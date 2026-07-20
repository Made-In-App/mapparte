<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Xoo_Sl_Handler{

	protected static $_instance = null;

    public $settings;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	public function __construct(){
        $this->settings = xoo_sl_helper()->get_general_option();
        $this->hooks();
	}

	public function hooks(){
	    add_action( 'wp_ajax_xoo_sl_fb_data', array( $this, 'xoo_sl_fb_data' ) );
	    add_action( 'wp_ajax_nopriv_xoo_sl_fb_data', array( $this, 'xoo_sl_fb_data' ) );
	    add_action( 'wp_ajax_xoo_sl_google_data', array( $this, 'xoo_sl_google_data' ) );
	    add_action( 'wp_ajax_nopriv_xoo_sl_google_data', array( $this, 'xoo_sl_google_data' ) );
	}

	private function base64url_decode( $value ) {
		$remainder = strlen( $value ) % 4;
		if ( $remainder ) {
			$value .= str_repeat( '=', 4 - $remainder );
		}
		return base64_decode( strtr( $value, '-_', '+/' ), true );
	}

	private function get_google_certificates() {
		$certificates = get_transient( 'xoo_sl_google_certificates' );
		if ( is_array( $certificates ) && $certificates ) {
			return $certificates;
		}

		$response = wp_remote_get( 'https://www.googleapis.com/oauth2/v1/certs', array( 'timeout' => 10 ) );
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return new WP_Error( 'xoo_sl_google_certs', __( 'Google login is temporarily unavailable.', 'social-login-woocommerce' ) );
		}

		$certificates = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! is_array( $certificates ) || ! $certificates ) {
			return new WP_Error( 'xoo_sl_google_certs', __( 'Google login is temporarily unavailable.', 'social-login-woocommerce' ) );
		}

		$ttl = HOUR_IN_SECONDS;
		$cache_control = wp_remote_retrieve_header( $response, 'cache-control' );
		if ( $cache_control && preg_match( '/max-age=(\d+)/', $cache_control, $matches ) ) {
			$ttl = max( 300, (int) $matches[1] );
		}
		set_transient( 'xoo_sl_google_certificates', $certificates, $ttl );

		return $certificates;
	}

	private function verify_google_credential( $credential ) {
		$parts = explode( '.', $credential );
		if ( 3 !== count( $parts ) ) {
			return new WP_Error( 'xoo_sl_google_token', __( 'Invalid Google login response.', 'social-login-woocommerce' ) );
		}

		$header  = json_decode( $this->base64url_decode( $parts[0] ), true );
		$payload = json_decode( $this->base64url_decode( $parts[1] ), true );
		$signature = $this->base64url_decode( $parts[2] );
		$client_id = isset( $this->settings['gl-goo-clientid'] ) ? (string) $this->settings['gl-goo-clientid'] : '';

		if ( ! is_array( $header ) || ! is_array( $payload ) || ! $signature ||
			'RS256' !== ( $header['alg'] ?? '' ) || empty( $header['kid'] ) ||
			empty( $payload['aud'] ) || ! hash_equals( $client_id, (string) $payload['aud'] ) ||
			! in_array( $payload['iss'] ?? '', array( 'accounts.google.com', 'https://accounts.google.com' ), true ) ||
			empty( $payload['exp'] ) || (int) $payload['exp'] < time() ||
			empty( $payload['email'] ) || empty( $payload['email_verified'] ) ) {
			return new WP_Error( 'xoo_sl_google_token', __( 'Invalid Google login response.', 'social-login-woocommerce' ) );
		}

		$certificates = $this->get_google_certificates();
		if ( is_wp_error( $certificates ) ) {
			return $certificates;
		}
		if ( empty( $certificates[ $header['kid'] ] ) || 1 !== openssl_verify( $parts[0] . '.' . $parts[1], $signature, $certificates[ $header['kid'] ], OPENSSL_ALGO_SHA256 ) ) {
			return new WP_Error( 'xoo_sl_google_signature', __( 'Invalid Google login response.', 'social-login-woocommerce' ) );
		}

		return $payload;
	}

	public function xoo_sl_google_data() {
		nocache_headers();
		if ( ! check_ajax_referer( 'xoo_sl_google_login', 'security', false ) ) {
			wp_send_json( array( 'success' => 'false', 'message' => xoo_sl_add_notice( __( 'The session has expired. Please reload the page.', 'social-login-woocommerce' ), 'error' ) ) );
		}

		$credential = isset( $_POST['credential'] ) ? sanitize_text_field( wp_unslash( $_POST['credential'] ) ) : '';
		$payload = $this->verify_google_credential( $credential );
		if ( is_wp_error( $payload ) ) {
			wp_send_json( array( 'success' => 'false', 'message' => xoo_sl_add_notice( $payload->get_error_message(), 'error' ) ) );
		}

		$_POST['userInfo'] = array(
			'social_type' => 'google',
			'email'        => sanitize_email( $payload['email'] ),
			'first_name'   => sanitize_text_field( $payload['given_name'] ?? '' ),
			'last_name'    => sanitize_text_field( $payload['family_name'] ?? '' ),
			'id'           => sanitize_text_field( $payload['sub'] ?? '' ),
			'name'         => sanitize_text_field( $payload['name'] ?? '' ),
		);

		$this->xoo_sl_fb_data();
	}

    public static function update_user_social_login_status( $user_id, $social_type ){
        update_user_meta( $user_id, '_xoo-sl-social-login', sanitize_text_field( $social_type ) );
    }
	

    //Social login handler
	public function xoo_sl_fb_data(){

		$user_data 	 = apply_filters( 'xoo_sl_social_login_user_data', $_POST['userInfo'] );
        
		$email 		 = sanitize_email( $user_data[ 'email' ] );

		if( !$email ){
			wc_add_notice( __( 'Something went wrong, please try again later.' ,'social-login-woocommerce' ) );
			return;
		}

        do_action( 'xoo_sl_before_processing_userdata', $user_data );

		//Login user
		if( email_exists( $email ) ){
			$action = $this->login( $email );
		}
		else{
			$action = $this->register( $user_data );
		}

        if( is_wp_error( $action ) ){
            $args = array(
                'success' => 'false',
                'message' => xoo_sl_add_notice( $action->get_error_message(), 'error')
            );
        }
        else{
            $args = array(
                'success' => 'true',
                'message' => xoo_sl_add_notice( $this->settings['gl-txt-sucess'], 'success')
            );
        }

        wp_send_json( $args );
		wp_die();
	}


	public function login( $email ){

		$email = sanitize_email( $email );
		$user  = get_user_by( 'email', $email );

		// get_user_by() restituisce WP_User o false, mai WP_Error: il vecchio ! is_wp_error() era sempre vero
		// e poteva chiamare wp_set_auth_cookie su valori non validi. Inoltre remember + is_ssl() aiuta i cookie
		// impostati da admin-ajax.php (login dal sito, non da wp-login.php).
		if ( $user instanceof WP_User ) {

			wp_clear_auth_cookie();
			wp_set_current_user( $user->ID );
			wp_set_auth_cookie( $user->ID, true, is_ssl() );

			do_action( 'wp_login', $user->user_login, $user );

			return $user;
		}

		return new WP_Error( 'xoo_sl_user_not_found', __( 'User could not be found.', 'social-login-woocommerce' ) );
	}


	public function register( $user_data ){

        $email = sanitize_email( $user_data[ 'email' ] );

		 // Check the email address.
        if ( empty( $email ) || ! is_email( $email ) ) {
            return new WP_Error( 'registration-error-invalid-email', __( 'Please provide a valid email address.', 'social-login-woocommerce' ) );
        }

        if ( email_exists( $email ) ) {
            return new WP_Error( 'registration-error-email-exists',  __( 'An account is already registered with your email address. Please log in.', 'social-login-woocommerce' ) );
        }

        // Handle username creation.
        if ( isset( $user_data['username'] ) && !empty( $user_data['username'] ) ) {
            $username = sanitize_user( $username );

            if ( empty( $username ) || ! validate_username( $username ) ) {
                return new WP_Error( 'registration-error-invalid-username', __( 'Please enter a valid account username.', 'social-login-woocommerce' ) );
            }

            if ( username_exists( $username ) ) {
                return new WP_Error( 'registration-error-username-exists', __( 'An account is already registered with that username. Please choose another.', 'social-login-woocommerce' ) );
            }
        } else {
            $username = sanitize_user( current( explode( '@', $email ) ), true );

            // Ensure username is unique.
            $append     = 1;
            $o_username = $username;

            while ( username_exists( $username ) ) {
                $username = $o_username . $append;
                $append++;
            }
        }

        // Handle password creation.
		$password           = wp_generate_password();
		$password_generated = true;

        // Use WP_Error to handle registration errors.
        $errors = new WP_Error();

        $errors = apply_filters( 'xoo_sl_registration_errors', $errors, $username, $email );

        if ( $errors->get_error_code() ) {
            return $errors;
        }

        $new_customer_data = apply_filters( 'xoo_sl_new_customer_data', array(
            'user_login' => $username,
            'user_pass'  => $password,
            'user_email' => $email,
            'role'       => 'customer',
            'first_name' => isset( $user_data['first_name'] ) ? sanitize_text_field( $user_data['first_name'] ) : '',
            'last_name'  => isset( $user_data['last_name'] ) ? sanitize_text_field( $user_data['last_name'] ) : '',
        ) );

        $customer_id = wp_insert_user( $new_customer_data );

        if ( is_wp_error( $customer_id ) ) {
            wp_send_json(
                array(
                    'success' => 'false',
                    'message' => xoo_sl_add_notice( $action->get_error_message(), 'error')
                )
            );
        }

        self::update_user_social_login_status( $customer_id, $user_data['social_type'] );

        do_action( 'xoo_sl_created_customer', $customer_id, $new_customer_data, $password_generated );

		return $this->login( $email );
	}
}

function xoo_sl_handler(){
	return Xoo_Sl_Handler::get_instance();
}
xoo_sl_handler();

?>

<?php

namespace Mapparte;

/**
 * Class Login
 *
 * @package Mapparte
 */
class Login extends Rest_Api {

	/**
	 * Book constructor.
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb = &$wpdb;

		add_action( 'rest_api_init', [ $this, 'add_login_api' ] );
		add_action( 'rest_api_init', [ $this, 'add_social_login_api' ] );
	}

	/**
	 * Add a REST route to login
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/login/
	 *
	 * @method POST
	 *
	 * @apiParam {string} [user] User
	 * @apiParam {string} [password] Password
	 */
	public function add_login_api() {
		register_rest_route( self::NAMESPACE, '/login', array(
			'methods'             => \WP_REST_Server::CREATABLE,
			'callback'            => [ $this, 'mapparte_login' ],
			'permission_callback' => [ $this, 'permission_callback' ],
			'args'                => array(
				'user'     => array(
					'description' => __( "User login", 'mapparte' ),
					'type'        => 'string',
					'default'     => '',
				),
				'password' => array(
					'description' => __( "User password", 'mapparte' ),
					'type'        => 'string',
					'default'     => '',
				),
			),
		) );
	}

	/**
	 * Check login and return the Application Password for the User
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 *
	 */
	public function mapparte_login( $request ) {

		$params = $request->get_params();

		$creds = array(
			'user_login'    => sanitize_text_field( $params['user'] ),
			'user_password' => sanitize_text_field( $params['password'] ),
			'remember'      => true
		);

		$user = wp_authenticate( $creds['user_login'], $creds['user_password'] );

		if ( is_wp_error( $user ) ) {
			$success = false;
			$data    = $user;
		} else {
			$success = 'true';

			$request = new \WP_REST_Request( 'POST', sprintf( '/wp/v2/users/%d/application-passwords', $user->data->ID ) );
			$request->set_query_params( array(
				'name' => bin2hex( random_bytes( 20 ) )
			) );
			$rest_response = rest_do_request( $request );
			$server        = rest_get_server();

			$data = $server->response_to_data( $rest_response, false );;
			$data['ID']         = $user->data->ID;
			$data['user_login'] = $user->data->user_login;
			$data['user_email'] = $user->data->user_email;
		}

		// return any necessary data in the response
		return rest_ensure_response( [
			'success' => $success,
			'data'    => $data,
		] );
	}

	/**
	 * Add a REST route to login
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/social-login/
	 *
	 * @method POST
	 *
	 * @apiParam {string} [social_type] Social type
	 * @apiParam {string} [email] Email
	 * @apiParam {string} [first_name] First name
	 * @apiParam {string} [last_name] Last name
	 * @apiParam {string} [id] Social ID
	 */
	public function add_social_login_api() {
		register_rest_route( self::NAMESPACE, '/social-login', array(
			'methods'             => \WP_REST_Server::CREATABLE,
			'callback'            => [ $this, 'mapparte_social_login' ],
			'permission_callback' => [ $this, 'permission_callback' ],
			'args'                => array(
				'social_type' => array(
					'description' => "Social type",
					'type'        => 'string',
					'default'     => '',
				),
				'email'       => array(
					'description' => "Email",
					'type'        => 'string',
					'default'     => '',
				),
				'first_name'  => array(
					'description' => "First name",
					'type'        => 'string',
					'default'     => '',
				),
				'last_name'   => array(
					'description' => "Last name",
					'type'        => 'string',
					'default'     => '',
				),
				'social_id'   => array(
					'description' => "Social ID",
					'type'        => 'string',
					'default'     => '',
				)
			),
		) );
	}

	/**
	 * Check Social login and return the Application Password for the User
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 *
	 */
	public function mapparte_social_login( $request ) {

		$user_data = $request->get_params();

		$email = sanitize_email( $user_data['email'] );

		if ( ! $email ) {
			$args = array(
				'success' => 'false',
				'message' => "Email is a mandatory field."
			);
		} else {

			//Login user
			if ( email_exists( $email ) ) {
				$user = $this->login( $email );
			} else {
				$user = $this->register( $user_data );
			}

			if ( is_wp_error( $user ) ) {
				$args = array(
					'success' => 'false',
					'message' => $user->get_error_message()
				);
			} else {

				$request = new \WP_REST_Request( 'POST', sprintf( '/wp/v2/users/%d/application-passwords', $user->data->ID ) );
				$request->set_query_params( array(
					'name' => bin2hex( random_bytes( 20 ) )
				) );
				$rest_response = rest_do_request( $request );
				$server        = rest_get_server();

				$data = $server->response_to_data( $rest_response, false );;
				$data['ID']         = $user->data->ID;
				$data['user_login'] = $user->data->user_login;
				$data['user_email'] = $user->data->user_email;

				$args = array(
					'success' => 'true',
					'message' => __( "Logged user", 'mapparte' ),
					'data'    => $data
				);
			}
		}


		// return any necessary data in the response
		return rest_ensure_response( $args );
	}

	public function login( $email ) {

		$email = sanitize_email( $email );
		$user  = get_user_by( 'email', $email );

		return $user; //returns wp_error if login unsucesful.

	}


	public function register( $user_data ) {

		$email = sanitize_email( $user_data['email'] );

		// Check the email address.
		if ( empty( $email ) || ! is_email( $email ) ) {
			return new \WP_Error( 'registration-error-invalid-email', __( 'Please provide a valid email address.', 'social-login-woocommerce' ) );
		}

		if ( email_exists( $email ) ) {
			return new \WP_Error( 'registration-error-email-exists', __( 'An account is already registered with your email address. Please log in.', 'social-login-woocommerce' ) );
		}


		$username = sanitize_user( current( explode( '@', $email ) ), true );

		// Ensure username is unique.
		$append     = 1;
		$o_username = $username;

		while ( username_exists( $username ) ) {
			$username = $o_username . $append;
			$append ++;
		}

		// Handle password creation.
		if ( isset( $user_data['password'] ) && ! empty( $user_data['password'] ) ) {
			$password = wp_generate_password();
		}

		// Use WP_Error to handle registration errors.
		$errors = new \WP_Error();

		if ( $errors->get_error_code() ) {
			return $errors;
		}

		$new_customer_data = array(
			'user_login' => $username,
			'user_pass'  => $password,
			'user_email' => $email,
			'role'       => 'contributor',
			'first_name' => isset( $user_data['first_name'] ) ? sanitize_text_field( $user_data['first_name'] ) : '',
			'last_name'  => isset( $user_data['last_name'] ) ? sanitize_text_field( $user_data['last_name'] ) : '',
		);

		$customer_id = wp_insert_user( $new_customer_data );

		if ( is_wp_error( $customer_id ) ) {
			return [
				'success' => 'false',
				'message' => __( 'Something went wrong during the registration', 'mapparte' )
			];
		}

		update_user_meta( $customer_id, '_xoo-sl-social-login', sanitize_text_field( $user_data['social_type'] ) );
		update_user_meta( $customer_id, 'mapparte-sl-social_id', sanitize_text_field( $user_data['social_id'] ) );

		return $this->login( $email ); //Everything is good , login user.

	}

}

new Login();

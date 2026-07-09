<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Xoo_Uv_Core{

	protected static $_instance = null;

	public static $woocommerce = false;

	public $wc_email = false;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	public function __construct(){

		self::$woocommerce 	= class_exists( 'woocommerce' );

		add_filter( 'authenticate', array( $this, 'verify_user_on_login' ), 30, 3 );
		add_action( 'template_redirect', array($this, 'activate_user') );
		add_action( 'user_register', array( $this, 'create_db_fields_on_register' ), 10, 1 );
		add_action( 'woocommerce_checkout_update_user_meta', array( $this, 'prevent_login_on_checkout' ), 10, 2 );
		add_filter( 'woocommerce_registration_auth_new_customer', array( $this, 'prevent_woocommerce_signup_form_login_redirect' ), 9999, 2 );
		//Login singup compatibility
		add_filter( 'pre_option_xoo-el-general-options', array( $this, 'prevent_login_popup_auto_login' ), 9999 );
		add_filter( 'xoo_el_registration_success_notice', array( $this, 'login_popup_success_notice' ), 9999, 2 );
		add_filter( 'xoo_el_registration_redirect', array( $this, 'login_popup_prevent_registration_redirect' ), 9999 );

	}


	


	//Create datbase fields for email verification on user registration
	public function create_db_fields_on_register( $user_id ){

		//If woocommerce - checks
		// - Auto generate password
		// - If account is created via checkout & verification is disabled
		if( self::$woocommerce && ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) || ( xoo_uv_helper()->get_general_option('m-verify-chk') === "yes" && isset( $_REQUEST['woocommerce-process-checkout-nonce'] ) ) ) ){
			$this->update_user_status( $user_id, 'active' );
			return;
		}

		$user_data = get_userdata( $user_id );

		update_user_meta( $user_id, 'xoo-uv-active', "no" );
		update_user_meta( $user_id, 'xoo-uv-sent-email-count', 0 );

		$permission = apply_filters( 'xoo_uv_send_email_permission', true, $user_id );

		if( $permission ){
			xoo_uv_email()->send( $user_id );
		}

		//Add WC Notice only if registered via woocommerce form
		if( self::$woocommerce && !isset( $_POST['_xoo_el_form'] ) ){
			wc_add_notice( xoo_uv_helper()->get_general_option( 'txt-onregister' ) );
		}

	}


	//Verify user on login
	public function verify_user_on_login( $user, $username, $password ){

		$user_data 	= get_user_by( 'login', $username );

		if( !$user_data ){
			$user_data = get_user_by( 'email', $username );
		}

		if( !$user_data ) return $user;

		$user_id 	= $user_data->ID;
		
		if( xoo_uv_is_user_active( $user_id ) ) return $user; // exit if user is verified or previous users.
		
		$sent_email_count = (int) get_user_meta( $user_id, 'xoo-uv-sent-email-count', true );
		
		//Check if first time registration
		if( $sent_email_count === 0 ){
			xoo_uv_email()->send( $user_id );
			return new WP_Error( 'xoo-uv-verify-notice-first', xoo_uv_helper()->get_general_option( 'txt-onregister' ) );
		}
		else{
			return new WP_Error( 'xoo-uv-verify-notice', xoo_uv_helper()->get_general_option( 'txt-login-error' ) );
		}



	}

	public function activate_user(){

		// If user is already activated, add wc notice once.
		if( isset( $_COOKIE['xoo-uv-verified-notice'] ) && $_COOKIE['xoo-uv-verified-notice'] === "not-printed"  ){
			if( !function_exists('xoo_el') && self::$woocommerce ){
				wc_add_notice( __( 'Your email has been successfully verified. Please Login', 'user-verification-woocommerce' ) );
			}
			self::set_verified_notice_cookie();
			return;
		}

		if( !isset($_GET['user']) || !isset($_GET['hash']) ) return;

		$user = get_user_by( 'ID', $_GET['user'] );

		if( !$user ) return;

		$is_activated = xoo_uv_is_user_active( $user->ID ); 

		$db_hash = get_user_meta( $user->ID, 'xoo-uv-email-hash', true );

		if( $db_hash && $_GET['hash'] === $db_hash && !$is_activated ){

			$this->update_user_status( $user->ID, 'active' );

			self::set_verified_notice_cookie( 'not-printed' );

			do_action( 'xoo_uv_user_activated', $user );

		}

		$url = remove_query_arg( array(
			'hash', 'user'
		) );

		wp_safe_redirect( apply_filters( 'xoo_uv_verified_redirect', $url, $user ) );

	}



	/**
	 * Set or unset the cookie.
	 *
	 * @param string $value Cookie value.
	 */
	public static function set_verified_notice_cookie( $value = '' ) {

		$cookie = 'xoo-uv-verified-notice';

		$path   = isset( $_SERVER['REQUEST_URI'] ) ? current( explode( '?', wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) : ''; // WPCS: input var ok, sanitization ok.

		if ( $value ) {
			setcookie( $cookie, $value, 0, $path, COOKIE_DOMAIN, is_ssl(), true );
		} else {
			setcookie( $cookie, ' ', time() - YEAR_IN_SECONDS, $path, COOKIE_DOMAIN, is_ssl(), true );
		}
	}


	public function prevent_login_on_checkout( $customer_id, $data ){

		if( !xoo_uv_is_user_active( $customer_id ) && xoo_uv_helper()->get_general_option( 'm-verify-chk' ) !== "yes" ){
			wp_clear_auth_cookie();
		}

	}

	public function update_user_status( $user_id, $status = 'active'){

		if( $status === 'active' ){
			$value = 'yes';
		}
		else{
			$value = 'no';
		}	
		
		update_user_meta( $user_id, 'xoo-uv-active', $value );

		do_action( 'xoo_uv_user_status_updated', $user_id, $status );
	
	}

	//If user registers from woocommerce form, do not login
	public function prevent_woocommerce_signup_form_login_redirect( $value, $new_customer ){
		return false;
	}


	//Prevent login signup auto login
	public function prevent_login_popup_auto_login( $value ){

		 remove_filter( 'pre_option_xoo-el-general-options', array( $this, 'prevent_login_popup_auto_login' ), 9999 );

	    $gl_options = get_option( 'xoo-el-general-options' );

	    add_filter( 'pre_option_xoo-el-general-options', array( $this, 'prevent_login_popup_auto_login' ), 9999 );

		$gl_options[ 'm-en-auto-login' ] = "no";

		return $gl_options;
	}

	//Prevent redirect after registration via login/signup popup
	public function login_popup_prevent_registration_redirect( $redirect ){
		return false;
	}

	//Return success notice
	public function login_popup_success_notice( $notice, $new_customer ){
		return xoo_uv_helper()->get_general_option( 'txt-onregister' );
	}


}

function xoo_uv_core(){
	return Xoo_Uv_Core::get_instance();
}
xoo_uv_core();

?>

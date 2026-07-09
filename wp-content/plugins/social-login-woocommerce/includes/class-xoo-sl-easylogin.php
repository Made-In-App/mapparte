<?php

class Xoo_Sl_EasyLogin{

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

		//If social login is enabled inside popup
		if( in_array( 'popup', $this->settings['gl-m-show'] ) ){
			add_action( 'xoo_el_form_end', array( $this, 'output_social_buttons' ), 10, 2 );
		}

		//force registration is enabled
		//add_action( 'xoo_sl_before_processing_userdata', array( $this, 'force_registration'), 10, 1 );

		//Set social login status after account has been created with force register
		add_action( 'xoo_el_created_customer', array( $this, 'handle_user_social_login_status' ), 10, 2 );
	}

	public function output_social_buttons( $form, $args ){

		if( !in_array( $form, $this->settings['gl-m-show-form'] ) || empty( xoo_sl_active_social_login() ) ) return; //check if any social login is active
		xoo_sl_get_social_buttons(); //Get buttons HTML
		
	}


	public function force_registration( $user_data ){

		$email = sanitize_email( $user_data[ 'email' ] );
		if( email_exists( $email ) ) return; // exit if user is already registered

		//Keep social data in cookie to update later when user account has been created
		//Setting data to cookie
		setcookie( 'xoo_el_social_data', json_encode( array(
			'email' => $user_data['email'],
			'type' 	=> $user_data['social_type'],
		) ) );

		wp_send_json(
			array(
				'register' => 'yes',
				'userData' => $user_data
			)
		);

	}

	//Save values stored in cookie to user meta
	public function handle_user_social_login_status( $customer_id, $new_customer_data ){
		if( !isset( $_COOKIE['xoo_ml_user_ip_data'] ) ) return;
		$social_data = json_decode( stripslashes( $_COOKIE['xoo_ml_user_ip_data'] ), true );

		//Update only when social account email id is used while creating account.
		if( $social_data['email'] === $new_customer_data['user_email'] ){
			xoo_sl_handler::update_user_social_login_status( $customer_id, $social_data['type'] );
			//Auto verify user
			if( class_exists( 'Xoo_Uv_Core' ) ){
				xoo_uv_core()->update_user_status( $customer_id, 'active' );
			}
		}
		unset( $_COOKIE['xoo_el_social_data'] );
	}

}

function xoo_el_easylogin(){
	return Xoo_Sl_EasyLogin::get_instance();
}

xoo_el_easylogin();

?>
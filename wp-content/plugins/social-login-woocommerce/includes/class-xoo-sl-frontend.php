<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Xoo_Sl_Frontend{

	protected static $_instance = null;

	public $settings = array();

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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'login_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		add_action( 'wp_footer', array( $this, 'notice_markup' ) );
		add_action( 'login_footer', array( $this, 'notice_markup' ) );
	}

	//Enqueue stylesheets
	public function enqueue_styles(){
		wp_enqueue_style( 'xoo-sl-style', XOO_SL_URL.'/assets/css/xoo-sl-style.css', array(), XOO_SL_VERSION );
		ob_start();
		xoo_sl_helper()->get_template( 'inline-style.php' );
		wp_add_inline_style( 'xoo-sl-style', ob_get_clean() );
	}

	//Enqueue javascript
	public function enqueue_scripts(){

		if( $this->settings['gl-fb-en'] === "yes" ){
			wp_enqueue_script( 'xoo-sl-fb-sdk', XOO_SL_URL.'/assets/js/facebook/facebook-sdk.js', array( 'jquery' ), XOO_SL_VERSION, true ); //Facebook SDK
			wp_localize_script( 'xoo-sl-fb-sdk', 'xoo_sl_fb_localize', array(
				'adminurl'  => admin_url().'admin-ajax.php',
				'appID'		=> esc_attr( $this->settings['gl-fb-appid'] ),
			));
		}

		if( $this->settings['gl-goo-en'] === "yes" ){
			wp_enqueue_script('google-social-login', "https://accounts.google.com/gsi/client", array(), null, true );
			wp_enqueue_script( 'xoo-sl-google-sdk', XOO_SL_URL.'/assets/js/google/google-sdk.js', array( 'jquery', 'google-social-login' ), filemtime( XOO_SL_PATH . '/assets/js/google/google-sdk.js' ), true );
			wp_localize_script( 'xoo-sl-google-sdk', 'xoo_sl_google_localize', array(
				'adminurl'  => admin_url().'admin-ajax.php',
				'clientID'	=> esc_attr( $this->settings['gl-goo-clientid'] ),
				'nonce'     => wp_create_nonce( 'xoo_sl_google_login' ),
			));
		}
		
		wp_enqueue_script( 'xoo-sl-js', XOO_SL_URL.'/assets/js/xoo-sl-js.js', array( 'jquery' ), XOO_SL_VERSION, true );

		if( class_exists( 'woocommmerce' ) && is_checkout() ){
			$redirect_to = $_SERVER['REQUEST_URI'];
		}
		elseif( !empty( $this->settings['gl-red-url']) ){
			$redirect_to = $this->settings['gl-red-url'];
		}
		elseif( $GLOBALS['pagenow'] === 'wp-login.php' ){
			$redirect_to = admin_url();
		}
		else{
			$redirect_to = $_SERVER['REQUEST_URI'];
		}

		wp_localize_script( 'xoo-sl-js', 'xoo_sl_localize', array(
			'adminurl' 			=> admin_url().'admin-ajax.php',
			'redirect_to'		=> $redirect_to,
			//'force_register' 	=> $this->settings['gl-force-reg']
		));
	}

	//Logging in notice
	public function notice_markup(){
		?>
		<div class="xoo-sl-notice-container"></div>
		<?php
	}
}


function xoo_sl_frontend(){
	return Xoo_Sl_Frontend::get_instance();
}
xoo_sl_frontend();
?>

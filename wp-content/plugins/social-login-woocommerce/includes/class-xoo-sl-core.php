<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Xoo_Sl_Core{

	protected static $_instance = null;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	public function __construct(){
		$this->define_constants();
		$this->includes();
	}


	//Define Constants
	protected function define_constants(){

		define( "XOO_SL_PATH", plugin_dir_path( XOO_SL_PLUGIN_FILE ) ); // Plugin path
		define( "XOO_SL_URL", untrailingslashit( plugins_url( '/', XOO_SL_PLUGIN_FILE ) ) ); // plugin url
		define( "XOO_SL_PLUGIN_BASENAME", plugin_basename( XOO_SL_PLUGIN_FILE ) );
		define( "XOO_SL_VERSION", "1.1" ); //Plugin version

	}

	//Include files
	public function includes(){

		//xootix framework
		require_once XOO_SL_PATH.'/includes/xoo-framework/xoo-framework.php';
		require_once XOO_SL_PATH.'/includes/class-xoo-sl-helper.php';

		include_once XOO_SL_PATH.'includes/xoo-sl-functions.php';
		
		if( defined( 'XOO_EL' ) ){
			include_once XOO_SL_PATH.'includes/class-xoo-sl-easylogin.php';
		}

		if( $this->is_request( 'frontend' ) ){
			include_once XOO_SL_PATH.'includes/class-xoo-sl-frontend.php';
			include_once XOO_SL_PATH.'includes/class-xoo-sl-handler.php';
		}
		
		if ( $this->is_request( 'admin' ) ) {
			include_once XOO_SL_PATH.'admin/class-xoo-sl-admin-settings.php';
		}

	}


	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}
}


?>
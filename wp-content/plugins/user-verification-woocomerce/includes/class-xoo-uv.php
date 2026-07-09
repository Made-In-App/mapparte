<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class XOO_UV{

	protected static $_instance = null;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	public function __construct(){

		require_once XOO_UV_PATH.'includes/xoo-uv-functions.php';
		require_once XOO_UV_PATH.'includes/xoo-framework/xoo-framework.php';
		require_once XOO_UV_PATH.'includes/class-xoo-uv-helper.php';

		if($this->is_request('frontend')){
			require_once XOO_UV_PATH.'includes/class-xoo-uv-frontend.php';
		}
		
		if($this->is_request('admin')) {
			require_once XOO_UV_PATH.'admin/class-xoo-uv-admin-settings.php';
			require_once XOO_UV_PATH.'admin/includes/class-xoo-uv-users-table.php';
			xoo_uv_admin_settings();
		}

		require_once XOO_UV_PATH.'includes/class-xoo-uv-core.php';
		require_once XOO_UV_PATH.'includes/class-xoo-uv-email.php';

		add_action( 'wp_loaded', array( $this, 'on_install' ) );
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


	/**
	* On install
	*/
	public function on_install(){

		$version_option = 'xoo-uv-version';
		$db_version 	= get_option( $version_option );

		//If first time installed
		if( !$db_version ){
			$this->activate_users();
		}

		if( version_compare( $db_version, XOO_UV_VERSION, '<') ){
			//Update to current version
			update_option( $version_option, XOO_UV_VERSION);
		}
	}


	protected function activate_users(){
		$users = get_users();
		if( !empty( $users ) ){
			foreach ($users as $user) {
				update_user_meta( $user->ID, 'xoo-uv-active', 1 );
			}
		}
	}
}

?>
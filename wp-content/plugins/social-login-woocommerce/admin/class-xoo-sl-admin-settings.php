<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Xoo_Sl_Admin_Settings{

	protected static $_instance = null;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct(){
		$this->hooks();
	}

	public function hooks(){

		if( current_user_can( 'manage_options' ) ){
			add_action( 'init', array( $this, 'generate_settings' ), 0 );
			add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts') );

		add_filter( 'plugin_action_links_' . XOO_SL_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );

		add_filter( 'manage_users_columns', array( $this, 'user_page_add_columns' ) );
		add_filter( 'manage_users_custom_column', array( $this, 'user_page_columns_output' ), 11, 3 );

	}


	public function enqueue_scripts() {
		
		if( !xoo_sl_helper()->admin->is_settings_page() ) return;

		wp_enqueue_style( 'xoo-sl-admin-style', XOO_SL_URL . '/admin/assets/css/xoo-sl-admin-style.css', array(), XOO_SL_VERSION, 'all' );
		wp_enqueue_script( 'xoo-sl-admin-js', XOO_SL_URL . '/admin/assets/js/xoo-sl-admin-js.js', array( 'jquery' ), XOO_SL_VERSION, false );

		wp_localize_script( 'xoo-admin-js', 'xoo_admin_params', array(
			'adminurl'  => admin_url().'admin-ajax.php',
		) );

	}

	public function generate_settings(){
		xoo_sl_helper()->admin->auto_generate_settings();
	}


	/**
	 * Show action links on the plugin screen.
	 *
	 * @param	mixed $links Plugin Action links
	 * @return	array
	 */
	public function plugin_action_links( $links ) {
		$action_links = array(
			'settings' 	=> '<a href="' . admin_url( 'admin.php?page=easy-login-woocommerce-settings' ) . '">Settings</a>',
			'support' 	=> '<a href="https://xootix.com/support" target="__blank">Support</a>',
		);

		return array_merge( $action_links, $links );
	}


	public function add_menu_pages(){

		$args = array(
			'title' 		=> 'Social Login Settings',
			'menu_title' 	=> 'Social Login',
			'icon' 			=> 'dashicons-unlock',
		);

		if( defined( 'XOO_EL' ) ){
			$args['parent_slug'] = 'easy-login-woocommerce-settings';
			xoo_sl_helper()->admin->register_as_submenu_page( $args );
		}
		else{
			xoo_sl_helper()->admin->register_menu_page( $args );
		}

		
	}


	public function user_page_add_columns( $columns ){
		$columns['xoo_sl_login_type'] = 'Social Login';
	    return $columns;
	}


	public function user_page_columns_output( $val, $column_name, $user_id ){
		if( $column_name === "xoo_sl_login_type" ){
			$social_type = get_user_meta( $user_id, '_xoo-sl-social-login', true ); 

			switch ( $social_type ) {
				case 'facebook':
					$val = '<span class="dashicons dashicons-facebook"></span>';
					break;

				case 'google':
					$val = '<span class="dashicons dashicons-googleplus"></span>';
					break;
				
				default:
					$val = '<span class="dashicons dashicons-no-alt"></span>';
					break;
			}
			
		}

		return $val;
	}

}

function xoo_sl_admin_settings(){
	return Xoo_Sl_Admin_Settings::get_instance();
}
xoo_sl_admin_settings();

?>
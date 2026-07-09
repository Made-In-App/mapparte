<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Xoo_Uv_Admin_Settings{

	protected static $_instance = null;

	public static $callbacks;
	public $all_options_array = array();
	public $tabs = array();


	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct(){

		self::$callbacks = include (XOO_UV_PATH.'admin/includes/class-xoo-uv-callbacks.php');

		$this->set_tabs(); // Set tabs

		add_action( 'admin_init', array( $this, 'set_default_options' ) );

		add_action('admin_menu',array($this,'add_menu_page'));
		add_action('admin_enqueue_scripts',array($this,'enqueue_scripts'));

		add_action('admin_init',array($this,'display_all_settings'));
		add_filter( 'plugin_action_links_' . XOO_UV_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );

		add_action('admin_init', array($this, 'preview_email'));
	}


	public function set_tabs(){

		if( !empty( $this->tabs ) ){
			return $this->tabs;
		}

		$this->tabs = array(
			'email'   => __('Email','xoo-uv-woocommerce'),
			'general' => __('General','xoo-uv-woocommerce'),
		);

	}


	public function set_default_options(){

		$default_options = $this->get_all_options_array();
		if( empty( $default_options ) ) return;

		foreach ($default_options as $option_name => $settings ) {

			//Return current option value from the database
			$option_value = (array) get_option($option_name) ;

			foreach ($settings as $setting) {	
				if( $setting['type'] === 'setting' && isset( $setting['default'] ) && isset( $setting['id'] ) && !isset( $option_value[$setting['id']]) ){
					$option_value[$setting['id']] = $setting['default'];
				}
			}



			update_option( $option_name, $option_value );
			
		}
	}


	public function get_all_options_array(){

		if( !empty( $this->all_options_array ) ){
			return $this->all_options_array;
		}

		foreach ($this->tabs as $key => $title) {

			$path = XOO_UV_PATH.'admin/includes/options/'.$key.'-options.php'; 

			if( file_exists( $path ) ){
				$this->all_options_array[ 'xoo-uv-'.$key.'-options' ] = include $path;
			}
		}

		return $this->all_options_array;
	}


	public function enqueue_scripts($hook) {

		//Enqueue Styles only on plugin settings page
		if($hook != 'toplevel_page_xoo-uv'){
			return;
		}
		
		wp_enqueue_media(); // media gallery
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style( 'xoo-uv-admin-style', XOO_UV_URL . '/admin/assets/css/xoo-uv-admin-style.css', array(), XOO_UV_VERSION, 'all' );
		wp_enqueue_script( 'xoo-uv-admin-js', XOO_UV_URL . '/admin/assets/js/xoo-uv-admin-js.js', array( 'jquery','wp-color-picker'), XOO_UV_VERSION, false );

	}


	public function add_menu_page(){
		add_menu_page( 
			'User Verification Settings', //Page Title
			'User Verification', // Menu Titlle
			'manage_options',// capability
			'xoo-uv', // Menu Slug
			array($this,'menu_page_callback') // callback
		);
	}

	public function menu_page_callback(){
		$args = array(
			'tabs' 		=> $this->tabs
		);
		xoo_uv_helper()->get_template( "admin/templates/xoo-uv-admin-display.php", $args, XOO_UV_PATH );
	}


	public function display_all_settings(){

		$default_options = $this->get_all_options_array();

		foreach ( $default_options as $option_name => $settings ) {
			$this->generate_settings( $settings, $option_name, $option_name, $option_name);
		}
	}


	public function generate_settings( $setting_fields, $page, $group, $option_name ){

		if(empty($setting_fields)){
			return;
		}

		foreach ($setting_fields as $field) {

			//Arguments for add_settings_field
			$args = $field;

			if( !isset($field['id']) || !isset($field['type']) || !isset($field['callback']) ) {
				continue;
			}

			//Check for callback functions
			if( is_callable( array( self::$callbacks, $field['callback'] ) ) ){
				$callback = array( self::$callbacks, $field['callback'] );
			}
			elseif ( is_callable( $field['callback'] ) ) {
				$callback = $field['callback'];
			}
			else{
				continue;
			}

			$title = isset($field['title']) ? $field['title'] : null;

			//Add a section
			if( $field['type'] === 'section' ){

				add_settings_section(
					$field['id'],
					$title,
					$callback,
					$page
				);

			}

			//Add a setting field
			elseif( $field['type'] === 'setting' ){

				add_settings_field(
					$field['id'],
					$title,
					$callback,
					$page,
					$field['section'],
					$args
				);

			}

		}

		register_setting( $group, $option_name);

	}


	/**
	 * Show action links on the plugin screen.
	 *
	 * @param	mixed $links Plugin Action links
	 * @return	array
	 */
	public function plugin_action_links( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=xoo-uv' ) . '">' . __('Settings', 'xoo-uv-woocommerce' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}


	//preview email
	public function preview_email(){
		
		if( !isset( $_GET['xoo_el_preview_email'] ) ) return;

		//Get a random user for demo email
		$args = array(
			'meta_key' 	=> 'xoo-uv-active',
			'meta_value' 	=> 'no',
			'meta_compare' 	=> '=',
			'number' 	=> 1
		);

		$query = new WP_User_Query( $args );
		$users = $query->get_results();
		if( empty($users) ){
			echo 'All users are verified, please set one of the users status to "verification pending" to generate a demo email. Go to any user\'s edit page & set "Account Status" to verification pending';
		}else{
			$user = $users[0];
			xoo_uv_email()->preview( $user->ID );
		}
		exit;

	}

}

function xoo_uv_admin_settings(){
	return Xoo_Uv_Admin_Settings::get_instance();
}


?>

<?php
/**
* Plugin Name: Social Login for WooCommerce
* Plugin URI: http://xootix.com/social-login-for-woocommerce
* Author: XootiX
* Version: 1.1
* Text Domain: social-login-woocommerce
* Domain Path: /languages
* Author URI: http://xootix.com
* Description: Login users via social media accounts.
* Tags: social login, facbook login, woocommerce 
*/


if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( !defined( 'XOO_SL' ) ){
	define( 'XOO_SL', true);
}

if ( ! defined( 'XOO_SL_PLUGIN_FILE' ) ) {
	define( 'XOO_SL_PLUGIN_FILE', __FILE__ );
}

//Initialize plugin
function xoo_sl_init(){

	do_action( 'xoo_sl_before_plugin_activation' );

	if ( ! class_exists( 'xoo_sl_Core' ) ) {
		require plugin_dir_path( __FILE__ ).'/includes/class-xoo-sl-core.php';
	}

	Xoo_Sl_Core::get_instance(); //Begin

	do_action( 'xoo_sl_loaded' );

}
	
add_action( 'plugins_loaded', 'xoo_sl_init', 15 );
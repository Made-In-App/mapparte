<?php
/**
* Plugin Name: Login/Signup Popup - Custom Fields Add-on 
* Plugin URI: http://xootix.com/easy-login-for-woocommerce
* Author: XootiX
* Version: 1.0
* Text Domain: easy-login-woocommerce
* Domain Path: /languages
* Author URI: http://xootix.com
* Description: Add custom fields to registration form
*/


//Exit if accessed directly
if(!defined('ABSPATH')){
	return;
}

define( "XOO_ELCF_VERSION", "1.0" ); //Plugin version

function xoo_el_add_new_fields( $allow, $aff ){
	if( $aff->plugin_slug === 'easy-login-woocommerce' ) return true;
	return $allow;
}
add_filter( 'xoo_aff_add_fields', 'xoo_el_add_new_fields', 20, 2 );


/**
 * Show action links on the plugin screen.
 *
 * @param	mixed $links Plugin Action links
 * @return	array
 */
function xoo_elcf_plugin_action_links( $links ) {
	$action_links = array(
		'settings' 	=> '<a href="' . admin_url( 'admin.php?page=xoo-el-fields' ) . '">Fields</a>',
		'support' 	=> '<a href="https://xootix.com/support" target="__blank">Support</a>',
	);

	return array_merge( $action_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'xoo_elcf_plugin_action_links' );
<?php
/**
* Plugin Name: User Verification WooCommerce
* Plugin URI: http://xootix.com/user-verification-woocommerce
* Author: XootiX
* Version: 1.2
* Text Domain: user-verification-woocommerce
* Domain Path: /languages
* Author URI: http://xootix.com
* Description: Sends email verification link on new account creation.
* Tags: woocommerce email verification, email verification, email activate, woocommerce, registration, verify user signup
*/


//Exit if accessed directly
if(!defined('ABSPATH')){
	return;
}

define("XOO_UV_PATH",plugin_dir_path(__FILE__)); // Plugin path
define("XOO_UV_URL",plugins_url('',__FILE__)); // plugin url
define("XOO_UV_PLUGIN_BASENAME",plugin_basename( __FILE__ ));
define("XOO_UV_VERSION","1.2"); //Plugin version

/**
 * Initialize
 *
 * @since    1.0.0
 */
function xoo_uv_init(){
	

	do_action('xoo_uv_before_plugin_activation');

	if ( ! class_exists( 'Xoo_Uv' ) ) {
		require XOO_UV_PATH.'/includes/class-xoo-uv.php';
	}

	xoo_uv();

	
}
add_action('plugins_loaded','xoo_uv_init',99);

function xoo_uv(){
	return Xoo_uv::get_instance();
}


/**
 * WooCommerce not activated admin notice
 *
 * @since    1.0.0
 */
function xoo_uv_install_wc_notice(){
	?>
	<div class="error">
		<p><?php _e( 'WooCommerce Login/Signup Popup is enabled but not effective. It requires WooCommerce in order to work.', 'xoo-uv-woocommerce' ); ?></p>
	</div>
	<?php
}
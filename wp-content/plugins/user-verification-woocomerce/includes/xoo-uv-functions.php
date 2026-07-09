<?php

//Get tempalte
if( !function_exists( 'xoo_get_template' ) ){
	function xoo_get_template ( $template_name, $path = '', $args = array(), $return = false ) {

	    $located = xoo_locate_template ( $template_name, $path );

	    if ( $args && is_array ( $args ) ) {
	        extract ( $args );
	    }

	    if ( $return ) {
	        ob_start ();
	    }

	    // include file located
	    if ( file_exists ( $located ) ) {
	        include ( $located );
	    }

	    if ( $return ) {
	        return ob_get_clean ();
	    }
	}
}


//Locate template
if( !function_exists( 'xoo_locate_template' ) ){
	function xoo_locate_template ( $template_name, $template_path ) {

	    // Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				'templates/' . $template_name,
				$template_name,
			)
		);

		//Check woocommerce directory for older version
		if( !$template && class_exists( 'woocommerce' ) ){
			if( file_exists( WC()->plugin_path() . '/templates/' . $template_name ) ){
				$template = WC()->plugin_path() . '/templates/' . $template_name;
			}
		}

	    if ( ! $template ) {
	        $template = trailingslashit( $template_path ) . $template_name;
	    }

	    return $template;
	}
}


function xoo_uv_wp_login_active_message( $message ){
	$message = __("Your email has been successfully verified. Please login.","user-verification-woocommerce");
	return '<div class="success">'.$message.'</div>';
}


function xoo_uv_add_active_notification( $user ){
	add_action('wp_footer', 'xoo_uv_verified_success_template');
}


//Is user active
function xoo_uv_is_user_active( $user_id ){
	return get_user_meta( $user_id, 'xoo-uv-active', true ) === "no" ? 0 : 1;
}


//Add woocommerce notice
function xoo_uv_add_verify_notice(){

	if( isset( $_COOKIE['xoo-uv-verified-notice'] ) && $_COOKIE['xoo-uv-verified-notice'] === 'not-printed' ){
		xoo_uv_helper()->get_template( 'xoo-uv-verified-success.php' );
	}

}
add_action('wp_footer','xoo_uv_add_verify_notice');

//Auto verify if account created via social logins
function xoo_uv_auto_verify_social_login( $customer_id, $new_customer_data, $password_generated ){

	Xoo_Uv_Core()->update_user_status( $customer_id, 'active' );

}
add_action( 'xoo_sl_created_customer', 'xoo_uv_auto_verify_social_login', 10, 3 );

?>
<?php

class Xoo_Uv_Helper extends Xoo_Helper{

	protected static $_instance = null;

	public static function get_instance( $slug, $path ){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $slug, $path );
		}
		return self::$_instance;
	}

	public function get_general_option( $subkey = '' ){
		return self::get_option( 'xoo-uv-general-options', $subkey );
	}

	public function get_email_option( $subkey = '' ){
		return self::get_option( 'xoo-uv-email-options', $subkey );
	}

}

function xoo_uv_helper(){
	return Xoo_Uv_Helper::get_instance( 'user-verification-woocommerce', XOO_UV_PATH );
}
xoo_uv_helper();

?>
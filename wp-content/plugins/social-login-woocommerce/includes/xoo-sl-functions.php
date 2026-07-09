<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Internationalization
if( !function_exists( 'xoo_sl_load_plugin_textdomain' ) ):
        function xoo_sl_load_plugin_textdomain() {
                $domain = 'social-login-woocommerce';
                $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
                load_textdomain( $domain, WP_LANG_DIR . '/'.$domain.'-' . $locale . '.mo' ); //wp-content languages
                load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' ); // Plugin Languages
        }   
        add_action('plugins_loaded','xoo_sl_load_plugin_textdomain',100);
endif;


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


//Hook social button on wp login
function xoo_sl_add_btns_on_wplogin(){

	//On Wp login page
	if( in_array( 'wplogin', xoo_sl_helper()->get_general_option( 'gl-m-show' ) ) ){
		xoo_sl_get_social_buttons();
	}

}
add_action( 'login_form', 'xoo_sl_add_btns_on_wplogin' );

//Add social buttons on woocommerce login form page
function xoo_sl_add_btns_on_wc_loginform(){
	
	$settings = xoo_sl_helper()->get_general_option();

	if( ( in_array( 'myaccount', $settings['gl-m-show'] ) ) || ( in_array( 'checkout', $settings['gl-m-show'] ) ) ){
		xoo_sl_get_social_buttons();
	}
}
add_action( 'woocommerce_login_form_end', 'xoo_sl_add_btns_on_wc_loginform' );
add_action( 'woocommerce_register_form_end', 'xoo_sl_add_btns_on_wc_loginform' );

function xoo_sl_all_login_options(){
	return array(
		'facebook',
		'google'
	);
}


function xoo_sl_get_social_buttons(){

	$settings = xoo_sl_helper()->get_general_option();

	$buttons = array(

		array(
			'type' 	  	=> 'facebook',
			'enable' 	=> $settings['gl-fb-en'],
			'text' 		=> $settings['gl-fb-btntxt'],	
			'class' 	=> 'xoo-sl-facebook-btn',
			'icon' 	 	=> 'xoo-sl-icon-facebook2',
		),

		array(
			'type' 	 	=> 'google',
			'enable' 	=> $settings['gl-goo-en'],
			'text' 		=> $settings['gl-goo-btntxt'],	
			'class'  	=> 'xoo-sl-google-btn',
			'icon' 	 	=> 'xoo-sl-icon-google',
		)
	);

	$buttons = apply_filters( 'xoo_sl_buttons_data', $buttons );

	$args = array(
		'buttons' 	=> $buttons,
		'heading' 	=> true
	);

	xoo_sl_helper()->get_template( 'xoo-sl-buttons.php', $args );
}

function xoo_sl_button_shortcode($user_atts){

	$atts = shortcode_atts( array(
		'type'		=> xoo_sl_all_login_options(),
		'text' 		=> 'show',
		'change_to' 	=> 'logout'
	), $user_atts, 'xoo_sl_button');

	if( is_user_logged_in() ){

		if( $atts[ 'change_to' ] === 'myaccount' ) {
			$change_to_link = wc_get_page_permalink( 'myaccount' );
			$change_to_text =  __( 'My account', 'social-login-woocommerce' );
		}
		else{
			$settings  	= xoo_sl_helper()->get_general_option();
			$logout_link 	= !empty( $settings[ 'm-logout-url' ] ) ? $settings[ 'm-logout-url' ] : $_SERVER[ 'REQUEST_URI' ];
			$change_to_link = wp_logout_url( $logout_link );
			$change_to_text =  __( 'Logout' ,'social-login-woocommerce' );
		}

		echo '<a href="'.$change_to_link.'" class="xoo-sl-changeto">'.$change_to_text.'</a>';
	}
	else{

		$button_text = $atts[ 'text' ] === "show" ? true : false;	
		xoo_sl_get_social_buttons( $atts[ 'type' ], $button_text, true );

	}

}
add_shortcode( 'xoo_sl_button', 'xoo_sl_button_shortcode' );




function xoo_sl_active_social_login(){
	$settings = xoo_sl_helper()->get_general_option();
	return $settings['gl-fb-en'] === "yes" || $settings['gl-goo-en'] === "yes";
}



function xoo_sl_add_notice( $message, $notice_type = 'success' ){

	if( $notice_type === "success" ){
		$notice_class 	= 'xoo-sl-notice-success';
		$icon_class 	= 'xoo-sl-icon-check_circle';
	}
	else{
		$notice_class 	= 'xoo-sl-notice-error';
		$icon_class 	= 'xoo-sl-icon-error';
	}

	$html  = '<div class="xoo-sl-notice '.$notice_class.'">';
	$html .= '<span class="xoo-sl-notice-icon '.$icon_class.'"></span>';
	$html .= '<span class="xoo-sl-notice-text">'.$message.'</span>';
	$html .= '</div>';

	return apply_filters( 'xoo_sl_notice_html', $html, $message, $notice_type );
}


?>
<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$option_name = 'xoo-uv-general-options';


$settings = array(
	
	array(
		'type' 			=> 'section',
		'callback' 		=> 'section',
		'id' 			=> 'main-section',
		'title' 		=> 'Main',
	),


	array(
		'type' 			=> 'setting',
		'callback' 		=> 'checkbox',
		'section' 		=> 'main-section',
		'option_name' 		=> $option_name,
		'id' 			=> 'm-verify-chk',
		'title' 		=> 'Auto verify user on checkout',
		'default' 		=> 'yes',
		'desc' 			=> 'If account creation is enabled on checkout, it will auto verify user.'
	),


	array(
		'type' 			=> 'setting',
		'callback' 		=> 'text',
		'section' 		=> 'main-section',
		'option_name' 		=> $option_name,
		'id' 			=> 'm-success-page',
		'title' 		=> 'Redirect to URL after verify',
		'default' 		=> '',
		'desc' 			=> 'Leave empty for home page.'
	),


	array(
		'type' 			=> 'section',
		'callback' 		=> 'section',
		'id' 			=> 'text-section',
		'title' 		=> 'Texts',
	),


	array(
		'type' 			=> 'setting',
		'callback' 		=> 'textarea',
		'section' 		=> 'text-section',
		'option_name' 		=> $option_name,
		'id' 			=> 'txt-onregister',
		'title' 		=> 'Registration Notice',
		'default' 		=> __( "Thank you for signing up. We need to verify your email address. Please check your inbox.", 'user-verification-woocommerce' )
	),


	array(
		'type' 			=> 'setting',
		'callback' 		=> 'textarea',
		'section' 		=> 'text-section',
		'option_name' 		=> $option_name,
		'id' 			=> 'txt-login-error',
		'title' 		=> 'Login Error notice',
		'default' 		=> __( "Your email verification is pending. Please check your inbox & verify your email address.", 'user-verification-woocommerce' )
	),


);

return $settings;

?>

<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$option_name = 'xoo-uv-email-options';


$settings = array(
	
	array(
		'type' 			=> 'section',
		'callback' 		=> 'section',
		'id' 			=> 'main-section',
		'title' 		=> 'Main',
	),


	array(
		'type' 			=> 'setting',
		'callback' 		=> 'text',
		'section' 		=> 'main-section',
		'option_name' 		=> $option_name,
		'id' 			=> 'm-send-name',
		'title' 		=> '"From" Name"',
		'default' 		=> get_bloginfo( 'name' ),
		'desc' 			=> ''
	),


	array(
		'type' 			=> 'setting',
		'callback' 		=> 'text',
		'section' 		=> 'main-section',
		'option_name' 		=> $option_name,
		'id' 			=> 'm-send-email',
		'title' 		=> '"From" Email"',
		'desc' 			=> '',
		'default' 		=> get_bloginfo( 'admin_email' ),
	),


	array(
		'type' 			=> 'setting',
		'callback' 		=> 'text',
		'section' 		=> 'main-section',
		'option_name' 	=> $option_name,
		'id' 			=> 'm-subject-txt',
		'title' 		=> 'Subject',
		'desc' 			=> '',
		'default' 		=> __('Please verify your email')
	),

	
	array(
		'type' 			=> 'setting',
		'callback' 		=> 'upload',
		'section' 		=> 'main-section',
		'option_name' 		=> $option_name,
		'id'			=> 'm-header-img',
		'title' 		=> 'Header Image',
		'desc'			=> 'Supported format: JPEG,PNG',
		'default'		=> '',
		'extra'			=> array(
			'upload_type' => 'image'
		)
	),


	array(
		'type' 			=> 'setting',
		'callback' 		=> 'textarea',
		'section' 		=> 'main-section',
		'option_name' 		=> $option_name,
		'id' 			=> 'm-body-txt',
		'title' 		=> 'Body Text',
		'desc' 			=> 'Shortcodes: [br] - Line Break, [username] - Username, [name] - Name [b] - Bold',
		'default' 		=> __("[b]Hello,[/b][br][br]Please activate the button below to verify your email address.",'user-verification-woocommerce')
	),


	array(
		'type' 			=> 'setting',
		'callback' 		=> 'textarea',
		'section' 		=> 'main-section',
		'option_name' 		=> $option_name,
		'id' 			=> 'm-footer-txt',
		'title' 		=> 'Footer Text',
		'desc' 			=> 'Shortcodes: [br] - Line Break, [username] - Username, [name] - Name, [b] - Bold',
		'default' 		=> __("Regards,[br]XootiX",'user-verification-woocommerce'),
	),


	array(
		'type' 			=> 'setting',
		'callback' 		=> 'text',
		'section' 		=> 'main-section',
		'option_name' 		=> $option_name,
		'id' 			=> 'm-verifybtn-txt',
		'title' 		=> 'Verify Button Text',
		'desc' 			=> '',
		'default' 		=> __("Verify my Email address",'user-verification-woocommerce')
	),

	array(
		'type' 			=> 'section',
		'callback' 		=> 'section',
		'id' 			=> 'style-section',
		'title' 		=> 'Style',
	),


	array(
		'type' 			=> 'setting',
		'callback' 		=> 'select',
		'section' 		=> 'style-section',
		'option_name' 	=> $option_name,
		'id'			=> 'sy-email-temp',
		'title' 		=> 'Email Template',
		'default' 		=> class_exists( 'woocommerce' ) ? 'woocommerce' : 'plugin',
		'extra'			=> array(
			'options' => array(
				'woocommerce' 	=> 'Woocommerce',
				'plugin' 		=> 'Plugin'
			)	
		)
	),


	array(
		'type' 			=> 'setting',	
		'callback' 		=> 'color',
		'section' 		=> 'style-section',
		'option_name' 		=> $option_name,
		'id'			=> 's-footer-bgcolor',
		'title' 		=> 'Footer BG Color',
		'default' 		=> '#444444'
	),


	array(
		'type' 			=> 'setting',	
		'callback' 		=> 'color',
		'section' 		=> 'style-section',
		'option_name' 		=> $option_name,
		'id'			=> 's-footer-txtcolor',
		'title' 		=> 'Footer Font Color',
		'default' 		=> '#ffffff'
	),


	array(
		'type' 			=> 'setting',	
		'callback' 		=> 'color',
		'section' 		=> 'style-section',
		'option_name' 		=> $option_name,
		'id'			=> 's-verify-btn-bgcolor',
		'title' 		=> 'Verify Button BG Color',
		'default' 		=> '#5bc255'
	),


	array(
		'type' 			=> 'setting',	
		'callback' 		=> 'color',
		'section' 		=> 'style-section',
		'option_name' 		=> $option_name,
		'id'			=> 's-verify-btn-txtcolor',
		'title' 		=> 'Verify Button Text Color',
		'default' 		=> ' #ffffff'
	),


	array(
		'type' 			=> 'setting',
		'callback' 		=> 'number',
		'section' 		=> 'style-section',
		'option_name' 		=> $option_name,
		'id' 			=> 's-verify-btn-hpad',
		'title' 		=> 'Verify Button Padding ↔',
		'desc' 			=> 'size in px',
		'default' 		=> '20'
	),


	array(
		'type' 			=> 'setting',
		'callback' 		=> 'number',
		'section' 		=> 'style-section',
		'option_name' 		=> $option_name,
		'id' 			=> 's-verify-btn-vpad',
		'title' 		=> 'Verify Button Padding ↨',
		'desc' 			=> 'size in px',
		'default' 		=> '10'
	),
);

return $settings;

?>

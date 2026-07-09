<?php

$settings = array(

	/* Main Style */
	array(
		'callback' 		=> 'checkbox_list',
		'title' 		=> 'Show Buttons on page',
		'id' 			=> 'gl-m-show',
		'section_id' 	=> 'gl_main',
		'args'			=> array(
			'options' => array(
				'popup' 		=> 'Popup',
				'myaccount' 	=> 'WC MyAccount',
				'checkout' 		=> 'WC Checkout',
				'wplogin' 		=> 'WP Login'
			)
		),
		'default' 		=> array( 'popup' ,'myaccount', 'checkout', 'wplogin')
	),


	/* Main Style */
	array(
		'callback' 		=> 'checkbox_list',
		'title' 		=> 'Show Buttons on form',
		'id' 			=> 'gl-m-show-form',
		'section_id' 	=> 'gl_main',
		'args'			=> array(
			'options' => array(
				'register' 		=> 'Register',
				'login' 		=> 'Login',
			)
		),
		'default' 		=> array( 'register' ,'login' )
	),


	array(
		'callback' 		=> 'text',
		'title' 		=> 'Redirect URL',
		'id' 			=> 'gl-red-url',
		'section_id' 	=> 'gl_main',
		'default' 		=> '',
		'desc' 			=> 'Leave empty to redirect on the same page'
	),

	array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Enable',
		'id' 			=> 'gl-fb-en',
		'section_id' 	=> 'gl_fb',
		'default' 		=> 'yes',
	),

	array(
		'callback' 		=> 'text',
		'title' 		=> 'App ID',
		'id' 			=> 'gl-fb-appid',
		'section_id' 	=> 'gl_fb',
		'default' 		=> '',
	),


	array(
		'callback' 		=> 'text',
		'title' 		=> 'Button Text',
		'id' 			=> 'gl-fb-btntxt',
		'section_id' 	=> 'gl_fb',
		'default' 		=> 'Facebook',
	),


	array(
		'callback' 		=> 'color',
		'title' 		=> 'Background Color',
		'id' 			=> 'gl-fb-bgcolor',
		'section_id' 	=> 'gl_fb',
		'default' 		=> '#4267b2',
	),


	array(
		'callback' 		=> 'color',
		'title' 		=> 'Text Color',
		'id' 			=> 'gl-fb-txtcolor',
		'section_id' 	=> 'gl_fb',
		'default' 		=> '#ffffff',
	),


	array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Enable',
		'id' 			=> 'gl-goo-en',
		'section_id' 	=> 'gl_goo',
		'default' 		=> 'yes',
	),

	array(
		'callback' 		=> 'text',
		'title' 		=> 'Client ID',
		'id' 			=> 'gl-goo-clientid',
		'section_id' 	=> 'gl_goo',
		'default' 		=> '',
	),


	array(
		'callback' 		=> 'text',
		'title' 		=> 'Button Text',
		'id' 			=> 'gl-goo-btntxt',
		'section_id' 	=> 'gl_goo',
		'default' 		=> 'Google',
	),


	array(
		'callback' 		=> 'color',
		'title' 		=> 'Background Color',
		'id' 			=> 'gl-goo-bgcolor',
		'section_id' 	=> 'gl_goo',
		'default' 		=> '#dd4c40',
	),


	array(
		'callback' 		=> 'color',
		'title' 		=> 'Text Color',
		'id' 			=> 'gl-goo-txtcolor',
		'section_id' 	=> 'gl_goo',
		'default' 		=> '#ffffff',
	),


	/** Button Style **/

	/* Main Style */
	array(
		'callback' 		=> 'select',
		'title' 		=> 'Display',
		'id' 			=> 'gl-btn-rows',
		'section_id' 	=> 'gl_btn',
		'args'			=> array(
			'options' => array(
				'each' 		=> 'Separate Row',
				'same' 		=> 'In a single row',
			)
		),
		'default' 		=> 'same'
	),

	array(
		'callback' 		=> 'number',
		'title' 		=> 'Width',
		'id' 			=> 'gl-btn-width',
		'section_id' 	=> 'gl_btn',
		'default' 		=> 150,
		'desc' 			=> 'size in px'
	),

	array(
		'callback' 		=> 'number',
		'title' 		=> 'Height',
		'id' 			=> 'gl-btn-height',
		'section_id' 	=> 'gl_btn',
		'default' 		=> 40,
		'desc' 			=> 'size in px'
	),


	array(
		'callback' 		=> 'number',
		'title' 		=> 'Border Radius',
		'id' 			=> 'gl-btn-borad',
		'section_id' 	=> 'gl_btn',
		'default' 		=> 0,
		'desc' 			=> 'size in px'
	),

	array(
		'callback' 		=> 'number',
		'title' 		=> 'Font Size',
		'id' 			=> 'gl-btn-fsize',
		'section_id' 	=> 'gl_btn',
		'default' 		=> 15,
		'desc' 			=> 'size in px'
	),


	/** Texts **/
	array(
		'callback' 		=> 'text',
		'title' 		=> 'Heading',
		'id' 			=> 'gl-txt-heading',
		'section_id' 	=> 'gl_texts',
		'default' 		=> 'Or Login Using',
	),

	array(
		'callback' 		=> 'text',
		'title' 		=> 'Success Notice',
		'id' 			=> 'gl-txt-sucess',
		'section_id' 	=> 'gl_texts',
		'default' 		=> 'Please wait...Signing you in...',
	),

);

/*if( defined( 'XOO_EL' ) ){
	$settings[] = array(
		'callback' 		=> 'checkbox',
		'title' 		=> 'Force Register',
		'id' 			=> 'gl-force-reg',
		'section_id' 	=> 'gl_main',
		'default' 		=> 'no',
		'desc' 			=> 'If enabled, user will be forced to fill form fields'
	);
}*/

return apply_filters( 'xoo_sl_admin_settings', $settings, 'style' );
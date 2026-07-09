<?php

$sections = array(

	/* General TAB Sections */
	array(
		'title' => 'Main',
		'id' 	=> 'gl_main',
		'tab' 	=> 'general',
	),


	array(
		'title' => 'Facebook',
		'id' 	=> 'gl_fb',
		'tab' 	=> 'general',
		'desc' 	=> '<a href="https://docs.xootix.com/easy-login-for-woocommerce/#setup-social" target="_blank">Documentation</a>'
	),


	array(
		'title' => 'Google',
		'id' 	=> 'gl_goo',
		'tab' 	=> 'general',
		'desc' 	=> '<a href="https://docs.xootix.com/easy-login-for-woocommerce/#setup-social" target="_blank">Documentation</a>'
	),

	array(
		'title' => 'Button Style',
		'id' 	=> 'gl_btn',
		'tab' 	=> 'general',
	),

	array(
		'title' => 'Texts',
		'id' 	=> 'gl_texts',
		'tab' 	=> 'general',
		'desc' 	=> 'Leave text empty to remove element'
	),

);

return apply_filters( 'xoo_sl_admin_settings_sections', $sections );
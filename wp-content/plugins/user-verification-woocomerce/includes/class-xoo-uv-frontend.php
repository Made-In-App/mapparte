<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Xoo_Uv_Frontend{

	public function __construct(){
		add_action('wp_enqueue_scripts',array($this,'enqueue_styles'));
		add_action('wp_enqueue_scripts',array($this,'enqueue_scripts'));
	}


	//Enqueue stylesheets
	public function enqueue_styles(){
		wp_enqueue_style('xoo-uv-style',XOO_UV_URL.'/assets/css/xoo-uv-style.css',array(),XOO_UV_VERSION);
		//wp_add_inline_style('xoo-uv-style','');
	}

	//Enqueue javascript
	public function enqueue_scripts(){
		
	}
	
}


new Xoo_Uv_Frontend();

?>

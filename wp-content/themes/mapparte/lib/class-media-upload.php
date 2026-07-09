<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class wrapper for Front End Media example
 */
class Front_End_Media {

	function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_filter( 'ajax_query_attachments_args', array( $this, 'filter_media' ) );
		add_shortcode( 'frontend-button', array( $this, 'frontend_shortcode' ) );
	}

	/**
	 * Call wp_enqueue_media() to load up all the scripts we need for media uploader
	 */
	function enqueue_scripts() {
		wp_enqueue_media();
		wp_enqueue_script( 'add-media-script', get_template_directory_uri() . '/assets/js/media.js', array( 'jquery' ) );
	}

	/**
	 * This filter insures users only see their own media
	 */
	function filter_media( $query ) {
		// admins get to see everything
		if ( ! current_user_can( 'manage_options' ) )
			$query['author'] = get_current_user_id();

		return $query;
	}

	function frontend_shortcode( $args ) {
		// check if user can upload files
		if ( current_user_can( 'upload_files' ) ) {
			$str = __( 'Aggiungi un\'immagine', 'mapparte' );
			return '<input id="frontend-button" type="button" value="' . $str . '" class="btn btn-primary" style="position: relative; z-index: 1;">';
		}

		return __( 'Devi essere loggato per fare uploadare immagini', 'mapparte' );
	}
}

new Front_End_Media();
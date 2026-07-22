<?php

namespace Mapparte;

/**
 * Class Frontend_Scripts
 *
 * @package Mapparte
 */
class Frontend_Scripts {

	/**
	 * Frontend_Booking constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
	}

	public function enqueue_styles() {
		$style_path = get_template_directory() . '/style.css';
		wp_enqueue_style( 'mapparte', get_template_directory_uri() . '/style.css', [], filemtime( $style_path ) );
	}

	public function enqueue_script() {
		if ( !is_singular( 'booking' ) ) {
			wp_enqueue_script( 'all-min', get_template_directory_uri() . '/assets/js/all.min.js' );
		}
		if ( is_singular( 'space' ) ) {
			$booking_script_path = get_template_directory() . '/assets/js/booking.js';
			wp_enqueue_script( 'booking-script', get_template_directory_uri() . '/assets/js/booking.js', array( 'jquery' ), filemtime( $booking_script_path ), true );
			wp_localize_script( 'booking-script', 'booking', array(
					'restURL'           => rest_url(),
					'getHome'           => get_home_url(),
					'restNonce'         => wp_create_nonce( 'wp_rest' ),
					'logged'            => is_user_logged_in(),
					'spaceId'           => get_the_ID(),
					'notAvailableLabel' => __('Non disponibile', 'mapparte' ),
					'selectTimeLabel'   => __('Seleziona un orario', 'mapparte' ),
					'alertTimeLabel'    => __('Seleziona data e ora valide', 'mapparte' ),
					'currentUserId'     => get_current_user_id(),
				)
			);
			wp_enqueue_script( 'google-map', get_template_directory_uri() . '/assets/js/google-map.js', array( 'jquery' ) );
		}
	}
}

new Frontend_Scripts();

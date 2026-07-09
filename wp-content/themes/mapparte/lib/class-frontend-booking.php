<?php

namespace Mapparte;

/**
 * Class Frontend_Booking
 *
 * @package Mapparte
 */
class Frontend_Booking {

	/**
	 * Frontend_Booking constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );
	}

	public function enqueue_script() {
		if ( is_singular( 'space' ) ) {
			wp_enqueue_script( 'booking-script', get_template_directory_uri() . '/assets/js/booking.js', array( 'jquery' ) );
			wp_localize_script( 'booking-script', 'booking', array(
					'restURL'           => rest_url(),
					'restNonce'         => wp_create_nonce( 'wp_rest' ),
					'logged'            => is_user_logged_in(),
					'spaceId'           => get_the_ID(),
					'notAvailableLabel' => 'Non disponibile',
					'selectTimeLabel'   => 'Seleziona un orario',
					'alertTimeLabel'    => 'Seleziona data e ora valide',
					'currentUserId'     => get_current_user_id(),
				)
			);
		}
	}
}

new Frontend_Booking();
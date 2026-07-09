<?php
/// IMPOSTA GLI END POINT PER LA
/// CREAZIONE DEI PAGAMENTI
require 'EndPoints.php';
require 'ConnectedAccount.php';
require 'Utils.php';

use Mapparte\Stripe;

/**
 * Class Init
 *
 * @package Mapparte
 */
class Init {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );
	}

	function enqueue_script() {
		global $post;
		if ( ( is_singular( 'booking' ) && isset( $post->post_status ) && 'accettata' === $post->post_status )
			|| ( is_page( 'dettaglio-sponsorizzazione' ) && isset( $_REQUEST['plan'] ) && ! isset( $_REQUEST['paymentIntentId'] ) ) ) {

			/// Aggiungiamo la libreria Elements di Stripe
			/// per sfruttare gli elementi di UI preconfezionati
			wp_register_script ('stripeElements', 'https://js.stripe.com/v3/', false, false, true);
			wp_enqueue_script  ('stripeElements');

			/// Registriamo il nostro >> script client custom <<
			/// con le azioni custom che servono ad ottenere
			/// la chiave segreta per l'accesso a stripe
			wp_register_script ('stripeclient', get_template_directory_uri().'/stripe/client/stripeclient.js', false, false, true);
			wp_enqueue_script  ('stripeclient');

			wp_register_style ('checkout', get_template_directory_uri().'/stripe/client/checkout.css',[],'0.1');
			wp_enqueue_style  ('checkout');
		}

	}

}

new Init();
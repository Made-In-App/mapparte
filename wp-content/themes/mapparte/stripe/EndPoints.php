<?php

namespace Mapparte\Stripe;

require_once( 'PaymentIntent.php' );

/**
 * Ha la responsabilità di impostare gli end points
 * per la parte client del checkout
 *
 */
class EndPoints {

	private $__basedir;

	private $sk;

	function __construct($sk) {

		$this->base_dir = get_template_directory_uri() . '/mapparte/stripe/';

		$this->sk = $sk;

		// this is the action called by the dialog box
		add_action( 'wp_ajax_secret', [ $this, 'getSecret' ] );
		add_action( 'wp_ajax_nopriv_secret', [ $this, 'getSecret' ] );

	}

	/**
	 * rispodne con la chiave segreta
	 * per le chiamate successive
	 *
	 * accetta come parametri in post
	 * -- nonce        : codice di sicurezza
	 * -- amount       : somma da pagare (decimali con virgola)
	 * -- platform_fee : percentuale da riconocere alla piattaforma
	 *
	 */
	function getSecret() {

		/// controlla che siano presenti tutti i parametri
		$post_parameters = [ 'nonce', 'amount', 'platform_fee' ];

		foreach ( $post_parameters as $parameter ) {
			if ( ! isset( $_POST[ $parameter ] ) ) {
				$this->response( json_encode( [ 'error' => "parameter $parameter is not present" ] ) );
			}
		}


		/// controlla il codice di sicurezza
		if ( ! wp_verify_nonce( urldecode( $_POST['nonce'] ), 'stripe-nonce-seed' ) ) {
			$this->response( json_encode( [ 'error' => "nonce validation failed" ] ) );
		}

		$amount               = urldecode( $_POST['amount'] );
		$platform_fee         = urldecode( $_POST['platform_fee'] );
		$connected_account_id = ( isset(  $_POST['connected_account_id'] ) ) ? urldecode( $_POST['connected_account_id'] ) : false;
		$info                 = urldecode( stripslashes( $_POST['info'] ) );

		$stripePayment = new PaymentIntent( $this->sk, $connected_account_id );

		$paymentIntent = $stripePayment->createPaymentIntent( $amount, $platform_fee, $info );

		$this->response( $paymentIntent );
	}


	private function response( $value ) {
		echo $value;
		die();
	}
}

// chiave segreta della piattaforma
$stripe_secret_key = get_field( 'stripe_secret_key', 'option' );

$stripeEndPoints = new EndPoints( $stripe_secret_key );
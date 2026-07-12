<?php

namespace Mapparte;

/**
 * Class Sponsorship
 *
 * @package Mapparte
 */
class Sponsorship {

	/**
	 * Sponsorship constructor.
	 */
	public function __construct() {
		add_action( 'wp', [ $this, 'activate_sponsorship' ] );
		add_action( 'mapparte_update_expired_sponsorship', [ $this, 'update_expired_sponsorship' ] );
		add_action( 'init', [ $this, 'schedule' ] );
	}

	/**
	 * Get the plan
	 */
	public static function get_plan( $plan_id ) {
		$plan = [];

		$startdate = date( 'Y-m-d H:i:s' );
		switch ( $plan_id ) {
			case 'silver':
				$plan['name']       = 'silver';
				$plan['amount']     = 15.00;
				$plan['desc']       = __( 'Un mese in cima ai risultati in homepage', 'mapparte' );
				$plan['desc_email'] = __( 'il tuo spazio avrà maggiore visibilità per un mese!', 'mapparte' );
				$plan['startdate']  = date( 'Y-m-d H:i:s', strtotime( $startdate ) );
				$plan['enddate']    = date( 'Y-m-d H:i:s', strtotime( '+1 month', strtotime( $startdate ) ) );
				break;
			case 'gold':
				$plan['name']       = 'gold';
				$plan['amount']     = 150.00;
				$plan['desc']       = __( 'Un anno in cima ai risultati in homepage', 'mapparte' );
				$plan['desc_email'] = __( 'il tuo spazio avrà maggiore visibilità per un anno!', 'mapparte' );
				$plan['startdate']  = date( 'Y-m-d H:i:s', strtotime( $startdate ) );
				$plan['enddate']    = date( 'Y-m-d H:i:s', strtotime( '+1 year', strtotime( $startdate ) ) );
				break;
			default:
				break;
		}

		return $plan;
	}

	/**
	 * Save the sponsorship data if the stripe payments success
	 */
	public function activate_sponsorship() {
		if ( is_page( 'dettaglio-sponsorizzazione' ) && ( isset( $_REQUEST['paymentIntentId'] ) && $_REQUEST['paymentIntentId'] )
			&& ( isset( $_REQUEST['payment'] ) && 'success' === $_REQUEST['payment'] )
			&& ( isset( $_REQUEST['nonce'] ) && wp_verify_nonce( urldecode( $_REQUEST['nonce'] ), 'stripe-nonce-seed' ) ) ) {

			$plan                    = $this->get_plan( $_REQUEST['plan'] );
			$plan['paymentIntentId'] = $_REQUEST['paymentIntentId'];

			update_post_meta( $_REQUEST['space_id'], 'sponsored_type', $plan['name'] );
			update_post_meta( $_REQUEST['space_id'], 'sponsored_expired', $plan['enddate'] );
			add_post_meta( $_REQUEST['space_id'], 'sponsorship_plan', $plan );

			$to_email = get_the_author_meta( 'email', get_current_user_id() );
			$subject  = sprintf( __( 'Mapparte - Il tuo piano %s è attivo!', 'mapparte' ), strtoupper( $plan['name'] ) );

			$message = sprintf(
				__(
					'Congratulazioni! Hai scelto di attivare il piano <b>%s</b> per <b>%s</b>!<br>
			A partire dal %s %s il tuo spazio avrà maggiore visibilità per un anno!<br>
			Trovi tutti i dettagli nella tua area riservata.',
					'mapparte'
				),
				esc_html( strtoupper( $plan['name'] ) ),
				esc_html( get_the_title( $_REQUEST['space_id'] ) ),
				esc_html( \Mapparte\Frontend_Utils::format_date( $plan['startdate'] ) ),
				esc_html( $plan['desc_email'] ),
			);

			$footer = __( 'Grazie!', 'mapparte' ) . '<br/>' . __( 'Il team Mapparte!', 'mapparte' );

			$args_notification = [
				'h1'                 => false,
				'body'               => $message,
				'call_to_action'     => false,
				'call_to_action_url' => false,
				'footer'             => $footer,
			];

			\Mapparte\Email_Notification::send_email( $to_email, $subject, $args_notification );

			wp_redirect( sprintf( '%s/attiva-sponsorizzazione/?space_id=%d&success=success', get_home_url(), (int) $_REQUEST['space_id'] ) );
			exit;
		}
	}

	/**
	 * Schedule a cron task to update expired sponsorship
	 */
	public function schedule() {
		if ( ! wp_next_scheduled( 'mapparte_update_expired_sponsorship' ) ) {
			wp_schedule_event( time(), '30mins', 'mapparte_update_expired_sponsorship' );
		}
	}

	/**
	 * Clear expired sponsorship meta
	 */
	public function update_expired_sponsorship() {

		$args = [
			'post_status'    => 'any',
			'post_type'      => 'space',
			'posts_per_page' => '-1',
			'meta_query'     => [
				'relation' => 'AND',
				[
					'key'     => 'sponsored_expired',
					'value'   => date( 'Y-m-d H:i:s' ),
					'compare' => '<=',
				],
				[
					'key'     => 'sponsored_type',
					'value'   => '',
					'compare' => '!=',
				],
			],
			'fields'         => 'ids',
		];

		$wp_query = new \WP_Query( $args );

		if ( count( $wp_query->posts ) > 0 ) {
			foreach ( $wp_query->posts as $id ) {
				update_post_meta( $id, 'sponsored_type', '' );
				update_post_meta( $id, 'sponsored_expired', '' );
			}
		}
	}

	/**
	 * IDs spazi sponsorizzati (per home in vetrina)
	 */
	public static function get_sponsored() {

		$args = [
			'post_type'      => 'space',
			'posts_per_page' => '-1',
			'meta_query'     => [
				'relation' => 'AND',
				[
					'key'     => 'sponsored_expired',
					'value'   => date( 'Y-m-d H:i:s' ),
					'compare' => '>=',
				],
				[
					'key'     => 'sponsored_type',
					'value'   => '',
					'compare' => '!=',
				],
			],
			'fields'         => 'ids',
		];

		$wp_query = new \WP_Query( $args );

		$results = $wp_query->posts;

		if ( count( $results ) < 5 ) {

			$args2 = [
				'post_type'      => 'space',
				'posts_per_page' => 5 - count( $wp_query->posts ),
				'post__not_in'   => $results,
				'fields'         => 'ids',
			];

			$wp_query_2 = new \WP_Query( $args2 );

			$results = array_merge( $results, $wp_query_2->posts );
		}

		return $results;
	}
}

new Sponsorship();

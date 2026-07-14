<?php

namespace Mapparte;

/**
 * Class Email_Notification
 *
 * @package Mapparte
 */
class Email_Notification {

	public function __construct() {
		add_filter( 'wp_mail_from', [ $this, 'mail_from' ] );
		add_filter( 'wp_mail_from_name', [ $this, 'mail_from_name' ] );
		add_action( 'init', [ $this, 'filter_xoo_emailer' ], 1 );
	}

	function mail_from( $old ) {
		return 'info@mapparte.com';
	}

	function mail_from_name( $old ) {
		return 'Mapparte';
	}

	function filter_xoo_emailer() {
		global $Xoo_Uv_Email;
		// Inject the custom Mapparte header footer
		add_action( 'xoo_uv_email_header', [ $this, 'get_email_header' ], 1 );
		add_action( 'xoo_uv_email_footer', [ $this, 'get_email_footer' ], 1 );

		// Don't send the default built-in new user registration email to the user
		remove_action( 'register_new_user', 'wp_send_new_user_notifications' );
		add_action( 'register_new_user', [ $this, 'send_new_user_notifications' ] );
	}

	function send_new_user_notifications( $user_id, $notify = 'admin' ) {
		wp_send_new_user_notifications( $user_id, $notify );
	}

	static public function get_email_header( $Xoo_Uv_Email ) {
		remove_action( 'xoo_uv_email_header', [ $Xoo_Uv_Email, 'email_header' ] );
		get_template_part( 'template-parts/email-notifications/header' );
	}

	static public function get_email_footer( $Xoo_Uv_Email ) {
		remove_action( 'xoo_uv_email_footer', [ $Xoo_Uv_Email, 'email_footer' ] );
		get_template_part( 'template-parts/email-notifications/footer' );
	}


	/**
	 * Format the messagge and send the email
	 *
	 * @param $to
	 * @param $subject
	 *
	 * @param $args This is an array of values displayed in the template
	 *
	 * $args = [
	 * 'h1'                 => 'Benvenuto!',
	 * 'body'               => 'Il tuo messagio',
	 * 'call_to_action'     => 'Clicca qui',
	 * 'call_to_action_url' => 'https://www.mapparte.com',
	 * 'footer'             => 'Grazie, Il team di Mapparte',
	 * ];
	 */
	static public function send_email( $to, $subject, $args ) {

		ob_start();
		get_template_part( 'template-parts/email-notifications/template', '', $args );
		$body = ob_get_contents();
		ob_end_clean();

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		wp_mail( $to, $subject, $body, $headers );
	}

	/**
	 * Display the booking details
	 *
	 * @param $args
	 */
	static public function format_booking_details( $args ) {
		//sintesi dei dati: spazio, giorno, orario, numero persone, destinazione d’uso, altre indicazioni + foto dell’host e suo rating)

		$guest      = get_userdata( $args['userId'] );
		$activity   = get_term_by( 'id', $args['planningTo'], 'activity' );
		$planningTo = ( isset( $activity->name ) ) ? $activity->name : "N.D.";

		$details = __('ID prenotazione', 'mapparte' ).': <b>' . esc_html( $args['bookingId'] ) . '</b><br>';
		$details .= ucfirst(__('utente', 'mapparte' )).': <b>' . esc_html( $guest->data->display_name ) . '</b><br>';

		$details .= __('Location', 'mapparte' ).': <b>' . esc_html( $args['spaceTitle'] ) . '</b><br>';
		$details .= __('Dal', 'mapparte' ).': <b>' . \Mapparte\Frontend_Utils::format_date_time( $args['fromDateTime'] ) . '</b><br>';
		$details .= __('Al', 'mapparte' ).': <b>' . \Mapparte\Frontend_Utils::format_date_time( $args['toDateTime'] ) . '</b><br>';
		$details .= __('Numero persone', 'mapparte' ).': <b>' . esc_html( $args['guests'] ) . '</b><br>';
		$details .= __('Destinazione d\'uso', 'mapparte' ).': <b>' . esc_html( $planningTo ) . '</b><br>';
		$details .= __('Prezzo stimato', 'mapparte' ).': <b>' . esc_html( $args['finalPrice'] ) . ' euro</b>';

		return $details;

	}
}

new Email_Notification();

<?php

namespace Mapparte;

/**
 * Class Email_Notification
 *
 * @package Mapparte
 */
class Email_Notification {
	const LOG_OPTION = 'mapparte_email_delivery_log';
	const RETRY_HOOK = 'mapparte_retry_failed_email';
	const MAX_RETRIES = 3;

	private $current_mail = [];
	private $retry_attempt = 0;

	public function __construct() {
		add_filter( 'wp_mail_from', [ $this, 'mail_from' ] );
		add_filter( 'wp_mail_from_name', [ $this, 'mail_from_name' ] );
		add_filter( 'wp_mail', [ $this, 'capture_mail' ], PHP_INT_MAX );
		add_action( 'init', [ $this, 'filter_xoo_emailer' ], 1 );
		add_action( 'wp_mail_failed', [ $this, 'handle_mail_failure' ] );
		add_action( 'wp_mail_succeeded', [ $this, 'handle_mail_success' ] );
		add_action( self::RETRY_HOOK, [ $this, 'retry_failed_email' ], 10, 2 );
		add_action( 'admin_menu', [ $this, 'register_email_log_page' ] );
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

		return wp_mail( $to, $subject, $body, $headers );
	}

	/**
	 * Keep the current payload available to the success/failure hooks.
	 */
	public function capture_mail( $mail ) {
		$this->current_mail = $mail;

		return $mail;
	}

	/**
	 * Record accepted messages. Acceptance by WordPress is not a delivery receipt.
	 */
	public function handle_mail_success( $mail_data ) {
		$mail = is_array( $mail_data ) ? $mail_data : $this->current_mail;
		$this->log_delivery( 'accepted', $mail, '' );
	}

	/**
	 * Record a transport failure and retry it through WP-Cron.
	 */
	public function handle_mail_failure( $error ) {
		$error_data = $error instanceof \WP_Error ? $error->get_error_data() : [];
		$mail       = is_array( $error_data ) ? array_merge( $this->current_mail, $error_data ) : $this->current_mail;
		$message    = $error instanceof \WP_Error ? $error->get_error_message() : __( 'Errore email sconosciuto', 'mapparte' );

		$this->log_delivery( 'failed', $mail, $message );

		if ( $this->retry_attempt >= self::MAX_RETRIES || empty( $mail['to'] ) || empty( $mail['subject'] ) ) {
			return;
		}

		$payload = [
			'to'          => $mail['to'],
			'subject'     => $mail['subject'],
			'message'     => isset( $mail['message'] ) ? $mail['message'] : '',
			'headers'     => isset( $mail['headers'] ) ? $mail['headers'] : [],
			'attachments' => isset( $mail['attachments'] ) ? $mail['attachments'] : [],
		];
		$next_attempt = $this->retry_attempt + 1;
		$event_args   = [ $payload, $next_attempt ];

		if ( ! wp_next_scheduled( self::RETRY_HOOK, $event_args ) ) {
			wp_schedule_single_event( time() + ( 5 * MINUTE_IN_SECONDS * $next_attempt ), self::RETRY_HOOK, $event_args );
		}
	}

	public function retry_failed_email( $mail, $attempt ) {
		$this->retry_attempt = min( self::MAX_RETRIES, max( 1, (int) $attempt ) );

		wp_mail(
			$mail['to'],
			$mail['subject'],
			$mail['message'],
			isset( $mail['headers'] ) ? $mail['headers'] : [],
			isset( $mail['attachments'] ) ? $mail['attachments'] : []
		);

		$this->retry_attempt = 0;
	}

	private function log_delivery( $status, $mail, $error ) {
		$logs = get_option( self::LOG_OPTION, [] );
		$logs = is_array( $logs ) ? $logs : [];
		$to   = isset( $mail['to'] ) ? $mail['to'] : '';

		if ( is_array( $to ) ) {
			$to = implode( ', ', array_map( 'sanitize_text_field', $to ) );
		}

		array_unshift( $logs, [
			'time'      => current_time( 'mysql' ),
			'status'    => sanitize_key( $status ),
			'to'        => sanitize_text_field( $to ),
			'subject'   => sanitize_text_field( isset( $mail['subject'] ) ? $mail['subject'] : '' ),
			'attempt'   => $this->retry_attempt,
			'transport' => defined( 'POST_SMTP_VER' ) ? 'post-smtp' : 'php-mail',
			'error'     => sanitize_textarea_field( $error ),
		] );

		update_option( self::LOG_OPTION, array_slice( $logs, 0, 200 ), false );
	}

	public function register_email_log_page() {
		add_management_page(
			__( 'Log email Mapparte', 'mapparte' ),
			__( 'Log email Mapparte', 'mapparte' ),
			'manage_options',
			'mapparte-email-log',
			[ $this, 'render_email_log_page' ]
		);
	}

	public function render_email_log_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$logs = get_option( self::LOG_OPTION, [] );
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Log email Mapparte', 'mapparte' ); ?></h1>
			<?php if ( ! defined( 'POST_SMTP_VER' ) ) : ?>
				<div class="notice notice-warning inline"><p><?php echo esc_html__( 'SMTP non attivo: lo stato “accettata” indica solo che il server locale ha preso in carico il messaggio, non che sia stato consegnato.', 'mapparte' ); ?></p></div>
			<?php endif; ?>
			<table class="widefat striped">
				<thead><tr><th><?php echo esc_html__( 'Data', 'mapparte' ); ?></th><th><?php echo esc_html__( 'Stato', 'mapparte' ); ?></th><th><?php echo esc_html__( 'Destinatario', 'mapparte' ); ?></th><th><?php echo esc_html__( 'Oggetto', 'mapparte' ); ?></th><th><?php echo esc_html__( 'Tentativo', 'mapparte' ); ?></th><th><?php echo esc_html__( 'Trasporto / errore', 'mapparte' ); ?></th></tr></thead>
				<tbody>
				<?php foreach ( (array) $logs as $log ) : ?>
					<tr>
						<td><?php echo esc_html( $log['time'] ); ?></td>
						<td><?php echo esc_html( $log['status'] ); ?></td>
						<td><?php echo esc_html( $log['to'] ); ?></td>
						<td><?php echo esc_html( $log['subject'] ); ?></td>
						<td><?php echo esc_html( $log['attempt'] ); ?></td>
						<td><?php echo esc_html( $log['transport'] . ( $log['error'] ? ': ' . $log['error'] : '' ) ); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
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

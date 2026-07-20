<?php

namespace Mapparte;

/**
 * Class Bookings
 *
 * @package Mapparte
 */
class Bookings {
	/**
	 * Bookings constructor.
	 */
	public function __construct() {
		add_action( 'wp', [ $this, 'update_booking' ] );
		add_filter( 'cron_schedules', [ $this, 'cron_schedules' ] );
		add_action( 'mapparte_update_expired_bookings', [ $this, 'update_expired_bookings' ] );
		add_action( 'mapparte_update_completed_bookings', [ $this, 'update_completed_bookings' ] );
		add_action( 'init', [ $this, 'schedule' ] );
	}

	/**
	 * Handle the booking update
	 */
	public static function update_booking() {

		global $post;

		if ( ! is_singular( 'booking' ) ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			return;
		}

		$booking_details = get_post_meta( $post->ID, '_booking_details', true );
		if ( ! is_array( $booking_details ) || empty( $booking_details['spaceId'] ) || empty( $booking_details['userId'] ) ) {
			return;
		}

		if ( get_current_user_id() !== (int) $post->post_author && get_current_user_id() !== (int) get_post_field( 'post_author', $booking_details['spaceId'] ) ) {
			return;
		}

		$details_msg            = \Mapparte\Email_Notification::format_booking_details( $booking_details );
		$space_title            = $booking_details['spaceTitle'];
		$to_email               = get_the_author_meta( 'email', $booking_details['userId'] );
		$user_nicename          = get_the_author_meta( 'nicename', $booking_details['userId'] );

		$host_id = (int) get_post_field( 'post_author', $booking_details['spaceId'] );

		$notifiche_prenotazione = get_the_author_meta( 'notifiche_prenotazione', $booking_details['userId'] );
		$one_signal             = get_the_author_meta( '_one_signal_tokens', $booking_details['userId'] );
		$one_signal_tokens = json_decode( $one_signal, true );
		$one_signal_ids = ( is_array( $one_signal_tokens ) ) ? array_keys( $one_signal_tokens ) : false;

		if ( isset( $_POST['status'] ) ) {
			if ( ! isset( $_POST['mapparte_booking_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mapparte_booking_nonce'] ) ), 'mapparte_update_booking_' . $post->ID ) ) {
				return;
			}

			$status = sanitize_key( wp_unslash( $_POST['status'] ) );
			$request = new \WP_REST_Request( 'PUT', sprintf( '/mapparte/v1/bookings/%d', $post->ID ) );
			$request->set_query_params( array( 'status' => $status ) );
			$rest_response = rest_do_request( $request );
			$server        = rest_get_server();
			$response      = $server->response_to_data( $rest_response, false );
			if ( isset( $_POST['message'] ) && $_POST['message'] ) {
				$thread    = \Mapparte\Messages::get_comment_thread_by_post_id( $post->ID );
				$thread_id = isset( $thread[0] ) ? $thread[0]->comment_ID : 0;
				\Mapparte\Messages::send_message( sanitize_text_field( $_POST['message'] ), sanitize_text_field( $thread_id ), sanitize_text_field( $post->ID ) );
			}

			if ( $response['success'] ) {

				$user_msg = ( isset( $_POST['message'] ) && $_POST['message'] ) ? sprintf( __("<b>Messaggio:</b><br>%s<br><br>", 'mapparte' ), sanitize_text_field( $_POST['message'] ) ) : false;

				if ( 'accettata' === $response['data']['post_status'] ) {

					$subject = __("Mapparte - Conferma prenotazione", 'mapparte' );

					$message = sprintf( __("<b>%s</b> ha accettato la tua richiesta.<br>La prenotazione è confermata: concorda direttamente con l'host le modalità di pagamento.<br><br>%s%s", 'mapparte' ),
						esc_html( $space_title ),
						$user_msg,
						$details_msg
					);

					$call_to_action = __("Vedi la prenotazione", 'mapparte' );

					$call_to_action_url = sprintf( "%s/?p=%d&post_type=booking",
						esc_url( get_home_url() ),
						$booking_details['bookingId']
					);

					$footer = __("Il team Mapparte!", 'mapparte' );

					$args_notification = [
						'h1'                 => false,
						'body'               => $message,
						'call_to_action'     => $call_to_action,
						'call_to_action_url' => $call_to_action_url,
						'footer'             => $footer,
					];

					\Mapparte\Email_Notification::send_email( $to_email, $subject, $args_notification );

					$message_notification = sprintf( __("%s ha accettato la tua richiesta. La prenotazione è confermata: concorda direttamente con l'host le modalità di pagamento.", 'mapparte' ),
						esc_html( $space_title )
					);
					if ( $notifiche_prenotazione && $one_signal_ids ) {
						\Mapparte\Push_Notification::sendMessage( $subject, [
							"popup_message" => $message_notification,
							"popup_link"    => $call_to_action_url
						], $one_signal_ids );
					}
				} elseif ( 'cancellata' === $response['data']['post_status'] ) {

					if ( get_current_user_id() === (int) get_post_field( 'post_author', $booking_details['spaceId'] ) ) {
						// Mail per l'utente quando l'host rifiuta.
						$subject = __("Mapparte - Prenotazione non confermata ", 'mapparte' );

						$message = sprintf( __("Ci dispiace! <b>%s</b> non ha accettato la tua prenotazione. :(
<br>Prosegui nella ricerca, siamo sicuri che ci sono tanti altri spazi adatti alle tue esigenze! 
<br><br>%s%s", 'mapparte' ),
							esc_html( $space_title ),
							$user_msg,
							$details_msg
						);

						$call_to_action = __("Cerca su Mapparte", 'mapparte' );

						$call_to_action_url = sprintf( "%s/%s",
							esc_url( get_home_url() ),
							'spaces/'
						);

						$message_notification = sprintf( __("Ci dispiace! %s non ha accettato la tua prenotazione. :( Prosegui nella ricerca, siamo sicuri che ci sono tanti altri spazi adatti alle tue esigenze!", 'mapparte' ),
							esc_html( $space_title )
						);
					} else {
						// Mail per l'host quando l'utente annulla.
						$to_email = get_the_author_meta( 'email', $host_id );
						$subject = __("Mapparte - Prenotazione annullata", 'mapparte' );

						$message = sprintf( __("<b>%s</b> ha annullato la prenotazione per <b>%s</b>.<br><br>%s%s", 'mapparte' ),
							esc_html( $user_nicename ),
							esc_html( $space_title ),
							$user_msg,
							$details_msg
						);

						$call_to_action = __("Vedi la prenotazione", 'mapparte' );

						$call_to_action_url = sprintf( "%s/?p=%d&post_type=booking",
							esc_url( get_home_url() ),
							$booking_details['bookingId']
						);

						$message_notification = false;
					}

					$footer = __("Il team Mapparte!", 'mapparte' );

					$args_notification = [
						'h1'                 => false,
						'body'               => $message,
						'call_to_action'     => $call_to_action,
						'call_to_action_url' => $call_to_action_url,
						'footer'             => $footer,
					];

					\Mapparte\Email_Notification::send_email( $to_email, $subject, $args_notification );

					if ( $message_notification && $notifiche_prenotazione && $one_signal_ids ) {
						\Mapparte\Push_Notification::sendMessage( $subject, [
							"popup_message" => $message_notification,
							"popup_link"    => $call_to_action_url
						], $one_signal_ids );
					}

				}
			}

			wp_safe_redirect( get_the_permalink() );
			exit;
		} else if ( isset ( $_REQUEST['acf'] ) ) {

			$guest_id = (int) $booking_details['userId'];
			unset( $_REQUEST['acf']['_validate_email'] );

			if ( $guest_id === get_current_user_id() ) { // Guest
				$tot_ratings = get_post_meta( $booking_details['spaceId'], '_tot_ratings', true );
			} else if ( $host_id === get_current_user_id() ) {  // Host
				$tot_ratings = get_user_meta( $post->post_author, '_tot_ratings', true );
			}

			foreach ( $_REQUEST['acf'] as $key => $value ) {
				$value      = sanitize_text_field( $value );
				$field_name = get_field_object( $key )['name'];
				update_field( $key, $value, $post->ID );

				if ( $value ) {
					array_push( $tot_ratings[ $field_name ], $value );
					$tot_ratings[ $field_name ] = array_filter( $tot_ratings[ $field_name ] );
					if ( count( $tot_ratings[ $field_name ] ) ) {
						$new_value = floor( ( array_sum( $tot_ratings[ $field_name ] ) / count( $tot_ratings[ $field_name ] ) ) * 2 ) / 2;
					}
				}

				if ( $guest_id === get_current_user_id() ) { // Guest
					update_post_meta( $booking_details['spaceId'], $field_name, $new_value );
				} else if ( $host_id === get_current_user_id() ) { // Host
					update_user_meta( $post->post_author, $field_name, $new_value );
				}
			}

			if ( $guest_id === get_current_user_id() ) { // Guest
				add_post_meta( $post->ID, '_rating_guest', 1, true );
				update_post_meta( $booking_details['spaceId'], '_tot_ratings', $tot_ratings );
			} else if ( $host_id === get_current_user_id() ) { // Host
				add_post_meta( $post->ID, '_rating_host', 1, true );
				update_user_meta( $post->post_author, '_tot_ratings', $tot_ratings );
			}
		}

	}

	// Add 30 mins interval for cronJobs
	public function cron_schedules( $schedules ) {
		if ( ! isset( $schedules["30mins"] ) ) {
			$schedules["30mins"] = array(
				'interval' => 30 * 60,
				'display'  => __( 'Once every 30 minutes' )
			);
		}

		return $schedules;
	}

	// Schedule a cron task to update status for expired bookings
	public function schedule() {

		// Schedule every 30 minutes
		if ( ! wp_next_scheduled( 'mapparte_update_expired_bookings' ) ) {
			wp_schedule_event( time(), '30mins', 'mapparte_update_expired_bookings' );
		}

		// Schedule every 30 minutes
		if ( ! wp_next_scheduled( 'mapparte_update_completed_bookings' ) ) {
			wp_schedule_event( time(), '30mins', 'mapparte_update_completed_bookings' );
		}

	}

	// Handle the update status for expired bookings
	public function update_expired_bookings() {

		global $wpdb;

		$date = date( 'Y-m-d H:i:s', strtotime( '-2 days' ) );

		$query = "select ID,post_status FROM " . $wpdb->prefix . "posts WHERE 1=1";
		$query .= " AND ( post_modified < '$date' )";
		$query .= " AND post_type = 'booking' AND post_status = 'nuova-richiesta' ORDER BY post_date DESC LIMIT 20";

		$results = $wpdb->get_results( $query );

		if ( count( $results ) === 0 ) {
			return;
		}

		foreach ( $results as $result ) {

			$time     = current_time( 'mysql', $gmt = 0 );
			$time_gmt = current_time( 'mysql', $gmt = 1 );

			$args    = [
				'ID'                => $result->ID,
				'post_status'       => 'cancellata',
				'post_modified'     => $time,
				'post_modified_gmt' => $time_gmt
			];
			$post_id = wp_update_post( $args, true );

			if ( ! is_wp_error( $post_id ) ) {
				$booking_details = get_post_meta( $result->ID, '_booking_details', true );
				$details_msg     = \Mapparte\Email_Notification::format_booking_details( $booking_details );
				$space_title     = $booking_details['spaceTitle'];
				$to_email        = get_the_author_meta( 'email', $booking_details['userId'] );

				$notifiche_prenotazione = get_the_author_meta( 'notifiche_prenotazione', $booking_details['userId'] );
				$one_signal             = get_the_author_meta( '_one_signal_tokens', $booking_details['userId'] );
				$one_signal_tokens = json_decode( $one_signal, true );
				$one_signal_ids = ( is_array( $one_signal_tokens ) ) ? array_keys( $one_signal_tokens ) : false;

				if ( "nuova-richiesta" === $result->post_status ) {
					// Mail per l'utente
					$subject = __("Mapparte - Prenotazione non confermata ", 'mapparte' );

					$message = sprintf( __("Ci dispiace! <b>%s</b> non ha accettato la tua prenotazione. :(
<br>Prosegui nella ricerca, siamo sicuri che ci sono tanti altri spazi adatti alle tue esigenze!
<br><br>%s", 'mapparte' ),
						esc_html( $space_title ),
						$details_msg
					);

					$call_to_action = __("Cerca su Mapparte", 'mapparte' );

					$call_to_action_url = sprintf( "%s/%s",
						esc_url( get_home_url() ),
						'spaces/'
					);

					$footer = __("Il team Mapparte!", 'mapparte' );

					$args_notification = [
						'h1'                 => false,
						'body'               => $message,
						'call_to_action'     => $call_to_action,
						'call_to_action_url' => $call_to_action_url,
						'footer'             => $footer,
					];

					$message_notification = sprintf( "Ci dispiace! %s non ha accettato la tua prenotazione. :( Prosegui nella ricerca, siamo sicuri che ci sono tanti altri spazi adatti alle tue esigenze!",
						esc_html( $space_title )
					);

					if ( $notifiche_prenotazione && $one_signal_ids ) {
						\Mapparte\Push_Notification::sendMessage( $subject, [
							"popup_message" => $message_notification,
							"popup_link"    => $call_to_action_url
						], $one_signal_ids );
					}

				}

				\Mapparte\Email_Notification::send_email( $to_email, $subject, $args_notification );

			}
		}
	}

	// Handle the update status for completed bookings
	public function update_completed_bookings() {

		global $wpdb;

		$date = current_time( 'mysql' );

		$query = "select p.ID,p.post_status FROM " . $wpdb->prefix . "posts as p ";
		$query .= "INNER JOIN " . $wpdb->prefix . "postmeta as m ON ( p.ID = m.post_id ) WHERE 1=1 ";
		$query .= $wpdb->prepare( "AND ( ( m.meta_key = 'toDateTime' AND m.meta_value < %s ) AND p.post_type = 'booking' AND p.post_status = 'accettata' ) GROUP BY p.ID ORDER BY p.post_date DESC LIMIT 20", $date );

		$results = $wpdb->get_results( $query );

		if ( count( $results ) === 0 ) {
			return;
		}

		foreach ( $results as $result ) {

			$booking_details = get_post_meta( $result->ID, '_booking_details', true );
			$details_msg     = \Mapparte\Email_Notification::format_booking_details( $booking_details );
			$to_email        = get_the_author_meta( 'email', $booking_details['userId'] );
			$subject         = __( "Mapparte - Richiesta di feedback", 'mapparte' );
			$message         = sprintf( __( "Grazie per aver utilizzato Mapparte!<br>
			Come è stata la tua esperienza?<br>
			La tua opinione è importante, per noi e per la community.<br>
			Clicca qui per lasciare una recensione!<br><br>%s", 'mapparte' ), $details_msg );
			$call_to_action     = __( "Lascia la tua recensione", 'mapparte' );
			$call_to_action_url = sprintf( "%s/?p=%d&post_type=booking",
				esc_url( get_home_url() ),
				$result->ID
			);
			$footer = __( "Il team Mapparte!", 'mapparte' );
			$args_notification = [
				'h1'                 => false,
				'body'               => $message,
				'call_to_action'     => $call_to_action,
				'call_to_action_url' => $call_to_action_url,
				'footer'             => $footer,
			];

			$guest_email_sent = (bool) get_post_meta( $result->ID, '_feedback_email_guest_sent', true );
			$host_email_sent  = (bool) get_post_meta( $result->ID, '_feedback_email_host_sent', true );

			if ( ! $guest_email_sent ) {
				$guest_email_sent = \Mapparte\Email_Notification::send_email( $to_email, $subject, $args_notification );

				if ( $guest_email_sent ) {
					update_post_meta( $result->ID, '_feedback_email_guest_sent', current_time( 'mysql' ) );

					$notifiche_prenotazione = get_the_author_meta( 'notifiche_prenotazione', $booking_details['userId'] );
					$one_signal             = get_the_author_meta( '_one_signal_tokens', $booking_details['userId'] );
					$one_signal_tokens      = json_decode( $one_signal, true );
					$one_signal_ids         = ( is_array( $one_signal_tokens ) ) ? array_keys( $one_signal_tokens ) : false;

					$message_notification = __( "Grazie per aver utilizzato Mapparte! Come è stata la tua esperienza? La tua opinione è importante, per noi e per la community. Clicca qui per lasciare una recensione!", 'mapparte' );

					if ( $notifiche_prenotazione && $one_signal_ids ) {
						\Mapparte\Push_Notification::sendMessage( $subject, [
							"popup_message" => $message_notification,
							"popup_link"    => $call_to_action_url
						], $one_signal_ids );
					}
				}
			}

			if ( ! $host_email_sent ) {
				$host_id         = get_post_field( 'post_author', $booking_details['spaceId'] );
				$host_email      = get_the_author_meta( 'email', $host_id );
				$host_email_sent = \Mapparte\Email_Notification::send_email( $host_email, $subject, $args_notification );

				if ( $host_email_sent ) {
					update_post_meta( $result->ID, '_feedback_email_host_sent', current_time( 'mysql' ) );
				}
			}

			if ( $guest_email_sent && $host_email_sent ) {
				wp_update_post( [
					'ID'          => $result->ID,
					'post_status' => 'feedback',
				] );
			}
		}
	}
}

new Bookings();

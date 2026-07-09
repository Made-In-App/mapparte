<?php

namespace Mapparte;

/**
 * Class Book
 *
 * @package Mapparte
 */
class Book extends Rest_Api {

	public $mapparte_bookings_table_name, $platform_discount, $platform_discount_min_hours;

	/** @var \wpdb */
	public $wpdb;

	/**
	 * Book constructor.
	 */
	public function __construct() {
		global $wpdb;
		$this->mapparte_bookings_table_name = $wpdb->prefix . 'mapparte_bookings';
		$this->wpdb                         = &$wpdb;
		// PHP 8+: abs() richiede int|float; ACF/WPGlobus possono restituire stringhe.
		$ore_consecutive   = get_field( 'ore_consecutive', 'option' );
		$sconto_applicato  = get_field( 'sconto_applicato', 'option' );
		$this->platform_discount_min_hours = ( null !== $ore_consecutive && '' !== $ore_consecutive ) ? abs( (float) $ore_consecutive ) : 12;
		$this->platform_discount          = ( null !== $sconto_applicato && '' !== $sconto_applicato ) ? abs( (float) $sconto_applicato ) : 10;

		add_action( 'rest_api_init', [ $this, 'get_quote_api' ] );
		add_action( 'rest_api_init', [ $this, 'add_booking_api' ] );
		add_action( 'rest_api_init', [ $this, 'edit_booking_api' ] );

	}

	/**
	 * Add a REST route to book a space
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/quote/{spaceId}
	 *
	 * @method GET
	 *
	 * @apiParam {string} [fromDateTime] YYYY-MM-DD hh:mm:ss
	 * @apiParam {string} [toDateTime] YYYY-MM-DD hh:mm:ss
	 * @apiParam {int} [voucherCode] The voucher code
	 */
	public function get_quote_api() {
		register_rest_route( self::NAMESPACE, '/quote/(?P<spaceId>[\d]+)', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => [ $this, 'book_space' ],
			'permission_callback' => [ $this, 'permission_callback_logged_in' ],
			'args'                => array(
				'fromDateTime' => array(
					'description'       => "Start Date - YYYY-MM-DD hh:mm:ss",
					'type'              => 'string',
					'default'           => '',
					'validate_callback' => array( $this, 'is_valid_date_time' ),
				),
				'toDateTime'   => array(
					'description'       => "Start Date - YYYY-MM-DD hh:mm:ss",
					'type'              => 'string',
					'default'           => '',
					'validate_callback' => array( $this, 'is_valid_date_time' ),
				),
				'voucherCode'  => array(
					'description' => "Voucher code",
					'type'        => 'string',
					'default'     => '',
				),
			),
		) );
	}

	/**
	 * Add a REST route to book a space
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/book/{spaceId}
	 *
	 * @method POST
	 *
	 * @apiParam {int} [planningTo] The activity
	 * @apiParam {string} [fromDateTime] YYYY-MM-DD hh:mm:ss
	 * @apiParam {string} [toDateTime] YYYY-MM-DD hh:mm:ss
	 * @apiParam {int} [voucherCode] The voucher code
	 * @apiParam {int} [guests] The number of guest
	 * @apiParam {string} [message] The user message
	 */

	public function add_booking_api() {
		register_rest_route( self::NAMESPACE, '/book/(?P<spaceId>[\d]+)', array(
			'methods'             => \WP_REST_Server::CREATABLE,
			'callback'            => [ $this, 'book_space' ],
			'permission_callback' => [ $this, 'permission_callback_logged_in' ],
			'args'                => array(
				'planningTo'   => array(
					'description'       => "What are you planning?",
					'type'              => 'integer',
					'default'           => 0,
					'sanitize_callback' => 'absint',
				),
				'fromDateTime' => array(
					'description'       => "Start Date - YYYY-MM-DD hh:mm:ss",
					'type'              => 'string',
					'default'           => '',
					'validate_callback' => array( $this, 'is_valid_date_time' ),
				),
				'toDateTime'   => array(
					'description'       => "Start Date - YYYY-MM-DD hh:mm:ss",
					'type'              => 'string',
					'default'           => '',
					'validate_callback' => array( $this, 'is_valid_date_time' ),
				),
				'guests'       => array(
					'description'       =>"Guest number",
					'type'              => 'integer',
					'default'           => 0,
					'sanitize_callback' => 'absint',
				),
				'voucherCode'  => array(
					'description' => "Voucher code",
					'type'        => 'string',
					'default'     => '',
				),
				'message'      => array(
					'description' => "Message for the host",
					'type'        => 'string',
					'default'     => '',
				),
			),
		) );
	}

	/**
	 * Add a REST route to update the status of booking
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/book/{bookingId}
	 *
	 * @method PUT
	 *
	 * @apiParam {string} [status]
	 */

	public function edit_booking_api() {
		register_rest_route( self::NAMESPACE, '/bookings/(?P<bookingId>[\d]+)', array(
			'methods'             => 'PUT',
			'callback'            => [ $this, 'edit_book_space' ],
			'permission_callback' => [ $this, 'permission_callback' ],
			'args'                => array(
				'status' => array(
					'description' => "The booking status",
					'type'        => 'string',
					'default'     => 'nuova-richiesta',
					'enum'        => array(
						'nuova-richiesta',
						'accettata',
						'pagata',
						'feedback',
						'cancellata'
					),
				),
			),
		) );
	}

	/**
	 * Get daily slots
	 *
	 * @param $availability
	 * @param $week_day
	 *
	 * @return array
	 */
	public function get_daily_slots( $daily_slots = [], $availability, $date_range ) {

		foreach ( $date_range as $key => $value ) {
			$week_day = strtolower( $value->format( 'D' ) );

			$opening_hours_key = sprintf( '%s_opening_hours', $week_day );
			if ( ! isset( $daily_slots[ $week_day ] ) ) {
				$day_availability = isset( $availability[ $opening_hours_key ] ) ? $availability[ $opening_hours_key ] : null;
				$daily_slots[ $week_day ] = Utils::get_slots_by_day( $day_availability, $week_day );
			}
		}

		return $daily_slots;
	}

	/**
	 * Return how many seconds from 00:00 by slot
	 *
	 * @param $slot
	 *
	 * @return float|int
	 */
	public function return_seconds_slot_time( $slot ) {
		list( $hours, $minutes ) = explode( ':', $slot, 2 );
		$seconds_slot_time = $minutes * 60 + $hours * 3600;

		return $seconds_slot_time;
	}

	/**
	 * Return Booking data
	 *
	 * @param $fromDate
	 * @param $toDate
	 * @param $days
	 * @param $date_range
	 * @param $weekly_slots
	 * @param $data
	 *
	 * @return array
	 */
	public function return_booking_data( $fromDate, $toDate, $days, $date_range, $weekly_slots, $data ) {

		$day                             = 1;
		$booking_data                    = [];
		$booking_data['tot_price']       = 0;
		$booking_data['tot_count_slots'] = 0;

		$availability = ( isset( $data['availability'] ) && is_array( $data['availability'] ) )
			? $data['availability']
			: [];
		$already_discounted   = false;
		$hourly_price         = (float) ( $data['price_hour'] ?? 0 );
		$hourly_weekend_price = (float) ( $data['price_hour_weekend'] ?? 0 );
		$day_discount         = abs( (float) ( $data['discount_perc_day'] ?? 0 ) );
		$weekend_discount     = abs( (float) ( $data['discount_perc_weekend'] ?? 0 ) );

		$slot_price         = $hourly_price / ( 60 / self::MINUTES_IN_SLOT );
		$slot_weekend_price = $hourly_weekend_price / ( 60 / self::MINUTES_IN_SLOT );

		$start_date         = $fromDate->format( 'Y-m-d' );
		$start_time         = $fromDate->format( 'H:i' );
		$seconds_start_time = $this->return_seconds_slot_time( $start_time );

		$end_date         = $toDate->format( 'Y-m-d' );
		$end_time         = $toDate->format( 'H:i' );
		$seconds_end_time = $this->return_seconds_slot_time( $end_time );

		$weekend_booked_slots = 0;
		$saturday             = null;
		$sat_slots            = Utils::get_slots_by_day( $availability['sat_opening_hours'] ?? null, 'sat' );
		$sun_slots            = Utils::get_slots_by_day( $availability['sun_opening_hours'] ?? null, 'sun' );
		$weekend_tot_slots    = count( $sat_slots ) + count( $sun_slots );

		// For each date check get available slots
		foreach ( $date_range as $key => $value ) {

			$booking_data['dates'][ $value->format( 'Y-m-d' ) ]['slots'] = [];

			$count_slots = 0;
			$week_day    = strtolower( $value->format( 'D' ) );

			// Reset weekend counts on Monday
			if ( $week_day != 'sat' && $week_day != 'sun' ) {
				$weekend_booked_slots = 0;
			}

			$day_slots = isset( $weekly_slots[ $week_day ] ) && is_array( $weekly_slots[ $week_day ] )
				? $weekly_slots[ $week_day ]
				: [];

			foreach ( $day_slots as $slot ) {

				$seconds_slot_time = $this->return_seconds_slot_time( $slot );

				if ( $start_date === $end_date ) { // if it's just one single day

					if ( $seconds_slot_time >= $seconds_end_time ) {
						break;
					}

					if ( $seconds_slot_time >= $seconds_start_time ) {
						$count_slots ++;
						array_push( $booking_data['dates'][ $value->format( 'Y-m-d' ) ]['slots'], sprintf( '%s %s', $value->format( 'Y-m-d' ), $slot ) );
					}

				} else if ( $day === 1 ) { // if it's the first day count how many slots from the start time

					if ( $seconds_slot_time >= $seconds_start_time ) {
						$count_slots ++;
						array_push( $booking_data['dates'][ $value->format( 'Y-m-d' ) ]['slots'], sprintf( '%s %s', $value->format( 'Y-m-d' ), $slot ) );
					}

				} else if ( $day === $days ) { // if it's the last day of the booking count how many slots until the end time

					if ( $seconds_end_time >= $seconds_slot_time ) {
						$count_slots ++;
						array_push( $booking_data['dates'][ $value->format( 'Y-m-d' ) ]['slots'], sprintf( '%s %s', $value->format( 'Y-m-d' ), $slot ) );

					}
				} else { // count the whole day
					array_push( $booking_data['dates'][ $value->format( 'Y-m-d' ) ]['slots'], sprintf( '%s %s', $value->format( 'Y-m-d' ), $slot ) );
					$count_slots = count( $day_slots );
				}
			}

			if ( $week_day === 'sat' || $week_day === 'sun' ) {
				$weekend_booked_slots = $weekend_booked_slots + $count_slots;

				if ( $week_day === 'sat' ) {
					$saturday = $value->format( 'Y-m-d' );
				}
			}

			// Calculate Prices
			// Solo se esistono slot weekend configurati: altrimenti 0===0 su giorni feriali e $saturday non è definito → fatal error / 500.
			if ( $weekend_tot_slots > 0 && $weekend_booked_slots === $weekend_tot_slots ) { // If it's the whole weekend assign the price to sunday

				// Re-calculate saturday price if saturday and sunday are fully booked
				if ( $week_day !== 'sat' && $saturday ) {
					$weekend_prices                                       = Utils::calculate_daily_prices( $data['availability'], 'sat', $hourly_weekend_price, $weekend_discount );
					$booking_data['dates'][ $saturday ]['price']          = Utils::format_price( $weekend_prices[0] );
					$booking_data['dates'][ $saturday ]['original_price'] = Utils::format_price( $weekend_prices[1] );
					$booking_data['dates'][ $saturday ]['discount']       = $weekend_prices[2];
				}

				$weekend_prices                                                       = Utils::calculate_daily_prices( $data['availability'], $week_day, $hourly_weekend_price, $weekend_discount );
				$booking_data['dates'][ $value->format( 'Y-m-d' ) ]['price']          = Utils::format_price( $weekend_prices[0] );
				$booking_data['dates'][ $value->format( 'Y-m-d' ) ]['original_price'] = Utils::format_price( $weekend_prices[1] );
				$booking_data['dates'][ $value->format( 'Y-m-d' ) ]['discount']       = $weekend_prices[2];
				$already_discounted                                                   = true;
			} else if ( count( $day_slots ) === $count_slots && $week_day !== 'sun' && $week_day !== 'sat' ) {
				$daily_prices                                                         = Utils::calculate_daily_prices( $data['availability'], $week_day, $hourly_price, $day_discount );
				$booking_data['dates'][ $value->format( 'Y-m-d' ) ]['price']          = ( $count_slots ) ? Utils::format_price( $daily_prices[0] ) : Utils::format_price( 0 );
				$booking_data['dates'][ $value->format( 'Y-m-d' ) ]['original_price'] = ( $count_slots ) ? Utils::format_price( $daily_prices[1] ) : Utils::format_price( 0 );
				$booking_data['dates'][ $value->format( 'Y-m-d' ) ]['discount']       = $daily_prices[2];
				$already_discounted                                                   = true;
			} else {
				switch ( $week_day ) {
					case 'sun':
					case 'sat':
						$booking_data['dates'][ $value->format( 'Y-m-d' ) ]['price'] = ( $count_slots ) ? Utils::format_price( $slot_weekend_price * $count_slots ) : Utils::format_price( 0 );
						break;
					default:
						$booking_data['dates'][ $value->format( 'Y-m-d' ) ]['price'] = ( $count_slots ) ? Utils::format_price( $slot_price * $count_slots ) : Utils::format_price( 0 );
				}

			}

			$booking_data['tot_count_slots'] = $booking_data['tot_count_slots'] + $count_slots;
			$day ++;
		}

		$hours_in_slot                = self::MINUTES_IN_SLOT / 60;
		$booking_data['hours_booked'] = $booking_data['tot_count_slots'] * $hours_in_slot;

		foreach ( $booking_data['dates'] as $key => $date ) {

			// Calculate the platform discount in some conditions
			// Adesione allo sconto accettata, minimo ore prenotate, nessun altro sconto gia' applicato
			if ( $data['discount'] && $booking_data['hours_booked'] >= $this->platform_discount_min_hours && ! $already_discounted ) {
				$booking_data['dates'][ $key ]['discount']       = $this->platform_discount;
				$booking_data['dates'][ $key ]['original_price'] = Utils::format_price( $date['price'] );
				// Calculate the 12 hours discount
				$booking_data['dates'][ $key ]['price'] = Utils::format_price( $date['price'] - ( $date['price'] * ( $this->platform_discount / 100 ) ) );
			}

			$booking_data['tot_price'] = Utils::format_price( $booking_data['tot_price'] + $booking_data['dates'][ $key ]['price'] );
		}

		return $booking_data;
	}

	/**
	 * Maybe store booking Data
	 *
	 * @param $data
	 * @param $params
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function maybe_store_book_data( $data, $params, $method ) {
		// In REST API il global $user_ID può essere 0: usare sempre l’ID utente corrente.
		$current_user_id = get_current_user_id();
		$bookingId       = null;

		$defaults = [
			'spaceId'      => false,
			'fromDateTime' => false,
			'toDateTime'   => false,
			'planningTo'   => 0,
			'guests'       => 0,
			'voucherCode'  => 0,
			'message'      => false,
		];

		$params = array_merge( $defaults, $params );

		// Normalizza data da form-urlencoded (es. "2026-05-22+00:30:00" → spazio) per DateTime/validateDate.
		if ( ! empty( $params['fromDateTime'] ) ) {
			$params['fromDateTime'] = str_replace( [ 'T', '+' ], ' ', $params['fromDateTime'] );
			$params['fromDateTime'] = preg_replace( '/\s+/', ' ', trim( $params['fromDateTime'] ) );
		}
		if ( ! empty( $params['toDateTime'] ) ) {
			$params['toDateTime'] = str_replace( [ 'T', '+' ], ' ', $params['toDateTime'] );
			$params['toDateTime'] = preg_replace( '/\s+/', ' ', trim( $params['toDateTime'] ) );
		}

		$time     = current_time( 'mysql', $gmt = 0 );
		$time_gmt = current_time( 'mysql', $gmt = 1 );

		$availability = isset( $data['availability'] ) ? $data['availability'] : [];

		if ( ! Utils::validateDate( $params['fromDateTime'] ) || ! Utils::validateDate( $params['toDateTime'] ) ) {
			return Utils::rest_api_response( false, __('Date non valide','mapparte') );
		}

		$fromDate = new \DateTime( $params['fromDateTime'] );
		$toDate   = new \DateTime( $params['toDateTime'] );

		// Remove 1 minute to cover the 00:00 edge case
		$toDate->modify( sprintf( '+ %d minutes', self::MINUTES_IN_SLOT - 1 ) );

		$toDateMod = new \DateTime( $toDate->format( 'Y-m-d' ) );

		// This is necessary to include the last day through the DatePeriod method https://www.php.net/manual/en/class.dateperiod.php
		$toDatePeriod = ( $fromDate->format( 'Y-m-d' ) != $toDate->format( 'Y-m-d' ) ) ? $toDateMod->modify( '+1 day' ) : $toDate;

		$date_range = new \DatePeriod(
			$fromDate,
			new \DateInterval( 'P1D' ),
			$toDatePeriod
		);
		$days       = iterator_count( $date_range );
		if ( $days < 1 ) {
			return Utils::rest_api_response( false, __('Date non valide','mapparte') );
		}

		$weekly_slots = $this->get_daily_slots( [], $availability, $date_range );

		// Calculate Price and Return Booking details
		$booking_data = $this->return_booking_data( $fromDate, $toDate, $days, $date_range, $weekly_slots, $data );

		$min_hours = \WPGlobus_Filters::filter__text( $data['min_hours'] );

		if ( $booking_data['hours_booked'] < (float) $min_hours ) {
			return Utils::rest_api_response( false, __("Numero minimo ore prenotabili",'mapparte')  . " : ". $min_hours);
		}

		if ( 'POST' === $method && $current_user_id ) {
			$booking_args = [
				'post_title'        => sanitize_text_field( $data['title']['rendered'] . '-' . date( 'Y-m-d H:i:s' ) ),
				'post_status'       => 'nuova-richiesta',
				'post_type'         => 'booking',
				'post_author'       => $current_user_id,
				'post_date'         => $time,
				'post_date_gmt'     => $time_gmt,
				'post_modified'     => $time,
				'post_modified_gmt' => $time_gmt
			];
			$bookingId    = wp_insert_post( $booking_args );

			if ( is_wp_error( $bookingId ) ) {
				return Utils::rest_api_response( false, $bookingId->get_error_message() );
			}
			if ( ! $bookingId ) {
				return Utils::rest_api_response( false, __( 'Errore nella creazione della prenotazione.', 'mapparte' ) );
			}
		}

		$voucher = Voucher::get_voucher( $params['voucherCode'], $booking_data['tot_price'] );

		$final_price = $booking_data['tot_price'] - $voucher['voucherValue'];

		$checkout_time = new \DateTime( $params['toDateTime'] );
		$checkout_time->add( new \DateInterval( 'PT' . self::MINUTES_IN_SLOT . 'M' ) );
		$stamp = $checkout_time->format( 'Y-m-d H:i:s' );

		$args = [
			'spaceId'      => (int) $params['spaceId'],
			'spaceTitle'   => sanitize_text_field( $data['title']['rendered'] ),
			'userId'       => (int) $current_user_id,
			'fromDateTime' => $params['fromDateTime'],
			'toDateTime'   => $stamp,
			'price'        => Utils::format_price( $booking_data['tot_price'] ),
			'voucherCode'  => sanitize_text_field( $voucher['voucherCode'] ),
			'voucherValue' => Utils::format_price( $voucher['voucherValue'] ),
			'voucherUsed'  => sanitize_text_field( $voucher['voucherUsed'] ),
			'finalPrice'   => Utils::format_price( $final_price ),
			'slotsDetails' => json_encode( $booking_data ),
			'guests'       => sanitize_text_field( $params['guests'] ),
			'planningTo'   => sanitize_text_field( $params['planningTo'] ),
			'message'      => sanitize_text_field( $params['message'] ),
			'date'         => $time,
			'date_gmt'     => $time_gmt,
			'status'       => 0
		];

		if ( 'POST' === $method ) {

			if ( isset( $bookingId ) && $bookingId ) {
				$args['bookingId'] = (int) $bookingId;
			}

			// wpdb::insert applica i format nell’ordine dei valori di $args (bookingId è in coda). Costruiamo i placeholder per chiave.
			$insert_formats = [];
			foreach ( array_keys( $args ) as $col ) {
				switch ( $col ) {
					case 'spaceId':
					case 'userId':
					case 'voucherUsed':
					case 'guests':
					case 'planningTo':
					case 'status':
					case 'bookingId':
						$insert_formats[] = '%d';
						break;
					case 'price':
					case 'voucherValue':
					case 'finalPrice':
						$insert_formats[] = '%f';
						break;
					default:
						$insert_formats[] = '%s';
						break;
				}
			}

			$result = $this->wpdb->insert( $this->mapparte_bookings_table_name, $args, $insert_formats );
		}

		if ( 'POST' === $method && isset( $result ) && isset( $bookingId ) && $result ) {

			if ( ! empty( $voucher['voucherID'] ) ) {
				update_post_meta( (int) $voucher['voucherID'], 'used', 1 );
			}
			update_post_meta( $bookingId, 'fromDateTime', $params['fromDateTime'] );
			update_post_meta( $bookingId, 'toDateTime', $params['toDateTime'] );
			update_post_meta( $bookingId, '_booking_details', $args );
			update_post_meta( $bookingId, '_host_id', $data['author'] );

			if ( $args['message'] ) {
				\Mapparte\Messages::send_message( sanitize_text_field( $args['message'] ), 0, sanitize_text_field( $args['bookingId'] ) );
			}

			$details_msg = \Mapparte\Email_Notification::format_booking_details( $args );

			// Mail per l'host

			$to = get_post_field( 'post_author', $args['spaceId'] );

			$space_title = $args['spaceTitle'];
			$to_email    = get_the_author_meta( 'email', (int) $to );
			if ( ! is_email( $to_email ) && $to ) {
				$host_user = get_user_by( 'id', (int) $to );
				$to_email  = ( $host_user && is_email( $host_user->user_email ) ) ? $host_user->user_email : '';
			}
			$from_name   = get_the_author_meta( 'nicename', get_current_user_id() );

			$subject = "Mapparte - Richiesta di prenotazione";


			$user_msg = ( $args['message'] ) ? sprintf( "Messaggio:<br><b>%s</b><br><br>", $args['message'] ) : false;

			$message = sprintf( "<b>%s</b> hai ricevuto una nuova prenotazione da <b>%s</b>!<br><br>%s%s",
				esc_html( $space_title ),
				esc_html( $from_name ),
				$user_msg,
				$details_msg
			);

			$call_to_action = "Accetta o Rifiuta entro le 48 ore";

			$call_to_action_url = sprintf( "%s/?p=%d&post_type=booking",
				esc_url( get_home_url() ),
				$bookingId
			);

			$footer = "Il team Mapparte!";

			$args_notification = [
				'h1'                 => false,
				'body'               => $message,
				'call_to_action'     => $call_to_action,
				'call_to_action_url' => $call_to_action_url,
				'footer'             => $footer,
			];

			if ( ! is_email( $to_email ) ) {
				// Fallback: notifica admin se l’host non ha email valida sul profilo.
				$to_email = get_option( 'admin_email' );
			}
			if ( is_email( $to_email ) ) {
				\Mapparte\Email_Notification::send_email( $to_email, $subject, $args_notification );
			}

			// Mail per l'utente
			$guest_email = get_the_author_meta( 'email', $current_user_id );
			if ( ! is_email( $guest_email ) && $current_user_id ) {
				$guest_user = get_user_by( 'id', $current_user_id );
				$guest_email = ( $guest_user && is_email( $guest_user->user_email ) ) ? $guest_user->user_email : '';
			}

			$subject = "Mapparte - Dettagli della prenotazione";

			$message = sprintf( "Ecco i dettagli della tua prenotazione. Riceverai conferma entro 48 ore.<br><br>%s", $details_msg );

			$args_notification = [
				'h1'                 => false,
				'body'               => $message,
				'call_to_action'     => false,
				'call_to_action_url' => false,
				'footer'             => $footer,
			];

			if ( is_email( $guest_email ) ) {
				\Mapparte\Email_Notification::send_email( $guest_email, $subject, $args_notification );
			}

			return Utils::rest_api_response( true, $this->wpdb->insert_id, $args );
		} else if ( 'GET' === $method ) {
			return Utils::rest_api_response( true, $params['spaceId'], $args );
		} else {
			if ( $bookingId ) {
				wp_delete_post( $bookingId );
			}

			return Utils::rest_api_response( false, 'Insert DB error', $args );
		}
	}

	/**
	 * Callback for the REST API
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 */
	public function book_space( $request ) {

		$params = $request->get_params();

		if ( ! $params['spaceId'] ) {
			$response = Utils::rest_api_response( false, __('ID spazio non valido','mapparte') );
		} else {

			$data = Utils::return_space_data( $params['spaceId'] );

			if ( isset( $data['data'] ) && 404 === $data['data']['status'] ) {
				$response = Utils::rest_api_response( false, __('ID spazio non valido','mapparte') );
			} else {
				if ( 'POST' === $request->get_method() && ! empty( $data['hide_prices'] ) ) {
					$response = Utils::rest_api_response( false, __( 'Per questo spazio è disponibile solo la richiesta di contatto.', 'mapparte' ) );
					return rest_ensure_response( [
						'success'  => $response[0],
						'code'     => $response[0] ? 'success' : 'fail',
						'message'  => $response[1],
						'data'     => $response[2],
					] );
				}
				$response = $this->maybe_store_book_data( $data, $params, $request->get_method() );
			}
		}

		// return any necessary data in the response here
		return rest_ensure_response( [
			'success'  => $response[0],
			'code'     => $response[0] ? 'success' : 'fail',
			'message'  => $response[1],
			'data'     => $response[2],
		] );
	}

	/**
	 * Callback for the REST API
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 */
	public function edit_book_space( $request ) {

		$params = $request->get_params();

		$time     = current_time( 'mysql', $gmt = 0 );
		$time_gmt = current_time( 'mysql', $gmt = 1 );

		$data = Utils::get_booking( $params['bookingId'] );

		if ( ! $data ) {
			$response = Utils::rest_api_response( false, __('ID booking non valido','mapparte') );
		} else {

			$args    = [
				'ID'                => $params['bookingId'],
				'post_status'       => $params['status'],
				'post_modified'     => $time,
				'post_modified_gmt' => $time_gmt
			];
			$post_id = wp_update_post( $args, true );

			if ( is_wp_error( $post_id ) ) {
				$errors = $post_id->get_error_messages();
				foreach ( $errors as $error ) {
					echo $error;
					$response = Utils::rest_api_response( false, __('Prenotazione non effettuata','mapparte'), $args );
				}
			} else {
				$response = Utils::rest_api_response( true, __('Prenotazione effettuata con successo','mapparte'), $args );
			}
		}

		// return any necessary data in the response here
		return rest_ensure_response( [
			'success'  => $response[0],
			'code'     => $response[0] ? 'success' : 'fail',
			'message'  => $response[1],
			'data'     => $response[2],
		] );
	}
}

new Book();

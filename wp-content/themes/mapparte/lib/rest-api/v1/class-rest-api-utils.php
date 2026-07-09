<?php

namespace Mapparte;

/**
 * Class Utils
 *
 * @package Mapparte
 */
class Utils extends Rest_Api {

	/**
	 * Validate date
	 *
	 * @param $date
	 * @param string $format
	 *
	 * @return bool
	 */
	public static function validateDate( $date, $format = 'Y-m-d H:i:s' ) {
		$d = \DateTime::createFromFormat( $format, $date );
		return $d && $d->format( $format ) === $date;
	}

	public static function get_slots_by_day( $availability, $week_day ) {
		$slots = [];
		if ( $availability ) {
			foreach ( $availability as $time_range ) {
				$open_key   = sprintf( "%s_open", $week_day );
				$close_key  = sprintf( "%s_close", $week_day );
				$start_time = $time_range[ $open_key ];
				$close_time = $time_range[ $close_key ];
				$time_slots = self::prepare_time_slots( $start_time, $close_time );
				$slots      = array_merge( $slots, $time_slots );
			}
		}
		usort( $slots, [ '\Mapparte\Utils', 'date_sort' ] );

		return $slots;

	}

	/**
	 * Sort dates
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return false|int
	 */
	public static function date_sort( $a, $b ) {
		return strtotime( $a ) - strtotime( $b );
	}

	/**
	 * Prepare the time slots by duration
	 *
	 * @param $starttime
	 * @param $endtime
	 * @param $duration
	 *
	 * @return array
	 */
	public static function prepare_time_slots( $starttime, $endtime ) {

		$time_slots = array();
		$start_time = strtotime( $starttime ); //change to strtotime
		$end_time   = strtotime( $endtime ); //change to strtotime

		$add_mins = self::MINUTES_IN_SLOT * 60;

		while ( $start_time <= $end_time ) // loop between time
		{
			$time_slots[] = date( "H:i", $start_time );
			$start_time   += $add_mins; // to check endtime
		}

		return $time_slots;
	}

	/**
	 * Return REST API response
	 *
	 * @param $success
	 * @param $message
	 * @param array $args
	 *
	 * @return array
	 */
	public static function rest_api_response( $success, $message, $args = [] ) {
		return [ $success, $message, $args ];
	}

	/**
	 * Return space data
	 *
	 * @param $spaceId
	 *
	 * @return array
	 */
	public static function return_space_data( $spaceId ) {
		$request       = new \WP_REST_Request( 'GET', sprintf( '/wp/v2/space/%d', $spaceId ) );
		$rest_response = rest_do_request( $request );
		$server        = rest_get_server();

		return $server->response_to_data( $rest_response, false );
	}

	/**
	 * get booking data
	 *
	 * @param $bookingId
	 *
	 * @return array
	 */
	public static function get_booking( $bookingId ) {

		$args = array(
			'p'           => $bookingId,
			'post_type'   => 'booking',
			'post_status' => 'any',
		);

		$booking = new \WP_Query( $args );

		if ( $booking->have_posts() ) {
			$data          = $booking->posts[0];
			$data->details = get_post_meta( $data->ID, '_booking_details', true );
			return $data;
		}

		return false;
	}


	/**
	 * Return weekend prices and discount
	 *
	 * @param $availability
	 * @param $hourly_weekend_price
	 * @param $weekend_discount
	 *
	 * @return array
	 */
	public static function calculate_weekend_prices( $availability, $hourly_weekend_price, $weekend_discount ) {
		$sat_slots = Utils::get_slots_by_day( $availability['sat_opening_hours'], 'sat' );
		$sun_slots = Utils::get_slots_by_day( $availability['sun_opening_hours'], 'sun' );
		$slots_tot = sizeof( $sat_slots ) + sizeof( $sun_slots );

		$slot_weekend_price           = $hourly_weekend_price / ( 60 / self::MINUTES_IN_SLOT );
		$full_weekend_price           = $slot_weekend_price * $slots_tot;
		$tot_weekend_discounted_price = $full_weekend_price - ( $full_weekend_price * ( $weekend_discount / 100 ) );

		return [ $tot_weekend_discounted_price, $full_weekend_price, $weekend_discount ];
	}

	/**
	 * Return daily prices and discount
	 *
	 * @param $availability
	 * @param $week_day
	 * @param $hourly_price
	 * @param $day_discount
	 *
	 * @return array
	 */
	public static function calculate_daily_prices( $availability, $week_day, $hourly_price, $day_discount ) {
		$opening_hours_key = sprintf( "%s_opening_hours", $week_day );
		$slots             = Utils::get_slots_by_day( $availability[ $opening_hours_key ], $week_day );
		$slot_day_price    = $hourly_price / ( 60 / self::MINUTES_IN_SLOT );
		$full_day_price    = $slot_day_price * sizeof( $slots );
		$discounted_price  = $full_day_price - ( $full_day_price * ( $day_discount / 100 ) );

		return [ $discounted_price, $full_day_price, $day_discount ];
	}

	/**
	 * Format Prices
	 *
	 * @param $price
	 *
	 * @return string
	 */
	public static function format_price( $price ) {
		return sprintf( "%.2f", $price );
	}
}

<?php

namespace Mapparte;

/**
 * Class Book
 *
 * @package Mapparte
 */
class Availability extends Rest_Api {

	public $mapparte_bookings_table_name;

	public function __construct() {
		global $wpdb;
		$this->mapparte_bookings_table_name = $wpdb->prefix . 'mapparte_bookings';
		$this->wpdb                         = &$wpdb;
		add_action( 'rest_api_init', [ $this, 'availability_api' ] );
	}

	/**
	 * Add a REST route to check the availability for a space
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/availability/41?date=2020-03-01
	 * @method GET
	 *
	 * @apiParam {string} [date] YYYY-MM-DD
	 */
	public function availability_api() {
		register_rest_route( self::NAMESPACE, '/availability/(?P<spaceId>[\d]+)', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => [ $this, 'space_availability' ],
			'permission_callback' => [ $this, 'permission_callback_logged_in' ],
			'args'                => array(
				'date' => array(
					'description'       => __( "Date - YYYY-MM-DD", 'mapparte' ),
					'type'              => 'string',
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field',
					'validate_callback' => array( $this, 'is_valid_date' ),
				),
			),
		) );
	}

	public function space_availability( $request ) {

		// if you sent any parameters along with the request, you can access them like so:
		$params   = $request->get_params();
		$response = [ false, __('Spazio non disponibile','mapparte') ];
		$slots    = [];

		if ( \DateTime::createFromFormat( 'Y-m-d', $params['date'] ) === false ) {
			$response = Utils::rest_api_response( false, __('Data non valida','mapparte') );;
		}

		if ( ! $params['spaceId'] ) {
			$response = Utils::rest_api_response( false, __('ID spazio non valido','mapparte') );
		} else {

			$data = Utils::return_space_data( $params['spaceId'] );

			if ( isset( $data['data']['status'] ) && 404 === $data['data']['status'] ) {
				$response = Utils::rest_api_response( false, __('ID spazio non valido','mapparte') );
			} else if ( isset( $data['availability'] ) ) {
				$availability      = $data['availability'];
				$date              = new \DateTime( $params['date'] );
				$week_day          = strtolower( $date->format( 'D' ) );
				$opening_hours_key = sprintf( "%s_opening_hours", $week_day );
				$slots             = isset( $availability[ $opening_hours_key ] ) ? Utils::get_slots_by_day( $availability[ $opening_hours_key ], $week_day ) : [];

				$response = Utils::rest_api_response( true, __('Slot disponibili','mapparte') );
			}
		}

		return rest_ensure_response( [
			'success' => $response[0],
			'code'    => $response[0] ? 'success' : 'fail',
			'message' => $response[1],
			'data'    => array_values( $slots )
		] );
	}
}

new Availability();

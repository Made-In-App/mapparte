<?php

namespace Mapparte;

/**
 * Class Search_Fields
 *
 * @package Mapparte
 */
class Search_Fields extends Rest_Api {

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'get_search_fields' ] );
	}

	/**
	 * Define the REST route to get search dropdowns and checkboxes
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/search-fields
	 *
	 * @method GET
	 */
	public function get_search_fields() {
		register_rest_route( self::NAMESPACE, '/search-fields/', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_items' ),
			'permission_callback' => '__return_true',
		) );
	}


	/**
	 * Callback for the mapparte/v1/favorites endpoint
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 */
	public function get_items( $request ) {

		$search_fields         = [];

		// Activities
		$params['per_page'] = 100;
		$request = new \WP_REST_Request( 'GET', sprintf( '/wp/v2/activity' ) );
		$request->set_query_params( $params );
		$rest_response = rest_do_request( $request );
		if ( $rest_response->get_status() === 200 ) {
			$activities = $rest_response->get_data();
			$search_fields['s_activity'] = [];
			foreach ( $activities as $activity ) {
				$search_fields['s_activity'][ $activity['id'] ] = $activity['name'];
			}
		}

		// Fare
		$search_fields['fare'] = [
			1 => __( "oraria infrasettimanale", 'mapparte' ),
			2 => __( "oraria weekend", 'mapparte' ),
			3 => __( "tariffa giornaliera", 'mapparte' ),
			4 => __( "tariffa weekend", 'mapparte' ),
		];

		// Price range
		$search_fields['priceRange'] = [
			0 => __( "min", 'mapparte' ),
			1000 => __( "max", 'mapparte' ),
		];

		$search_fields['accessibility'] = [
			1 => __( "Sì", 'mapparte' ),
			0 => __( "No", 'mapparte' ),
		];

		// Typology
		$params['per_page'] = 100;
		$request = new \WP_REST_Request( 'GET', sprintf( '/wp/v2/typology' ) );
		$request->set_query_params( $params );
		$rest_response = rest_do_request( $request );
		if ( $rest_response->get_status() === 200 ) {
			$activities = $rest_response->get_data();
			$search_fields['s_typology'] = [];
			foreach ( $activities as $activity ) {
				$search_fields['s_typology'][ $activity['id'] ] = $activity['name'];
			}
		}

		$acf_fields = [ "space_access", "space_external", "features", "floor_type", "rooms" ];

		foreach ( $acf_fields as $acf_field ) {
			$search_fields[ $acf_field ] = [];
			$field = acf_get_field( $acf_field );
			if ( isset( $field['choices'] ) && is_array( $field['choices'] ) ) {
				foreach ( $field['choices'] as $choice ) {
					$search_fields[ $acf_field ][ $choice ] =  __( ucfirst( $choice ), 'mapparte' );
				}
			};
		}

		// return any necessary data in the response
		return rest_ensure_response( [
			'code'    => 'success',
			'message' => 'Search Fields',
			'data'    => $search_fields,
		] );
	}
}

new Search_Fields();

<?php

namespace Mapparte;

/**
 * Class Space
 *
 * @package Mapparte
 */
class REST_Taxonomies extends Rest_Api {

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'get_activities' ] );
		add_action( 'rest_api_init', [ $this, 'get_typologies' ] );
	}

	/**
	 * Add a wrapper REST route to get all the activities
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/activity/
	 *
	 * @method GET
	 */
	public function get_activities() {
		$post_controller = new \WP_REST_Terms_Controller( 'activity' );
		register_rest_route( self::NAMESPACE, '/activity/', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_activities_items' ),
			'permission_callback' => [ $this, 'permission_callback' ],
			'args'                => $post_controller->get_collection_params(),
		) );
	}

	/**
	 * Add a wrapper REST route to get all the typologies
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/typology/
	 *
	 * @method GET
	 */
	public function get_typologies() {
		$post_controller = new \WP_REST_Terms_Controller( 'typology' );
		register_rest_route( self::NAMESPACE, '/typology/', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_typologies_items' ),
			'permission_callback' => [ $this, 'permission_callback' ],
			'args'                => $post_controller->get_collection_params(),
		) );
	}

	/**
	 * Callback for the mapparte/v1/activity/ endpoint
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 */
	public function get_activities_items( $request ) {

		$params = $request->get_params();

		$request = new \WP_REST_Request( 'GET', sprintf( '/wp/v2/activity' ) );
		$request->set_query_params( $params );
		$rest_response = rest_do_request( $request );
		$server        = rest_get_server();

		if ( $rest_response->get_status() === 200 ) {
			return rest_ensure_response( [
				'code'    => $rest_response->get_status(),
				'message' => 'Results',
				'data'    => $rest_response->get_data()
			] );
		} else {
			return $server->response_to_data( $rest_response, true );
		};

	}

	/**
	 * Callback for the mapparte/v1/activity/ endpoint
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 */
	public function get_typologies_items( $request ) {

		$params = $request->get_params();

		$request = new \WP_REST_Request( 'GET', sprintf( '/wp/v2/typology' ) );
		$request->set_query_params( $params );
		$rest_response = rest_do_request( $request );
		$server        = rest_get_server();

		if ( $rest_response->get_status() === 200 ) {
			return rest_ensure_response( [
				'code'    => $rest_response->get_status(),
				'message' => 'Results',
				'data'    => $rest_response->get_data()
			] );
		} else {
			return $server->response_to_data( $rest_response, true );
		};

	}
}

new REST_Taxonomies();

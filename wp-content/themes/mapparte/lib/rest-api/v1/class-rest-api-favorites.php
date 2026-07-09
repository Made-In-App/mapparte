<?php

namespace Mapparte;

/**
 * Class Favorites
 *
 * @package Mapparte
 */
class Favorites extends Rest_Api {

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'get_favorites' ] );
		add_action( 'rest_api_init', [ $this, 'get_single_favorite' ] );
		add_action( 'rest_api_init', [ $this, 'add_remove_favorite' ] );
	}

	/**
	 * Define the REST route to get favorites for a specific user
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/favorites/
	 *
	 * @method GET
	 */
	public function get_favorites() {
		register_rest_route( self::NAMESPACE, '/favorites/', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_items' ),
			'permission_callback' => [ $this, 'permission_callback' ]
		) );
	}

	/**
	 * Define the REST route to get the favorite status for a space ID
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/favorites/{space_id}
	 *
	 * @method GET
	 */
	public function get_single_favorite() {
		register_rest_route( self::NAMESPACE, '/favorites/(?P<spaceId>[\d]+)', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => [ $this, 'get_item' ],
			'permission_callback' => [ $this, 'permission_callback' ]
		) );
	}

	/**
	 * Define the REST route to add or remove a space ID to favorites
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/favorites/{space_id}
	 *
	 * @method POST
	 */
	public function add_remove_favorite() {
		register_rest_route( self::NAMESPACE, '/favorites/(?P<spaceId>[\d]+)', array(
			'methods'             => \WP_REST_Server::CREATABLE,
			'callback'            => array( $this, 'add_remove_item' ),
			'permission_callback' => [ $this, 'permission_callback' ]
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
		$current_user = get_current_user_id();

		$favorites = get_user_favorites( $current_user );

		if ( sizeof( $favorites ) ) {
			$request = new \WP_REST_Request( 'GET', sprintf( '/wp/v2/space' ) );
			$request->set_query_params( [ 'include' => $favorites ] );
			$rest_response = rest_do_request( $request );
			$favorites = $rest_response->get_data();
		}

		return [
			'code' => 200,
			'message'  => 'Favorites',
			'data' => $favorites
		];
	}

	/**
	 * Callback for the mapparte/v1/favorites/{space_id} endpoint
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 */
	public function add_remove_item( $request ) {
		$params = $request->get_params();

		$user = new \Favorites\Entities\User\UserRepository;

		$response            = [];
		$response['spaceId'] = (int) $params['spaceId'];
		$is_favorite         = $user->isFavorite( $params['spaceId'], 1, null, 1 );

		$response['is_favorite'] = $is_favorite ? false : true;

		$favorite = new \Favorites\Entities\Favorite\Favorite;

		$favorite->update( $response['spaceId'], $response['is_favorite'], 1, 1 );

		$filled_heart               = get_theme_file_uri( 'assets/images/heart-fill.png' );
		$heart                      = get_theme_file_uri( 'assets/images/heart.png' );
		$response['favorite_image'] = $response['is_favorite'] ? $filled_heart : $heart;

		// return any necessary data in the response
		return rest_ensure_response( [
			'code'  => 'success',
			'message'  => 'Favorites',
			'data' => $response,
		] );

	}

	/**
	 * Callback for the mapparte/v1/favorites/{space_id} endpoint
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 */
	public function get_item( $request ) {
		$params = $request->get_params();

		$user = new \Favorites\Entities\User\UserRepository;

		$response                = [];
		$response['spaceId'] = (int) $params['spaceId'];
		$response['is_favorite'] = $user->isFavorite( $params['spaceId'], 1, null, 1 );

		$filled_heart               = get_theme_file_uri( 'assets/images/heart-fill.png' );
		$heart                      = get_theme_file_uri( 'assets/images/heart.png' );
		$response['favorite_image'] = $response['is_favorite'] ? $filled_heart : $heart;

		// return any necessary data in the response
		return rest_ensure_response( [
			'code'  => 'success',
			'message'  => 'Favorites',
			'data' => $response,
		] );

	}
}

new Favorites();

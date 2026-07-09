<?php

namespace Mapparte;

/**
 * Class Space
 *
 * @package Mapparte
 */
class Space extends Rest_Api {

	public function __construct() {
		add_filter( "rest_prepare_space", [ $this, 'show_custom_fields' ], 10, 3 );
		add_action( 'acf/save_post', [ $this, 'update_additional_meta' ], 15, 1 );
		add_action( 'rest_api_init', [ $this, 'get_spaces' ] );
		add_action( 'rest_api_init', [ $this, 'get_space' ] );
	}

	/**
	 * Add a wrapper REST route to get all published spaces
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/spaces/
	 *
	 * @method GET
	 */
	public function get_spaces() {
		$post_controller = new \WP_REST_Posts_Controller( 'space' );
		$collection_params = $post_controller->get_collection_params();

		$collection_params['city'] = array(
			'description'       => "Limita i risultati filtrando per il Nome della località.",
			'type'              => 'string',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field'
		);

		$collection_params['lat'] = array(
			'description'       => "Limita i risultati filtrando per Latitudine.",
			'type'              => 'string',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field'
		);

		$collection_params['lon'] = array(
			'description'       => "Limita i risultati filtrando per Longitudine.",
			'type'              => 'string',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field'
		);

		$collection_params['s_activity'] = array(
			'description'       => "Limita i risultati filtrando per Attività.",
			'type'              => 'integer',
			'default'           => 0,
			'sanitize_callback' => 'absint',
		);

		$collection_params['s_typology'] = array(
			'description'       => "Limita i risultati filtrando per una o più Tipologie. (?s_typology[]=32&s_typology[]=33)",
			'type'              => 'integer',
			'default'           => 0,
			'sanitize_callback' => 'absint',
		);

		$collection_params['fare'] = array(
			'description'       => "Limita i risultati seleziondo la Tipologia di tariffa. Funziona in combinazione con priceRange.",
			'type'              => 'integer',
			'default'           => 0,
			'sanitize_callback' => 'absint',
		);

		$collection_params['priceRange'] = array(
			'description'       => "Limita i risultati filtrando per Range di prezzo. (?priceRange=200;300)",
			'type'              => 'integer',
			'default'           => 0,
			'sanitize_callback' => 'absint',
		);

		$collection_params['space_mq'] = array(
			'description'       => "Limita i risultati filtrando per Metri quadri.",
			'type'              => 'integer',
			'default'           => 0,
			'sanitize_callback' => 'absint',
		);

		$collection_params['max_people'] = array(
			'description'       => "Limita i risultati filtrando per Capienza.",
			'type'              => 'integer',
			'default'           => 0,
			'sanitize_callback' => 'absint',
		);

		$collection_params['accessibility'] = array(
			'description'       => "Limita i risultati filtrando per Accessibilità per disabili.",
			'type'              => 'integer',
			'default'           => 0,
			'sanitize_callback' => 'absint',
		);

		$collection_params['space_access'] = array(
			'description'       => "Limita i risultati filtrando per uno o più valori di Accessibilità. (?space_access[]=ascensore&space_access[]=montacarichi)",
			'type'              => 'integer',
			'default'           => 0,
			'sanitize_callback' => 'sanitize_text_field'
		);

		$collection_params['space_external'] = array(
			'description'       => "Limita i risultati filtrando per uno o più valori di Spazio esterno. (?space_external[]=giardino&space_external[]=terrazza)",
			'type'              => 'integer',
			'default'           => 0,
			'sanitize_callback' => 'sanitize_text_field'
		);

		$collection_params['features'] = array(
			'description'       => "Limita i risultati filtrando per uno o più valori di Caratteristiche. (?features[]=docce&features[]=piscina)",
			'type'              => 'integer',
			'default'           => 0,
			'sanitize_callback' => 'sanitize_text_field'
		);

		$collection_params['floor_type'] = array(
			'description'       => "Limita i risultati filtrando per uno o più valori di Pavimento. (?floor_type[]=legno&floor_type[]=cotto)",
			'type'              => 'integer',
			'default'           => 0,
			'sanitize_callback' => 'sanitize_text_field'
		);

		$collection_params['rooms'] = array(
			'description'       => "Limita i risultati filtrando per uno o più valori di Numero sale. (?rooms[]=2&rooms[]=3)",
			'type'              => 'integer',
			'default'           => 0,
			'sanitize_callback' => 'sanitize_text_field'
		);

		register_rest_route( self::NAMESPACE, '/spaces/', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_items' ),
			'permission_callback' => '__return_true',
			'args'                => $collection_params,
		) );
	}

	/**
	 * Add a wrapper REST route to get a single by ID
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/spaces/{space_id}
	 *
	 * @method GET
	 */
	public function get_space() {
		$post_controller = new \WP_REST_Post_Types_Controller( 'space' );
		register_rest_route( self::NAMESPACE, '/spaces/(?P<spaceId>[\d]+)', array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_item' ),
			'permission_callback' => '__return_true',
			'args'                => $post_controller->get_collection_params(),
		) );
	}

	/**
	 * Callback for the mapparte/v1/spaces/ endpoint
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 */
	public function get_items( $request ) {

		$params = $request->get_params();

		$request = new \WP_REST_Request( 'GET', sprintf( '/wp/v2/space' ) );
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
	 * Callback for the mapparte/v1/spaces/{space_id} endpoint
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 */
	public function get_item( $request ) {

		$params = $request->get_params();

		$request = new \WP_REST_Request( 'GET', sprintf( '/wp/v2/space/%d', $params['spaceId'] ) );
		$request->set_query_params( $params );
		$rest_response = rest_do_request( $request );
		$server        = rest_get_server();

		if ( $rest_response->get_status() === 200 ) {
			return rest_ensure_response( [
				'code'    => $rest_response->get_status(),
				'message' => __('Spazio trovato','mapparte'),
				'data'    => $rest_response->get_data()
			] );
		} else {
			return $server->response_to_data( $rest_response, true );
		};
	}

	/**
	 * Show Custom Fields in the REST API response
	 *
	 * @param $data
	 * @param $post
	 * @param $request
	 *
	 * @return mixed
	 */
	function show_custom_fields( $data, $post, $request ) {
		$_data  = $data->data;

		$fields = get_fields( $post->ID );
		if ( is_array( $fields ) ) {
			foreach ( $fields as $key => $value ) {
				if ( $key ) {
					$val = get_field( $key, $post->ID );

					if ( is_array( $val ) ) {
						$empty = [ '' ];
						$val   = array_filter(
							$val,
							function ( $key ) use ( $empty ) {
								return ! in_array( $key, $empty, true );
							},
							ARRAY_FILTER_USE_KEY
						);
						foreach ( $val as $chiave => $valore ) {
							$val[ $chiave ] = ( false === $valore ) ? [] : $valore;
							if ( $chiave === 'address' && false === $valore ) {
								$val[ $chiave ] = json_encode( $val[ $chiave ] );
							}
						}

					}
					$_data[ $key ] = ( false === $val ) ? [] : $val;

					if ( $key === 'address' && !$val ) {
						$_data[ $key ] = (object) [];
					}
				}
			}
		}

		$_data['min_price_day']     = get_post_meta( $post->ID, 'min_price_day', true );
		$_data['average_price_day'] = get_post_meta( $post->ID, 'average_price_day', true );
		$_data['max_price_day']     = get_post_meta( $post->ID, 'max_price_day', true );
		$_data['price_weekend']     = get_post_meta( $post->ID, 'price_weekend', true );
		$_data['hide_prices']       = (bool) get_post_meta( $post->ID, 'hide_prices', true );
		$user                       = new \Favorites\Entities\User\UserRepository;
		$_data['is_favorite']       = $user->isFavorite( $post->ID, 1, null, 1 );

		// Hide some infos for non logged users
		if ( ! user_can( get_current_user_id(), 'edit_posts' ) ) {
			$_data['title']['rendered'] =  $_data['excerpt']['rendered'];
			$_data['address'] = (object) [];
			$_data['neighbourhood'] = '';
			$_data['photos'] = array_slice( $_data['photos'], 0, 3 );
		}

		$data->data = $_data;

		return $data;
	}

	/**
	 * Update the discounted prices for the website search
	 *
	 * @param $id
	 */
	public static function update_additional_meta( $id ) {

		if ( "space" !== get_post_type( $id ) ) {
			return;
		}

		$availability     = get_field( 'availability', $id );
		$weekend_discount = (float) abs( (float) get_post_meta( $id, 'discount_perc_weekend', true ) );

		if ( is_float( $weekend_discount ) ) {

			$hourly_weekend_price = (float) get_post_meta( $id, 'price_hour_weekend', true );
			$old_weekend_price    = (float) get_post_meta( $id, 'price_weekend', true );

			$tot_weekend_prices = Utils::calculate_weekend_prices( $availability, $hourly_weekend_price, $weekend_discount );

			if ( $old_weekend_price !== (float) $tot_weekend_prices[0] ) {
				update_post_meta( $id, 'price_weekend', $tot_weekend_prices[0] );
			}
		}

		$week_days    = [ 'mon', 'tue', 'wed', 'thu', 'fri' ];
		$day_discount = (float) abs( (float) get_post_meta( $id, 'discount_perc_day', true ) );

		if ( is_float( $day_discount ) ) {
			$daily_discounted_prices = [];
			$hourly_price            = (float) get_post_meta( $id, 'price_hour', true );

			foreach ( $week_days as $week_day ) {

				$tot_day_discounted_price = Utils::calculate_daily_prices( $availability, $week_day, $hourly_price, $day_discount );

				if ( $tot_day_discounted_price ) {
					$daily_discounted_prices[] = $tot_day_discounted_price[0];
				}
			}
			//Remove empty days
			$daily_discounted_prices = array_diff( $daily_discounted_prices, [ 0 ] );
			$average_daily_price     = ( $daily_discounted_prices ) ? array_sum( $daily_discounted_prices ) / sizeof( $daily_discounted_prices ) : 0;

			$min_price_day = ( sizeof( $daily_discounted_prices ) > 0 ) ? min( $daily_discounted_prices ) : 0;
			$max_price_day = ( sizeof( $daily_discounted_prices ) > 0 ) ? max( $daily_discounted_prices ) : 0;

			update_post_meta( $id, 'min_price_day', $min_price_day );
			update_post_meta( $id, 'average_price_day', $average_daily_price );
			update_post_meta( $id, 'max_price_day', $max_price_day );
		}
	}
}

new Space();

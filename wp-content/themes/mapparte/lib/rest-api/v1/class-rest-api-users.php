<?php

namespace Mapparte;

/**
 * Class Users
 *
 * @package Mapparte
 */
class Users extends Rest_Api {

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'adding_user_meta_rest' ], 10 );
		add_action( 'rest_api_init', [ $this, 'create_user' ], 11 );
		add_action( 'rest_api_init', [ $this, 'get_user' ], 11 );
		add_action( 'rest_api_init', [ $this, 'update_user' ], 11 );
		add_action( 'rest_api_init', [ $this, 'reset_password' ], 11 );
	}

	/**
	 * Add a wrapper REST route to create a new user
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/users/
	 *
	 * @method POST
	 */
	public function create_user() {
		$user_controller = new \WP_REST_Users_Controller();
		register_rest_route( self::NAMESPACE, '/users/', [
			'methods'             => \WP_REST_Server::CREATABLE,
			'callback'            => [ $this, 'create_item' ],
			'permission_callback' => [ $this, 'permission_callback' ],
			'args'                => $user_controller->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE )
		] );
	}


	/**
	 * Callback for the mapparte/v1/users/ endpoint
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 */
	public function create_item( $request ) {

		$params = $request->get_params();

		$request = new \WP_REST_Request( 'POST', sprintf( '/wp/v2/users' ) );
		$request->set_query_params( $params );
		$rest_response = rest_do_request( $request );
		$server        = rest_get_server();

		if ( $rest_response->get_status() === 201 ) {
			return rest_ensure_response( [
				'code'    => $rest_response->get_status(),
				'message' => __('Utente creato con successo','mapparte'),
				'data'    => $rest_response->get_data()
			] );
		} else {
			return $server->response_to_data( $rest_response, true );
		};
	}

	/**
	 * Add a wrapper REST route to get an existing user
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/users/{user_id}
	 *
	 * @method GET
	 */
	public function get_user() {
		$post_controller = new \WP_REST_Post_Types_Controller( 'space' );
		register_rest_route( self::NAMESPACE, '/users/(?P<userId>[\d]+)', [
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => [ $this, 'callback_get_user' ],
			'permission_callback' => [ $this, 'permission_callback' ],
			'args'                => $post_controller->get_collection_params(),
		] );
	}

	/**
	 * Callback for the GET mapparte/v1/users/{user_id} endpoint
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 */
	public function callback_get_user( $request ) {

		$params = $request->get_params();

		$request = new \WP_REST_Request( 'GET', sprintf( '/wp/v2/users/%d', $params['userId'] ) );
		$request->set_query_params( $params );
		$rest_response = rest_do_request( $request );
		$server        = rest_get_server();

		if ( $rest_response->get_status() === 200 ) {
			return rest_ensure_response( [
				'code'    => $rest_response->get_status(),
				'message' => __('Utente trovato','mapparte'),
				'data'    => $rest_response->get_data()
			] );
		} else {
			return $server->response_to_data( $rest_response, true );
		};
	}

	/**
	 * Add a wrapper REST route to update an existing user
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/users/{user_id}
	 *
	 * @method POST
	 */
	public function update_user() {
		$user_controller = new \WP_REST_Users_Controller();
		register_rest_route( self::NAMESPACE, '/users/(?P<userId>[\d]+)', [
			'methods'             => \WP_REST_Server::EDITABLE,
			'callback'            => [ $this, 'callbak_update_user' ],
			'permission_callback' => [ $this, 'permission_callback' ],
			'args'                => $user_controller->get_endpoint_args_for_item_schema( \WP_REST_Server::EDITABLE )
		] );
	}

	/**
	 * Callback for the POST mapparte/v1/users/{user_id} endpoint
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 */
	public function callbak_update_user( $request ) {

		$params = $request->get_params();

		$request = new \WP_REST_Request( 'POST', sprintf( '/wp/v2/users/%d', $params['userId'] ) );
		$request->set_query_params( $params );
		$rest_response = rest_do_request( $request );
		$server        = rest_get_server();

		if ( $rest_response->get_status() === 200 ) {
			return rest_ensure_response( [
				'code'    => $rest_response->get_status(),
				'message' => __('Utente aggiornato con successo','mapparte'),
				'data'    => $rest_response->get_data()
			] );
		} else {
			return $server->response_to_data( $rest_response, true );
		};

	}

	public function adding_user_meta_rest() {

			register_rest_field( 'user', 'xoo_aff_text_residenza', [
				'get_callback'    => [ $this, 'get_user_custom_meta' ],
				'update_callback' => [ $this, 'update_user_custom_meta' ],
				'schema'          => [
					'type'        => 'string',
					'description' => 'Residenza',
					'context'     => [ 'view', 'edit' ],
				],
			] );
			register_rest_field( 'user', 'xoo_aff_date_date', [
				'get_callback'    => [ $this, 'get_user_custom_meta' ],
				'update_callback' => [ $this, 'update_user_custom_meta' ],
				'schema'          => [
					'type'        => 'string',
					'description' => 'Date of birth (YYYY-MM-DD)',
					'context'     => [ 'view', 'edit' ],
				],
			] );
			register_rest_field( 'user', 'xoo_aff_select_list_attivita', [
				'get_callback'    => [ $this, 'get_user_custom_meta' ],
				'update_callback' => [ $this, 'update_user_custom_meta' ],
				'schema'          => [
					'type'        => 'string',
					'description' => 'Attività prevalentemente svolta',
					'context'     => [ 'view', 'edit' ],
				],
			] );
			register_rest_field( 'user', 'mc4wp-subscribe', [
				'get_callback'    => [ $this, 'get_user_custom_meta' ],
				'update_callback' => [ $this, 'update_user_custom_meta' ],
				'schema'          => [
					'type'        => 'string',
					'description' => 'Desidero ricevere email di aggiornamento (yes/0)',
					'context'     => [ 'view', 'edit' ],
				],
			] );
			register_rest_field( 'user', 'xoo_el_reg_terms', [
				'get_callback'    => [ $this, 'get_user_custom_meta' ],
				'update_callback' => [ $this, 'update_user_custom_meta' ],
				'schema'          => [
					'type'        => 'string',
					'description' => 'Accetta Termini d\'uso e privacy (0/1)',
					'context'     => [ 'view', 'edit' ],
				],
			] );
			register_rest_field( 'user', 'mostra_notifiche', [
				'get_callback'    => [ $this, 'get_user_custom_meta' ],
				'update_callback' => [ $this, 'update_user_custom_meta' ],
				'schema'          => [
					'type'        => 'string',
					'description' => 'Mostra notifiche (0/1)',
					'context'     => [ 'view', 'edit' ],
				],
			] );
			register_rest_field( 'user', 'notifiche_disponibilita', [
				'get_callback'    => [ $this, 'get_user_custom_meta' ],
				'update_callback' => [ $this, 'update_user_custom_meta' ],
				'schema'          => [
					'type'        => 'string',
					'description' => 'Aggiornamento disponibilità spazi (0/1)',
					'context'     => [ 'view', 'edit' ],
				],
			] );

			register_rest_field( 'user', 'notifiche_prenotazione', [
				'get_callback'    => [ $this, 'get_user_custom_meta' ],
				'update_callback' => [ $this, 'update_user_custom_meta' ],
				'schema'          => [
					'type'        => 'string',
					'description' => 'Ricevere notifiche della prenotazione (0/1)',
					'context'     => [ 'view', 'edit' ],
				],
			] );

			register_rest_field( 'user', 'notifiche_localita', [
				'get_callback'    => [ $this, 'get_user_custom_meta' ],
				'update_callback' => [ $this, 'update_user_custom_meta' ],
				'schema'          => [
					'type'        => 'string',
					'description' => 'Località preferita per le notifiche e.g. "Roma"',
					'context'     => [ 'view', 'edit' ],
				],
			] );

			register_rest_field( 'user', 'notifiche_attivita', [
				'get_callback'    => [ $this, 'get_user_custom_meta' ],
				'update_callback' => [ $this, 'update_user_custom_meta' ],
				'schema'          => [
					'type'        => 'string',
					'description' => 'IDs delle attività preferite per le notifiche e.g. 21,11,10',
					'context'     => [ 'view', 'edit' ],
				],
			] );

			register_rest_field( 'user', 'notifiche_prezzo', [
				'get_callback'    => [ $this, 'get_user_custom_meta' ],
				'update_callback' => [ $this, 'update_user_custom_meta' ],
				'schema'          => [
					'type'        => 'string',
					'description' => 'Range di prezzo per le notifiche e.g. 200;600',
					'context'     => [ 'view', 'edit' ],
				],
			] );

			register_rest_field( 'user', '_xoo-sl-social-login', [
				'get_callback'    => [ $this, 'get_user_custom_meta' ],
				'update_callback' => [ $this, 'update_user_custom_meta' ],
				'schema'          => [
					'type'        => 'string',
					'description' => 'Identifica utenti loggati tramite social (e.g. google, facebook o apple)',
					'context'     => [ 'view', 'edit' ],
				],
			] );

			register_rest_field( 'user', '_one_signal_tokens', [
				'get_callback'    => [ $this, 'get_one_signal' ],
				'update_callback' => [ $this, 'update_one_signal' ],
				'schema'          => [
					'type'        => 'string',
					'description' => 'One signal token and OS { "2222222": "android" }',
					'context'     => [ 'view', 'edit' ],
				],
			] );

	}

	/**
	/**
	 * Add a REST route to reset your password
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/reset-password/
	 *
	 * @method POST
	 */
	public function reset_password() {
		register_rest_route( self::NAMESPACE, '/reset-password/', [
			'methods'             => \WP_REST_Server::CREATABLE,
			'callback'            => [ $this, 'callback_reset_password' ],
			'permission_callback' => '__return_true',
			'args'                => array(
				'user_login' => array(
					'description' => __( "User login / User email", 'mapparte' ),
					'type'        => 'string',
					'default'     => '',
				),
			),
		] );
	}

	/**
	 * Callback for the POST mapparte/v1/reset-password/ endpoint
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 */
	public function callback_reset_password( $request ) {

		$params = $request->get_params();

		$response = retrieve_password( $params['user_login'] );

		if ( ! is_wp_error( $response ) ) {
			return rest_ensure_response( [
				'code'    => 200,
				'message' => __( "Controlla la tua email per il link da cui recuperare la password.", 'mapparte' ),
				'data'    => $response
			] );
		} else {
			return rest_ensure_response( $response );
		};

	}

	public function get_one_signal( $user, $field_name, $request ) {
		if ( ( isset( $user['id'] ) && get_current_user_id() === (int) $user['id'] ) || current_user_can( 'administrator' ) ) {
			return get_user_meta( $user['id'], $field_name, true );
		}
	}

	public function update_one_signal( $meta_value, $user, $field_name ) {
		if ( ( isset( $user->data->ID ) && get_current_user_id() === (int) $user->data->ID ) || current_user_can( 'administrator' ) ) {
			$one_signal_tokens = json_decode( get_user_meta( $user->data->ID, $field_name, true ), true );
			if ( ! is_array( $one_signal_tokens ) ) {
				$one_signal_tokens = [];
			}
			if ( $meta_value ) {
				$value = json_decode( $meta_value, true );
				foreach ( $value as $key => $val ) {
					$one_signal_tokens[ $key ] = $val;
				}
			}
			update_user_meta( $user->data->ID, $field_name, json_encode( $one_signal_tokens ) );
		}
	}


	public function get_user_custom_meta( $user, $field_name, $request ) {
		if ( ( isset( $user['id'] ) && get_current_user_id() === (int) $user['id'] ) || current_user_can( 'administrator' ) ) {
			return get_user_meta( $user['id'], $field_name, true );
		}
	}

	public function get_acf_user_custom_meta( $user, $field_name, $request ) {
		if ( ( isset( $user['id'] ) && get_current_user_id() === (int) $user['id'] ) || current_user_can( 'administrator' ) ) {
			return get_field( $field_name, 'user_' . $user['id'], false );
		}
	}

	public function update_user_custom_meta( $meta_value, $user, $field_name ) {
		if ( ( isset( $user->data->ID ) && get_current_user_id() === (int) $user->data->ID ) || current_user_can( 'administrator' ) ) {
			if (!empty($meta_value)) update_user_meta( $user->data->ID, $field_name, $meta_value );
		}
	}

	public function update_user_acf_custom_meta( $meta_value, $user, $field_name ) {
		if ( ( isset( $user->data->ID ) && get_current_user_id() === (int) $user->data->ID ) || current_user_can( 'administrator' ) ) {
			if (!empty($meta_value)) update_field( $field_name, $meta_value, 'user_' . $user->data->ID );
		}
	}

}

new Users();


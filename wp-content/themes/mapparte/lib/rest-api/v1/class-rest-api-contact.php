<?php

namespace Mapparte;

/**
 * Class Contact
 *
 * @package Mapparte
 */
class Contact extends Rest_Api {

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'contact_host' ], 11 );
	}


	/**
	 * Add a REST route to reset your password
	 *
	 * @example https://mapparte.com/wp-json/mapparte/v1/contact/{space_id}
	 *
	 * @method POST
	 */
	public function contact_host() {
		register_rest_route( self::NAMESPACE, '/contact/(?P<spaceId>[\d]+)', [
			'methods'             => \WP_REST_Server::CREATABLE,
			'callback'            => [ $this, 'callback_contact' ],
			'permission_callback' => [ $this, 'permission_callback' ],
			'args'                => array(
				'message' => array(
					'description'       => __( "Il tuo messaggio", 'mapparte' ),
					'type'              => 'string',
					'default'           => '',
					'validate_callback' => array( $this, 'is_not_empty' ),
				),
			),
		] );
	}

	/**
	 * Callback for the POST mapparte/v1/contact/{space_id} endpoint
	 *
	 * @param $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 * @throws \Exception
	 */
	public function callback_contact( $request ) {

		$params = $request->get_params();

		$message = \Mapparte\Messages::send_message( sanitize_text_field( $params['message'] ), 0, sanitize_text_field( $params['spaceId'] ) );

		if ( is_int( $message ) ) {
			return rest_ensure_response( [
				'code'    => 200,
				'message' => __( "Messaggio inviato con successo.", 'mapparte' ),
				'data'    => $message
			] );
		} else {
			$error = new \WP_Error( 'space_not_found', __( 'Spazio non trovato', 'mapparte' ) );
			$error->add_data( array(
				'status'     => 404
			) );
			return rest_ensure_response( $error );
		}
	}

}

new Contact();


<?php

namespace Mapparte;

/**
 * Class Rest_Api
 *
 * @package Mapparte
 */
class Rest_Api {

	const NAMESPACE = 'mapparte/v1';

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'add_book_api' ] );
		add_filter( "rest_prepare_space", [ $this, 'show_custom_fields' ], 10, 3 );
	}

	function show_custom_fields( $data, $post, $request ) {
		$_data  = $data->data;
		$fields = get_fields( $post->ID );
		foreach ( $fields as $key => $value ) {
			$_data[ $key ] = get_field( $key, $post->ID );
		}
		$data->data = $_data;

		return $data;
	}

	public function add_book_api() {
		register_rest_route( self::NAMESPACE, '/book/', array(
			'methods'             => 'POST',
			'callback'            => [ $this, 'book_space' ],
			'permission_callback' => [ $this, 'permission_callback' ],
		) );
	}

	public function book_space( $request ) {

		// if you sent any parameters along with the request, you can access them like so:
		$params = $request->get_params();

		$permittedExtension = [ 'jpg', 'jpeg', 'png', 'gif' ];
		$permittedTypes     = [ 'image/jpeg', 'image/png', 'image/gif' ];

		$files = $request->get_file_params();

		if ( ! empty( $files ) && ! empty( $files['file'] ) ) {
			$file = $files['file'];
			error_log( json_encode( $file ) );
		}

		try {
			// smoke/sanity check
			if ( ! $file ) {
				throw new \Exception( 'Error' );
			}
			// confirm file uploaded via POST
			if ( ! is_uploaded_file( $file['tmp_name'] ) ) {
				throw new \Exception( 'File upload check failed ' );
			}
			// confirm no file errors
			if ( ! $file['error'] === UPLOAD_ERR_OK ) {
				throw new \Exception( 'Upload error: ' . $file['error'] );
			}
			// confirm extension meets requirements
			$ext = \pathinfo( $file['name'], PATHINFO_EXTENSION );
			if ( ! in_array( $ext, $permittedExtension ) ) {
				throw new \Exception( 'Invalid extension. ' );
			}
			// check type
			$mimeType = \mime_content_type( $file['tmp_name'] );
			if ( ! in_array( $file['type'], $permittedTypes )
			     || ! in_array( $mimeType, $permittedTypes ) ) {
				throw new \Exception( 'Invalid mime type' );
			}
		} catch ( \Exception $e ) {
			return rest_ensure_response( [ 'error' => $e->getMessage() ] );
		}

		// return any necessary data in the response here
		return rest_ensure_response( [ 'success' => true, 'params' => $params ] );
	}

	public function permission_callback() {
		return current_user_can( 'edit_others_posts' );
	}
}

new Rest_Api();

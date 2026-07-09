<?php

namespace Mapparte;

require( "class-rest-api-utils.php" );
require( "class-rest-api-space.php" );
require( "class-rest-api-taxonomies.php" );
require( "class-rest-api-book.php" );
require( "class-rest-api-favorites.php" );
require( "class-rest-api-availability.php" );
require( "class-rest-api-users.php" );
require( "class-rest-api-login.php" );
require( "class-rest-api-search-fields.php" );
require( "class-rest-api-contact.php" );

/**
 * Class Rest_Api
 *
 * @package Mapparte
 */
class Rest_Api {

	const NAMESPACE = 'mapparte/v1';
	const MINUTES_IN_SLOT = 30;

	public function permission_callback() {
		return current_user_can( 'edit_posts' );
	}

	/**
	 * Consente quote, disponibilità e creazione prenotazione a qualsiasi utente loggato
	 * (subscriber/customer non hanno edit_posts e altrimenti riceverebbero 403).
	 */
	public function permission_callback_logged_in() {
		return is_user_logged_in();
	}

	/**
	 * Returns whether a variable is a valid date time.
	 *
	 * @param $param
	 * @param $request
	 * @param $key
	 *
	 * @return bool
	 */
	public function is_valid_date_time( $param, $request, $key ) {
		return Utils::validateDate( $param );
	}

	/**
	 * Returns whether a variable is a valid date.
	 *
	 * @param $param
	 * @param $request
	 * @param $key
	 *
	 * @return bool
	 */
	public function is_valid_date( $param, $request, $key ) {
		return Utils::validateDate( $param, 'Y-m-d' );
	}

	/**
	 * Returns whether a variable is empty
	 *
	 * @param $param
	 * @param $request
	 * @param $key
	 *
	 * @return bool
	 */
	public function is_not_empty( $param, $request, $key ) {
		return $param !== '' ;
	}
}

new Rest_Api();

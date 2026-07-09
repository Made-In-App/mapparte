<?php

namespace Mapparte;

/**
 * Class Edit_Profile
 *
 * @package Mapparte
 */
class Edit_Profile {
	/**
	 * Edit_Profile constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );
		add_action( 'wp', [ $this, 'save' ] );
	}


	/**
	 * Handle the profile update
	 *
	 * @return array|void
	 */
	public static function save() {

		global $wp_hasher, $response;

		$user_id      = get_current_user_id();
		$current_user = get_userdata( $user_id );
		$request      = $_REQUEST;

		$response = [ 'id' => $user_id ];

		if ( ! is_page( "profilo" ) ) {
			return;
		}

		if ( ! $request ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			return;
		}

		if ( ! user_can( $user_id, 'edit_posts' ) ) {
			return;
		}

		if ( isset( $request['nonce'] ) && ! wp_verify_nonce( urldecode( $request['nonce'] ), 'profile-nonce' ) ) {
			return;
		}

		if ( empty( $wp_hasher ) ) {
			require_once ABSPATH . WPINC . '/class-phpass.php';
			// By default, use the portable hash from phpass.
			$wp_hasher = new \PasswordHash( 8, true );
		}

		if ( isset( $request['oldpassword'] ) && $request['oldpassword'] ) {
			if ( $wp_hasher->CheckPassword( $request['oldpassword'], $current_user->data->user_pass ) ) {

				if ( isset( $request['newpassword'] ) && $request['newpassword'] && ( $request['newpassword'] === $request['confirmpassword'] ) ) {
					wp_update_user( array( 'ID'        => $user_id,
					                       'user_pass' => sanitize_text_field( $request['newpassword'] )
					) );

					//set their new password (this will trigger the logout)
					wp_set_password( $request['newpassword'], $user_id );

					//setup the data to be passed on to wp_signon
					$user_data = array(
						'user_login'    => $current_user->user_login,
						'user_password' => $request['newpassword'],
						'remember'      => false
					);

					// Sign them back in.
					wp_signon( $user_data );
				} else {
					$response['error'] = __('Errore! Inserisci una password valida.', 'mapparte' );
				}
			} else {
				$response['error'] = __("Errore!<br/>Inserisci correttamente la vecchia password.", 'mapparte' );
			}
		}


		unset( $request['nonce'] );
		unset( $request['oldpassword'] );
		unset( $request['newpassword'] );
		unset( $request['confirmpassword'] );

		$core_fields = [ 'user_email' ];

		foreach ( $request as $key => $value ) {
			if ( in_array( $key, $core_fields ) ) {
				if ( $key === 'user_email' ) {
					wp_update_user( array( 'ID' => $user_id, 'user_email' => sanitize_email( $value ) ) );
				}
			} else {
				update_user_meta( $user_id, $key, sanitize_text_field( $value ) );
			}

		}

		return $response;
	}

	public function enqueue_script() {
		if ( is_page( "profilo" ) && is_user_logged_in() ) {
			wp_enqueue_script( 'edit-profile', get_template_directory_uri() . '/assets/js/edit_profile.js', array( 'jquery' ) );
		}
	}

}

new Edit_Profile();
<?php

namespace Mapparte;

/**
 * Class Spazio
 *
 * @package Mapparte
 */
class Spazio {

	/**
	 * Spazio constructor.
	 *
	 */
	public function __construct() {
		add_action( 'save_post_space', [ get_called_class(), 'space_save' ], 10, 3 );
		add_action( 'mapparte_new_space_notification', [ get_called_class(), 'handle_new_space_notifications' ], 10, 1 );
		add_action( 'transition_post_status', [ get_called_class(), 'set_push_notification' ], 10, 3 );
	}

	/**
	 * Register the Spazio post_type
	 */
	public static function space_save( $post_id, $post, $update ) {

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( $post->post_type !== 'space' ) {
			return;
		}

		if ( ! $update ) {
			return;
		}

		// unhook this function to prevent infinite looping
		remove_action( 'save_post_space', [ get_called_class(), 'space_save' ] );

		$_POST["post_name"] = md5( "mapparte-" . $post_id );
		$post->post_name    = $_POST["post_name"];

		// update the post slug
		wp_update_post( [ 'ID' => $post_id, 'post_name' => $_POST["post_name"] ] );

		// re-hook this function
		add_action( 'save_post_space', [ get_called_class(), 'space_save' ], 10, 3 );

	}

	/**
	 * Register push notification on post save
	 */
	public static function set_push_notification( $new_status, $old_status, $post ) {

		if ( wp_is_post_revision( $post->ID ) ) {
			return;
		}

		if ( $post->post_type !== 'space' ) {
			return;
		}

		// Register push notification on post save
		if ( $new_status == 'publish' && $old_status != 'publish' ) {

			// Check if the hook already exists
			if ( wp_next_scheduled( 'mapparte_new_space_notification', [ $post->ID ] ) ) {
				return false;
			}

			wp_schedule_single_event( time() + 5, 'mapparte_new_space_notification', [ $post->ID ] );
		}

	}

	public function handle_new_space_notifications( $post_id ) {

		$address    = get_field( 'address', $post_id );
		$activities = get_post_meta( $post_id, 'activities', true );

		if ( isset( $address['city'] ) ) {
			$meta_query_city['meta_query']['relation'] = 'AND';
			$meta_query_city['meta_query'][]           = array(
				'key'     => 'notifiche_localita',
				'value'   => $address['city'],
				'compare' => 'LIKE'
			);
			$meta_query_city['meta_query'][]           = array(
				'key'     => 'notifiche_disponibilita',
				'value'   => 1,
				'compare' => '= '
			);
			$user_query                                = new \WP_User_Query( $meta_query_city );

			if ( ! empty( $user_query->get_results() ) ) {
				foreach ( $user_query->get_results() as $user ) {
					$notifiche_attivita = explode( ',', get_the_author_meta( 'notifiche_attivita', $user->data->ID ) );

					if ( ! sizeof( array_intersect( $notifiche_attivita, $activities ) ) ) {
						continue;
					}

					$notifiche_price = get_the_author_meta( 'notifiche_prezzo', $user->data->ID );

					$query_args                 = [];
					$query_args['meta_query'][] = [
						'key'     => 'min_price_day',
						'value'   => explode( ";", $notifiche_price ),
						'type'    => 'numeric',
						'compare' => 'BETWEEN',
					];
					$query_args['p']            = $post_id;
					$query_args['post_type']    = 'space';

					$query = new \WP_Query( $query_args );

					if ( $query->post_count ) {
						$one_signal_tokens = json_decode( get_the_author_meta( '_one_signal_tokens', $user->data->ID ), true );
						$one_signal_ids    = ( is_array( $one_signal_tokens ) ) ? array_keys( $one_signal_tokens ) : false;
						if ( $one_signal_ids ) {
							\Mapparte\Push_Notification::sendMessage( __( 'Mapparte ha appena pubblicato uno spazio di tuo interesse.', 'mapparte' ), [ "id" => $post_id ], $one_signal_ids );
						}
					}

				}
			}
		}
	}
}

new Spazio();
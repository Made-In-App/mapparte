<?php

namespace Mapparte;

/**
 * Class Edit_Space
 *
 * @package Mapparte
 */
class Edit_Space {
	/**
	 * Edit_Space constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );
		add_action( 'mapparte_update_prices_min_max', [ $this, 'update_prices_min_max' ], 10, 1 );
		add_action( 'transition_post_status', [ $this, 'space_email_notifications' ], 10, 3 );
		add_action( 'wp_insert_post', [ $this, 'set_default_rating' ], 10, 3 );
		add_action( 'init', [ $this, 'remove_space' ] );
	}

	function remove_space() {
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'remove'
		     && isset( $_REQUEST['space_id'] ) && is_integer( (int) $_REQUEST['space_id'] ) ) {
			wp_trash_post( $_REQUEST['space_id'] );
		}
	}

	function update_prices_min_max( $space_id ) {
		\Mapparte\Space::update_additional_meta( $space_id );
	}

	/**
	 * Setup some variables for the wizard
	 *
	 * @return array
	 */
	public static function setup_wizard() {
		$current_step = isset( $_REQUEST['step'] ) ? (int) $_REQUEST['step'] : 1;
		$next_step    = $current_step + 1;
		$prev_step    = $current_step - 1;
		$max_steps    = 10;
		$progress     = ceil( ( 100 * $current_step ) / $max_steps ) + 1;

		return [ $current_step, $next_step, $prev_step, $progress, $max_steps ];
	}

	/**
	 * Handle the save/update for the wizard
	 *
	 * @param $space_id
	 * @param $space_author
	 * @param $current_step
	 *
	 * @return int|\WP_Error
	 */
	public static function save_space( $space_id, $space_author, $current_step ) {

		$user_id = get_current_user_id();

		if ( ! is_user_logged_in() ) {
			return;
		}

		if ( ! user_can( $user_id, 'edit_posts' ) ) {
			return;
		}

		if ( $space_id && ! user_can( $user_id, 'edit_post', $space_id ) ) {
			return;
		}

		if ( $space_author && $space_author !== $user_id ) {
			return;
		}

		$space_meta = get_post_meta( $space_id );

		// Step 1 - Add new space
		if ( ! $space_id && 2 === $current_step ) {
			$args     = [
				'post_title'   => ' ',
				'post_content' => ' ',
				'post_excerpt' => ' ',
				'post_status'  => 'draft',
				'post_type'    => 'space'
			];
			$space_id = wp_insert_post( $args );

			if ( is_wp_error( $space_id ) ) {
				return $space_id->get_error_message();
			}

			$post_name = md5( "mapparte-" . $space_id );

			// update the post slug
			wp_update_post( [ 'ID' => $space_id, 'post_name' => $post_name ] );
		}

		// Step 1 Update typology
		if ( isset ( $_REQUEST['s_typology'] ) && $space_id ) {
			$terms[] = (int) sanitize_text_field( $_REQUEST['s_typology'] );
			wp_set_post_terms( $space_id, $terms, 'typology' );
		}

		// Update ACF
		if ( isset ( $_REQUEST['acf'] ) && $space_id ) {

			foreach ( $_REQUEST['acf'] as $key => $value ) {
				$value = ! is_array( $value ) ? sanitize_text_field( $value ) : rest_sanitize_array( $value );
				$field = get_field_object( $key );
				if ( isset( $field['type'] ) && 'group' === $field['type'] && isset( $field['name'] ) && 'availability' === $field['name'] ) {
					// TODO: Save availability on mobile
//					if ( is_array( $value ) ) {
//						foreach ( $value as $repeater ) {
//							if ( is_array( $repeater ) ) {
//								foreach ( $repeater as $key2 => $value2 ) {
//									if ( isset( $key2 ) ) {
//										foreach ( $value2 as $key3 => $value3 ) {
//											if ( isset( $key3 ) ) {
//												pre( $key3 );
//												pre( $value3 );
//												update_field( $key3, $value3, $space_id );
//											}
//										}
//									}
//								}
//							}
//						}
//					}
				} else {
					update_field( $key, $value, $space_id );
				}
			}
		}

		if ( isset( $_REQUEST['hide_prices'] ) && $space_id ) {
			update_post_meta( $space_id, 'hide_prices', absint( $_REQUEST['hide_prices'] ) ? 1 : 0 );
		}
		if ( isset( $_REQUEST['hide_availability'] ) && $space_id ) {
			update_post_meta( $space_id, 'hide_availability', absint( $_REQUEST['hide_availability'] ) ? 1 : 0 );
		}

		if ( 4 === $current_step ) {
			// Step 3 Title / Description
			$post             = get_post( $space_id );
			$post_title       = '';
			$post_title_en    = $post ? \WPGlobus_Core::text_filter( $post->post_title, 'en' ) : '';
			$post_excerpt_en  = $post ? \WPGlobus_Core::text_filter( $post->post_excerpt, 'en' ) : '';
			$post_content_en  = $post ? \WPGlobus_Core::text_filter( $post->post_content, 'en' ) : '';

			if ( isset( $_REQUEST['post_title'] ) && $_REQUEST['post_title'] ) {
				$post_title = sprintf( "{:it}%s{:}", sanitize_text_field( $_REQUEST['post_title'] ) );
			}
			if ( $post_title_en ) {
				$post_title .= sprintf( "{:en}%s{:}", sanitize_text_field( $post_title_en ) );
			}

			$post_excerpt = '';

			if ( isset( $_REQUEST['post_excerpt'] ) && $_REQUEST['post_excerpt'] ) {
				$post_excerpt = sprintf( "{:it}%s{:}", sanitize_text_field( $_REQUEST['post_excerpt'] ) );
			}
			if ( $post_excerpt_en ) {
				$post_excerpt .= sprintf( "{:en}%s{:}", sanitize_text_field( $post_excerpt_en ) );
			}


			$post_content = '';

			if ( isset( $_REQUEST['post_content'] ) && $_REQUEST['post_content'] ) {
				$post_content = sprintf( "{:it}%s{:}", sanitize_text_field( $_REQUEST['post_content'] ) );
			}
			if ( $post_content_en ) {
				$post_content .= sprintf( "{:en}%s{:}", sanitize_text_field( $post_content_en ) );
			}

			if ( $post_title || $post_excerpt || $post_content ) {
				$args = [
					'ID'           => $space_id,
					'post_title'   => $post_title,
					'post_excerpt' => $post_excerpt,
					'post_content' => $post_content,
				];

				$space_id = wp_update_post( $args );

				if ( is_wp_error( $space_id ) ) {
					return $space_id->get_error_message();
				}

			}

			wp_create_nonce( 'post_preview_' . $space_id );

		}

		if ( isset( $_REQUEST['gallery_imgs'] ) ) {
			$photos_field = acf_get_field( 'photos' );
			if ( $photos_field && isset( $photos_field['key'] ) ) {
				update_post_meta( $space_id, '_photos', $photos_field['key'] );
			}
			$photo_ids = array_filter( array_map( 'absint', explode( ',', sanitize_text_field( $_REQUEST['gallery_imgs'] ) ) ) );
			update_post_meta( $space_id, 'photos', $photo_ids );
			// Associa gli attachment allo spazio (post_parent) così la media library li mostra correttamente
			foreach ( $photo_ids as $attach_id ) {
				if ( $attach_id && (string) get_post_type( $attach_id ) === 'attachment' ) {
					wp_update_post( [
						'ID'          => $attach_id,
						'post_parent' => $space_id,
					] );
				}
			}
		}

		if ( isset( $_REQUEST['available_slots'] ) ) {

			$available_slots = json_decode( stripslashes( sanitize_text_field( $_REQUEST['available_slots'] ) ) );

			$availability = array_filter(
				$space_meta,
				function ( $key ) {
					return strpos( $key, 'availability' ) !== false;
				},
				ARRAY_FILTER_USE_KEY
			);

			// Reset all the availability post meta
			foreach ( $availability as $key => $meta_field ) {
				delete_post_meta( $space_id, $key );
			}

			$availability_key = acf_get_field( 'availability' )['key'];
			add_post_meta( $space_id, 'availability', '' );
			add_post_meta( $space_id, '_availability', $availability_key );

			foreach ( $available_slots as $key => $daily_slots ) {

				$i         = 0;
				$next_slot = 0;
				$prev_slot = 0;
				$last_slot = end( $daily_slots );

				foreach ( $daily_slots as $key_slot => $slot ) {

					$key_open  = sprintf( "availability_%s_opening_hours_%d_%s_open", $key, $i, $key );
					$key_close = sprintf( "availability_%s_opening_hours_%d_%s_close", $key, $i, $key );

					if ( $key_slot === 0 ) {
						update_post_meta( $space_id, $key_open, $slot );
					}

					if ( $slot === $last_slot ) {
						update_post_meta( $space_id, $key_close, $slot );
						update_post_meta( $space_id, 'availability_' . $key . '_opening_hours', $i + 1 );

					} else if ( $slot !== $next_slot && $next_slot ) {
						update_post_meta( $space_id, $key_close, $prev_slot );

						$i ++;
						$key_open  = sprintf( "availability_%s_opening_hours_%d_%s_open", $key, $i, $key );
						$key_close = sprintf( "availability_%s_opening_hours_%d_%s_close", $key, $i, $key );

						update_post_meta( $space_id, $key_open, $slot );
					}

					$prev_slot = $slot;
					$next_slot = date( "H:i", strtotime( '+30 minutes', strtotime( $slot ) ) );

				}

			}
		}

		// Update max min prices if availability or prices change
		if ( 5 === $current_step || 7 === $current_step ) {
			wp_schedule_single_event( time() + 10, 'mapparte_update_prices_min_max', array( $space_id ) );
		}

		if ( isset ( $_REQUEST['step'] ) && $_REQUEST['step'] == 10 ) {

			$cancel_policy     = '';
			$cancel_policy_key = acf_get_field( 'cancel_policy' )['key'];

			if ( isset( $_REQUEST['politica_it'] ) && sanitize_text_field( $_REQUEST['politica_it'] ) ) {
				$cancel_policy = sprintf( "{:it}%s{:}", sanitize_text_field( $_REQUEST['politica_it'] ) );
			}
			$existing_policy    = get_post_meta( $space_id, 'cancel_policy', true );
			$cancel_policy_en   = $existing_policy ? \WPGlobus_Core::text_filter( $existing_policy, 'en' ) : '';
			if ( $cancel_policy_en ) {
				$cancel_policy .= sprintf( "{:en}%s{:}", sanitize_text_field( $cancel_policy_en ) );
			}

			update_post_meta( $space_id, '_cancel_policy', $cancel_policy_key );
			update_post_meta( $space_id, 'cancel_policy', $cancel_policy );
		}

		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'salva e chiudi' ) {
			echo "<script> jQuery(location).attr('href', '" . get_home_url() . "/my-spaces/'); </script>";
		}

		return $space_id;
	}

	/**
	 * Send the approval request to the website administators
	 *
	 * @param $space_id
	 * @param $space_author
	 * @param $current_step
	 *
	 * @return int|void|\WP_Error
	 */
	public static function space_approval_request( $space_id, $space_author, $current_step ) {

		$user = wp_get_current_user();

		if ( ! is_user_logged_in() ) {
			return;
		}

		if ( ! user_can( $user->data->ID, 'edit_posts' ) ) {
			return;
		}

		if ( $space_id && ! user_can( $user->data->ID, 'edit_post', $space_id ) ) {
			return;
		}

		if ( $space_author && $space_author !== (int) $user->data->ID ) {
			return;
		}

		// Final step - Set status to nuova-richiesta after accepting the terms.
		if ( isset ( $space_id ) && 11 === $current_step && isset ( $_REQUEST['request-approval'] ) ) {
			$nonce = isset( $_REQUEST['space_approval_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['space_approval_nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $nonce, 'mapparte_space_approval_' . (int) $space_id ) ) {
				return new \WP_Error( 'invalid_space_approval_nonce', __( 'La sessione è scaduta. Ricarica la pagina e riprova.', 'mapparte' ) );
			}

			if ( empty( $_REQUEST['space_terms_accepted'] ) ) {
				return new \WP_Error( 'space_terms_required', __( 'Devi accettare i termini e le condizioni d’uso per inviare lo spazio.', 'mapparte' ) );
			}

			$space_url = isset( $_REQUEST['space_url'] ) ? esc_url_raw( wp_unslash( $_REQUEST['space_url'] ) ) : '';
			if ( $space_url ) {
				update_post_meta( $space_id, 'space_url', $space_url );
			} else {
				delete_post_meta( $space_id, 'space_url' );
			}

			$args     = [
				'ID'          => $space_id,
				'post_status' => 'nuova-richiesta',
			];
			$space_id = wp_update_post( $args );

			if ( is_wp_error( $space_id ) ) {
				return $space_id;
			} else {
				update_post_meta( $space_id, 'terms_accepted_at', current_time( 'mysql' ) );
				update_post_meta( $space_id, 'terms_accepted_by', (int) $user->data->ID );

				// Send email notification
				$subject = __('Mapparte - Inserimento del tuo spazio - in attesa di pubblicazione', 'mapparte' );

				$message = sprintf( __("Buongiorno %s,<br>
				abbiamo ricevuto i dati relativi al tuo spazio, ora in fase di approvazione.<br>
				Appena il tuo annuncio sarà online ti verrà comunicato.<br><br>
				Contattaci per informazioni o dubbi, saremo lieti di aiutarti!
				", 'mapparte' ), esc_html( $user->data->display_name ) );

				$footer = __('Grazie.<br>Il team Mapparte!', 'mapparte' );

				$args_notification = [
					'h1'                 => false,
					'body'               => $message,
					'call_to_action'     => false,
					'call_to_action_url' => false,
					'footer'             => $footer,
				];

				\Mapparte\Email_Notification::send_email( $user->data->user_email, $subject, $args_notification );

				// Send email notification to Mapparte
				$subject = __('Mapparte - Un utente ha richiesto l\'approvazione di uno spazio', 'mapparte' );

				$call_to_action     = __("Approva lo spazio", 'mapparte' );
				$call_to_action_url = sprintf( "%s/wp-admin/post.php?post=%d&action=edit", esc_url( get_home_url() ), $space_id );

				$message = sprintf( __("Buongiorno,<br>
				l'utente %s ha aggiunto un nuovo spazio.<br>
				Vai alla scheda di modifica per approvare lo spazio.", 'mapparte' ), esc_html( $user->data->display_name ) );

				$footer = 'Grazie.';

				$args_notification = [
					'h1'                 => false,
					'body'               => $message,
					'call_to_action'     => $call_to_action,
					'call_to_action_url' => $call_to_action_url,
					'footer'             => $footer,
				];

				\Mapparte\Email_Notification::send_email( get_bloginfo( 'admin_email' ), $subject, $args_notification );

				echo "<script> jQuery(location).attr('href', '/my-spaces/'); </script>";

				return $space_id;
			}
		}
	}

	public function enqueue_script() {
		if ( is_page( "inserisci-il-tuo-spazio" ) && is_user_logged_in() ) {
			wp_enqueue_script( 'add-space-script', get_template_directory_uri() . '/assets/js/edit_space.js', array( 'jquery' ) );
		}
	}

	/**
	 * Handle the logic behind email notifications
	 *
	 * @param $new_status
	 * @param $old_status
	 * @param $post
	 */
	function space_email_notifications( $new_status, $old_status, $post ) {

		if ( 'space' !== get_post_type( $post->ID ) ) {
			return;
		}
		if ( wp_is_post_revision( $post->ID ) ) {
			return;
		}
		if ( 'auto-draft' === $new_status ) {
			return;
		}
		if ( $new_status === $old_status ) {
			return;
		}

		if ( 'publish' === $new_status ) {

			$to = get_the_author_meta( 'email', $post->post_author );

			$subject = __("Mapparte - Il tuo spazio è pubblicato!", 'mapparte' );

			$message = sprintf( __("Buone notizie: <b>%s</b> è online!<br><br>
				Da adesso sarà visibile sul nostro portale.<br>
				Tutte le informazioni inserite appariranno nella scheda completa del tuo spazio.<br><br>
				Potrai modificarle in qualsiasi momento accedendo alla dashboard del tuo profilo.
				", 'mapparte' ), esc_html( $post->post_title ) );

			$footer = __("A presto e buon lavoro,<br>Il team Mapparte!", 'mapparte' );

			$args = [
				'h1'                 => false,
				'body'               => $message,
				'call_to_action'     => false,
				'call_to_action_url' => false,
				'footer'             => $footer,
			];

			\Mapparte\Email_Notification::send_email( $to, $subject, $args );
		}
	}

	function set_default_rating( $post_id, $post, $update ) {

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( $post->post_type !== 'space' ) {
			return;
		}

		if ( $update ) {
			return;
		}

		$tot_ratings = [
			'pulizia'         => [ 5, 5, 5, 5, 5, 5, 5, 5, 5, 5 ],
			'aderenza'        => [ 5, 5, 5, 5, 5, 5, 5, 5, 5, 5 ],
			'professionalita' => [ 5, 5, 5, 5, 5, 5, 5, 5, 5, 5 ],
		];

		update_post_meta( $post_id, '_tot_ratings', $tot_ratings );
		update_field( 'pulizia', 5, $post_id );
		update_field( 'aderenza', 5, $post_id );
		update_field( 'professionalita', 5, $post_id );

	}
}

new Edit_Space();

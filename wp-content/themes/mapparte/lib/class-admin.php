<?php

namespace Mapparte;

/**
 * Class Core
 *
 * @package Mapparte
 */
class Admin {

	public function __construct() {
		add_action( 'admin_init', [ $this, 'mapparte_restrict_admin' ] );
		add_action( 'pre_get_posts', [ $this, 'filter_bookings' ] );
		add_action( 'pre_get_posts', [ $this, 'filter_my_spaces' ] );
		add_action( 'wp', [ $this, 'check_authorization' ] );
		add_action( 'post_submitbox_misc_actions', [ $this, 'post_status_dropdown_script' ] );
		add_action( 'admin_head-edit.php', [ $this, 'quick_edit_script' ] );
		add_action( 'user_register', [ $this, 'set_default_rating' ] );
		add_action('wp_trash_post', [ $this, 'restrict_post_deletion' ], 10, 1);
		add_action('before_delete_post',[ $this, 'restrict_post_deletion' ], 10, 1);
		add_action( 'init', [ $this, 'remove_schedule_delete' ] );
	}

	public function check_authorization() {
		global $post, $wp_query;
		if ( is_singular( 'space' ) && $post->post_status !== 'publish' && is_user_logged_in() ) {
			if ( (int) $post->post_author !== get_current_user_id() && ! current_user_can( 'administrator' ) ) {
				header( "HTTP/1.1 401 Unauthorized" );
				header( "Location: " . get_home_url() );
				exit;
			}
		}
		if ( is_singular( 'booking' ) ) {
			$details = get_post_meta( $post->ID, '_booking_details', true );
			if ( get_current_user_id() !== (int) $post->post_author && get_current_user_id() !== (int) get_post_field( 'post_author', $details['spaceId'] ) ) {
				header( "HTTP/1.1 401 Unauthorized" );
				header( "Location: " . get_home_url() );
				exit;
			}
		}
		if ( is_post_type_archive( 'booking' ) && ! is_user_logged_in() ) {
			header( "HTTP/1.1 401 Unauthorized" );
			header( "Location: " . get_home_url() );
			exit;
		}

		if ( $wp_query->query_vars['p'] && 'booking' === $wp_query->query_vars['post_type'] && ! is_user_logged_in() ) {
			header( "HTTP/1.1 401 Unauthorized" );
			header( "Location: " . get_home_url() . '?redirect=' . urlencode( get_permalink( $wp_query->query_vars['p'] ) ) );
			exit;
		}

		if ( is_post_type_archive( 'space' ) && isset( $wp_query->query_vars['mine'] ) && $wp_query->query_vars['mine'] && ! is_user_logged_in() ) {
			header( "HTTP/1.1 401 Unauthorized" );
			header( "Location: " . get_home_url() );
			exit;
		}

		if ( ( is_page( "messaggi" ) || is_page( "inserisci-il-tuo-spazio" ) || is_page( "profilo" ) || is_page( "preferiti" ) ) && ! is_user_logged_in() ) {
			header( "HTTP/1.1 401 Unauthorized" );
			header( "Location: " . get_home_url() );
			exit;
		}

	}

	public function mapparte_restrict_admin() {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		$user_id = get_current_user_id();
		// Administrator e Editor possono usare wp-admin (campi ACF sponsorizzazione, approvazione spazi, ecc.).
		// Altri ruoli vengono reindirizzati in home — era questo il motivo per cui «dal backend» non si poteva agire se non si era admin.
		$allow = current_user_can( 'manage_options' ) || current_user_can( 'edit_others_posts' );
		/** @param bool $allow   Accesso consentito prima del filtro. @param int $user_id */
		$allow = (bool) apply_filters( 'mapparte_user_can_access_wp_admin', $allow, $user_id );

		if ( ! $allow ) {
			wp_redirect( home_url() );
			exit;
		}
	}

	function filter_bookings( $query ) {
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
		if ( is_admin() || strpos( $uri, '/wp-json/' ) !== false ) {
			return $query;
		}

		// Singolo booking via ?p=ID&post_type=booking (link email, anteprima wp-admin, ecc.):
		// NON applicare i filtri da archivio (meta _host_id), altrimenti l'ospite (autore post) ottiene 404.
		// Con i CPT, is_single() in pre_get_posts non è sempre affidabile → non basarci solo su quello.
		$booking_id = isset( $query->query_vars['p'] ) ? (int) $query->query_vars['p'] : 0;
		if ( isset( $query->query_vars['post_type'] ) && 'booking' === $query->query_vars['post_type'] && $booking_id > 0 ) {
			if ( is_user_logged_in() ) {
				// Stati custom (nuova-richiesta, …) + eventuale draft in anteprima.
				$query->set( 'post_status', 'any' );
			}
			return $query;
		}

		if ( ! is_single() && isset( $query->query_vars['post_type'] ) && 'booking' === $query->query_vars['post_type'] ) {
			$query->set( 'post_status', [ 'nuova-richiesta', 'accettata', 'cancellata', 'feedback', 'pagata' ] );

			if ( isset( $query->query_vars['mine'] ) && '1' === $query->query_vars['mine'] ) {
				$query->set( 'author', get_current_user_id() );
			} else {
				$query->set( 'meta_query', array(
					array(
						'key'   => '_host_id',
						'value' => get_current_user_id(),
					)
				) );
			}

		}

		return $query;
	}

	function filter_my_spaces( $query ) {
		if ( ! is_admin() && isset( $query->query_vars['post_type'] ) && 'space' === $query->query_vars['post_type'] && isset( $query->query_vars['mine'] ) && '1' === $query->query_vars['mine'] ) {

			$query->set( 'post_status', 'any' );
			$query->set( 'author', get_current_user_id() );

		}
	}

	public function post_status_dropdown_script() {
		global $post;
		if ( $post->post_type === 'space' ) {
			echo "<script>
			jQuery(document).ready( function() {
			    
			    setTimeout(function(){ 
			         jQuery('select[name=\"post_status\"] option[value=\"feedback\"]').remove(); 
			         jQuery('select[name=\"post_status\"] option[value=\"pagata\"]').remove();
			    }, 500);
				
			});
		</script>";
		} else if ( $post->post_type === 'booking' ) {
			echo "<script>
			jQuery(document).ready( function() {
			    setTimeout(function(){ 
			         jQuery('select[name=\"post_status\"] option[value=\"draft\"]').remove();
			         jQuery('#major-publishing-actions').html('');
			    }, 100);
				
			});
		</script>";
		}
	}

	function quick_edit_script() {

		global $current_screen;

		if ( 'edit-booking' != $current_screen->id && 'edit-space' != $current_screen->id ) {
			return;
		}
		?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                setTimeout(function () {
					<?php if( 'edit-booking' === $current_screen->id ) : ?>
                    jQuery('select[name=\"_status\"] option[value=\"draft\"]').remove();
                    jQuery('select[name=\"_status\"] option[value=\"publish\"]').remove();
					<?php elseif ( 'edit-space' === $current_screen->id ) : ?>
                    jQuery('select[name="_status"] option[value="feedback"]').remove();
                    jQuery('select[name="_status"] option[value="pagata"]').remove();
					<?php endif; ?>
                }, 500);
            });
        </script>
		<?php
	}

	function set_default_rating( $user_id ) {
		$tot_ratings = [
			'puntualita'               => [ 5, 5, 5, 5, 5, 5, 5, 5, 5, 5 ],
			'cura'                     => [ 5, 5, 5, 5, 5, 5, 5, 5, 5, 5 ],
			'rispetto_delle_dotazioni' => [ 5, 5, 5, 5, 5, 5, 5, 5, 5, 5 ],
		];
		update_user_meta( $user_id, '_tot_ratings', $tot_ratings );
		update_field( 'puntualita', 5, 'user_' . $user_id );
		update_field( 'cura', 5, 'user_' . $user_id );
		update_field( 'rispetto_delle_dotazioni', 5, 'user_' . $user_id );
	}

	// disable delete entirely
	function restrict_post_deletion($post_ID){
		$type = get_post_type($post_ID);
		if( ( $type == 'booking' ) && is_admin() ){
			echo __( 'Non sei autorizzato a cancellare lo spazio.', 'mapparte' );
			exit;
		}
	}

	/** Prevent automated deletion for trashed items */
	function remove_schedule_delete() {
		remove_action( 'wp_scheduled_delete', 'wp_scheduled_delete' );
	}
}

new Admin();
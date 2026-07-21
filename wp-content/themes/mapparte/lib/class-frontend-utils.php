<?php

namespace Mapparte;

/**
 * Class Frontend_Utils
 *
 * @package Mapparte
 */
class Frontend_Utils {

	public static function favorite_button( $post_id ) {
		if ( is_user_logged_in() ) {
			echo get_favorites_button( $post_id );
			return;
		}

		printf(
			'<button type="button" class="favorite-login-button xoo-el-login-tgr" data-redirect="%1$s" aria-label="%2$s" title="%2$s"><i class="far fa-heart" aria-hidden="true"></i></button>',
			esc_url( get_permalink( $post_id ) ),
			esc_attr__( 'Accedi o registrati per aggiungere lo spazio ai preferiti', 'mapparte' )
		);
	}

	static public function get_taxonomy_select( $taxonomy, $label = '', $first_option = '', $value = '' ) {
		echo "<select name=\"s_$taxonomy\" id=\"s_$taxonomy\" class=\"form-select form-control\" aria-label=\"$label\">";
		$tax_terms = get_terms( $taxonomy, array( 'hide_empty' => '0' ) );
		if ( $first_option ) {
			echo "<option value=\"0\">" . esc_html( $first_option ) . "</option>";
		}
		foreach ( $tax_terms as $tax_term ):
			$selected = ( (int) $value === $tax_term->term_id ) ? "selected=\"selected\"" : "";
			echo sprintf( '<option value="%s" %s>%s</option>', esc_attr( $tax_term->term_id ), $selected, esc_html( ucfirst( $tax_term->name ) ) );
		endforeach;
		echo "</select>";
	}

	static public function get_taxonomy_select_by_slug( $taxonomy, $label = '', $first_option = '', $value = '' ) {

		echo "<select name=\"xoo_aff_select_list_attivita\" id=\"xoo_aff_select_list_attivita\" class=\"form-select form-control\" aria-label=\"$label\">";
		$tax_terms = get_terms( $taxonomy, array( 'hide_empty' => '0' ) );
		if ( $first_option ) {
			echo "<option value=\"0\">" . esc_html( $first_option ) . "</option>";
		}
		foreach ( $tax_terms as $tax_term ):
			$selected = ( $value === $tax_term->slug ) ? "selected=\"selected\"" : "";
			echo sprintf( '<option value="%s" %s>%s</option>', esc_attr( $tax_term->slug ), $selected, esc_html( ucfirst( $tax_term->name ) ) );
		endforeach;
		echo "</select>";
	}

	static public function get_taxonomy_checkbox( $taxonomy, $value = '' ) {
		$tax_terms = get_terms( $taxonomy, array( 'hide_empty' => '0' ) );

		foreach ( $tax_terms as $tax_term ): ?>
            <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" <?php echo ( is_array( $value ) && in_array( $tax_term->term_id, $value ) ) ? "checked='checked'" : "" ?>
                           type="checkbox" value="<?php echo esc_attr( $tax_term->term_id ); ?>"
                           id="s_<?php echo esc_attr( $taxonomy ); ?>[]"
                           name="s_<?php echo esc_attr( $taxonomy ); ?>[]"><?php echo ucfirst( esc_html( $tax_term->name ) ); ?>
                </label>
            </div>
		<?php
		endforeach;
	}

	static public function get_acf_choices_by_field_name( $name, $value ) {
		$field = acf_get_field( $name );

		if ( isset( $field['choices'] ) && is_array( $field['choices'] ) ) {
			foreach ( $field['choices'] as $choice ) {
				?>
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" <?php echo ( is_array( $value ) && in_array( $choice, $value ) ) ? "checked='checked'" : "" ?>
                               type="checkbox" value="<?php echo esc_attr( $choice ); ?>"
                               id="<?php echo esc_attr( $name ); ?>[]"
                               name="<?php echo esc_attr( $name ); ?>[]"><?php echo ucfirst( esc_html( $choice ) ); ?>
                    </label>
                </div>
				<?php
			}
		}
	}

	/**
	 * Return Space status
	 *
	 * @param $status
	 *
	 * @return mixed
	 */
	public static function get_space_status( $status ) {

		switch ( $status ) {
			case 'draft':
				return [ 'new-request', $status, 'Bozza' ];
				break;
			case 'nuova-richiesta':
				return [ 'feedback', $status, 'In attesa di approvazione' ];
				break;
			case 'accettata':
				return [ 'accepted', $status, 'Approvato' ];
				break;
			case 'publish':
				return [ 'accepted', $status, 'Pubblicato' ];
				break;
			case 'cancellata':
				return [ 'rejected', $status, 'Rifiutato' ];
				break;
		}

	}

	/**
	 * Return Get Rating
	 *
	 * @param post_id
	 *
	 * @return mixed
	 */
	public static function get_rating($post_id,$type) {

		$pulizia = (get_field('pulizia',$post_id)) ? get_field('pulizia',$post_id) : 0;
		$aderenza = (get_field('aderenza',$post_id)) ? get_field('aderenza',$post_id) : 0;
		$professionalita = (get_field('professionalita',$post_id)) ? get_field('professionalita',$post_id) : 0;
		$rating = (floatval($pulizia)+floatval($aderenza)+floatval($professionalita))/3;

		
		if ($rating == 0) $rating = 5;
		$average_stars = round($rating * 2 ) / 2;

		$drawn = 5;
		if ($type == "star"){
			echo '<div class="rating">';
			
			// full stars.
			for ( $i = 0; $i < floor( $average_stars ); $i++ ) {
				$drawn--;
				echo '<svg aria-hidden="true" data-prefix="fas" data-icon="star" class="svg-inline--fa fa-star fa-w-18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"/></svg>';
			}

			// half stars.
			if ( $rating - floor( $average_stars ) === 0.5 ) {
				$drawn--;
				echo '<svg aria-hidden="true" data-prefix="fas" data-icon="star-half-alt" class="svg-inline--fa fa-star-half-alt fa-w-17" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 536 512"><path fill="currentColor" d="M508.55 171.51L362.18 150.2 296.77 17.81C290.89 5.98 279.42 0 267.95 0c-11.4 0-22.79 5.9-28.69 17.81l-65.43 132.38-146.38 21.29c-26.25 3.8-36.77 36.09-17.74 54.59l105.89 103-25.06 145.48C86.98 495.33 103.57 512 122.15 512c4.93 0 10-1.17 14.87-3.75l130.95-68.68 130.94 68.7c4.86 2.55 9.92 3.71 14.83 3.71 18.6 0 35.22-16.61 31.66-37.4l-25.03-145.49 105.91-102.98c19.04-18.5 8.52-50.8-17.73-54.6zm-121.74 123.2l-18.12 17.62 4.28 24.88 19.52 113.45-102.13-53.59-22.38-11.74.03-317.19 51.03 103.29 11.18 22.63 25.01 3.64 114.23 16.63-82.65 80.38z"/></svg>';
			}

			// empty stars.
			for ( $i = 0; $i < $drawn; $i++ ) {
				echo '<svg aria-hidden="true" data-prefix="far" data-icon="star" class="svg-inline--fa fa-star fa-w-18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z"/></svg>';
			}

			echo '</div>';
		}else{
			echo $average_stars;
		}
	

	}

	/**
	 * Return Get Rating
	 *
	 * @param post_id
	 *
	 * @return mixed
	 */
	public static function get_rating_single( $field, $id ) {

		$rating = ( get_field( $field, $id ) ) ? get_field( $field, $id ) : 0;

		$starRating = new \StarRatingField();
		echo '<div class="acf-input">';
		echo '<div class="field_type-star_rating_field theme-color">';
		echo $starRating->make_list( 5,
			$rating,
			'<li><i class="%s star-%d"></i></li>',
			array( 'fa fa-star-o', 'fa fa-star-half-o', 'fa fa-star' ),
			true );
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Return Booking status
	 *
	 * @param $status
	 *
	 * @return mixed
	 */
	public static function get_booking_status( $booking, $details ) {

		$rating = false;
		$guest_id = (int) $booking->post_author;
		$host_id = (int) get_post_field( 'post_author', $details['spaceId'] );

		if ( $guest_id ===  get_current_user_id() ) { // guest
			$rating = get_post_meta( $booking->ID, '_rating_guest', true );
        } else if ( $host_id ===  get_current_user_id() ) { // host
			$rating = get_post_meta( $booking->ID, '_rating_host', true );
        }

		$feedback_label = ( $rating ) ? strtolower(__('COMPLETATA', 'mapparte' )) : __('Dai un feedback', 'mapparte' );

		switch ( $booking->post_status ) {
			case 'nuova-richiesta':
				return [ 'new-request', $booking->post_status, 'Nuova richiesta' ];
				break;
			case 'accettata':
				return [ 'accepted', $booking->post_status, 'Accettata' ];
				break;
			case 'pagata':
				return [ 'paid', $booking->post_status, 'Pagata' ];
				break;
			case 'cancellata':
				return [ 'rejected', $booking->post_status, 'Cancellata' ];
				break;
			case 'feedback':
				return [ 'feedback', $booking->post_status, $feedback_label ];
				break;
		}

	}

	/**
	 * Display activities
	 *
	 * @param $status
	 *
	 * @return mixed
	 */
	public static function show_activities( $activities ) {

		if ( is_array( $activities ) && sizeof( $activities ) > 0 ) {
			foreach ( $activities as $activity_id ) {
				$activity = get_term_by( 'id', $activity_id, 'activity' );
				?>
                <div class="col activities-tile">
                    <img class="activities-img"
                         src="<?php echo get_field( 'immagine', $activity->taxonomy . '_' . $activity->term_id ); ?>"
                         alt="photo">
                    <img class="divider" src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                         alt="divider">
                    <h5 class="activities-ttl"><?php echo esc_html( $activity->name ); ?></h5>
                </div>
				<?php
			}
		}
	}

	/**
	 * Display frequent activities
	 *
	 * @param $status
	 *
	 * @return mixed
	 */
	public static function show_frequent_activities( $spaceId ) {
		global $wpdb;

		if ( ! $spaceId ) {
			return;
		}

		$sql = "SELECT DISTINCT planningTo FROM " . $wpdb->prefix . "mapparte_bookings WHERE spaceId = $spaceId LIMIT 5";

		$results = $wpdb->get_results( $sql );
		if ( sizeof( $results ) > 0 ) :
			?>
            <div class="activities-wrapper">
                <h3 class="activities-title"><?php echo __("Spesso prenotato per", 'mapparte' );?></h3>
                <div class="row">
					<?php
					foreach ( $results as $activity ) {
						$activity = get_term_by( 'id', $activity->planningTo, 'activity' );
						if ( $activity ) { ?>
                            <div class="col activities-tile">
                                <img class="activities-img"
                                     src="<?php echo get_field( 'immagine', $activity->taxonomy . '_' . $activity->term_id ); ?>"
                                     alt="photo">
                                <img class="divider"
                                     src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                                     alt="divider">
                                <h5 class="activities-ttl"><?php echo esc_html( $activity->name ); ?></h5>
                            </div>
							<?php
						}
					} ?>
                </div>
            </div>
		<?php
		endif;
	}

	/**
	 * Show the google map
	 *
	 * @param $position
	 */
	public static function show_map( $position ) {
		if ( is_array( $position ) ) {
			?>
            <div class="position-header">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/position-icon.png" alt="position">
				<?php if ( ! is_user_logged_in() ) : ?>
                    <a data-redirect="<?php echo get_permalink(); ?>" href="<?php echo get_permalink(); ?>"
                       class="xoo-el-login-tgr">
                        <p class="address"><?php echo __("Visualizza la posizione esatta", 'mapparte' );?></p>
                    </a>
				<?php else : ?>
                    <p class="address"><?php echo esc_html( $position['address'] ); ?></p>
				<?php endif; ?>
            </div>
            <img class="divider mt-3"
                 src="<?php echo get_template_directory_uri(); ?>/assets/images/position-divider.png" alt="divider"/>
            <div class="position-body">
                <h4>Posizione</h4>
				<?php if ( is_user_logged_in() ) : ?>
                    <div class="google-map" data-zoom="16">
                        <div class="marker" data-lat="<?php echo esc_attr( $position['lat'] ); ?>"
                             data-lng="<?php echo esc_attr( $position['lng'] ); ?>"></div>
                    </div>
				<?php else : ?>
                    <img class="position-img"
                         src="<?php echo get_template_directory_uri(); ?>/assets/images/position-not-loged.png"
                         alt="position">
				<?php endif; ?>
            </div>
			<?
		}
	}

	/**
	 * Show Additional Infos
	 *
	 * @param $values
	 */
	public static function show_additional_infos( $values ) {
		if ( is_array( $values ) ) {
			foreach ( $values as $value ) {
				printf( '<p class="col-8">%s</p>', ucfirst( esc_html( $value ) ) );
				printf( '<!--p class="col-4 text-end"></p -->' );
			}
			?>
			<?php
		}
	}

	public static function truncate_string( $string, $your_desired_width ) {
		$parts       = preg_split( '/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE );
		$parts_count = count( $parts );

		$length    = 0;
		$last_part = 0;
		for ( ; $last_part < $parts_count; ++ $last_part ) {
			$length += strlen( $parts[ $last_part ] );
			if ( $length > $your_desired_width ) {
				break;
			}
		}

		return implode( array_slice( $parts, 0, $last_part ) );
	}

	public static function format_date_time( $date_time ) {
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );

		return date_i18n( "$date_format - $time_format", strtotime( $date_time ) );;
	}

	public static function format_date( $date_time ) {
		$date_format = get_option( 'date_format' );
		return date_i18n( "$date_format", strtotime( $date_time ) );;
	}

	/**
	 * Show the availability
	 *
	 * @param $availability
	 */
	public static function show_availability( $availability ) {
		$weekdays = [
			'mon' => __('Lunedì','mapparte'),
			'tue' => __('Martedì','mapparte'),
			'wed' => __('Mercoledì','mapparte'),
			'thu' => __('Giovedì','mapparte'),
			'fri' => __('Venerdì','mapparte'),
			'sat' => __('Sabato','mapparte'),
			'sun' => __('Domenica','mapparte'),
		];
		if ( is_array( $availability ) ) {
			foreach ( $availability as $time_ranges ) {
				if ( $time_ranges ) {
					$day           = '';
					$key_time_prev = '';
					foreach ( $time_ranges as $key_time => $time_range ) {

						foreach ( $time_range as $key => $time ) {
							if ( $day !== $weekdays[ explode( '_', $key )[0] ] ) {
								if ( $day ) {
									echo '</p>';
								}
								$day = $weekdays[ explode( '_', $key )[0] ];
								printf( "<p class=\"col-5\">%s</p>", esc_html( $day ) );
								echo '<p class="col-7">';
							}
							if ( $key_time && $key_time_prev !== $key_time ) {
								$key_time_prev = $key_time;
								echo ' / ';
							}
							$hour_min = new \DateTime( $time );
							if ( explode( '_', $key )[1] === 'close' ) {
								$hour_min->modify( "+30 minutes" );
							}
							echo esc_html( $hour_min->format( 'H:i' ) );
							if ( explode( '_', $key )[1] === 'open' ) {
								echo '-';
							}
						}
					}
				}
			}
		}
	}

	/**
     * Return if it's a blog
     *
	 * @return bool
	 */
	public static function is_blog_page() {
		global $post;
		$post_type = get_post_type( $post );

		return (
			( is_home() || is_archive() || is_single() )
			&& ( $post_type == 'post' )
		) ? true : false;
	}
}

<?php

namespace Mapparte;

/**
 * Class Filters
 *
 * @package Mapparte
 */
class Filters {

	public function __construct() {
		add_filter( 'wp_nav_menu_items', [ $this, 'add_admin_link' ], 10, 2 );
		add_filter( 'wp_nav_menu_objects', [ $this, 'remove_app_menu_item' ], 100, 2 );
		add_filter( 'nav_menu_css_class', [ $this, 'add_additional_class_on_li' ], 10, 3 );
		add_filter( 'wp_is_application_passwords_available', '__return_true' );
		add_filter( 'body_class', [ $this, 'my_body_classes' ] );
		add_filter( 'use_block_editor_for_post', '__return_false' );
		add_filter( 'mc4wp_form_settings', [ $this, 'newsletter_form_settings' ], 10, 2 );
		add_filter( 'mc4wp_form_content', [ $this, 'newsletter_form_content' ], 20, 2 );
		add_filter( 'mc4wp_form_messages', [ $this, 'newsletter_form_messages' ], 10, 2 );
		add_filter( 'xoo_aff_field_html', [ $this, 'registration_residence_label' ], 10, 3 );
		add_filter( 'wpcf7_form_elements', [ $this, 'link_contact_form_terms' ] );
		add_action( 'mc4wp_form_subscribed', [ $this, 'send_newsletter_confirmation' ], 10, 2 );
	}

	/**
	 * Keep the public newsletter form connected to the Mapparte audience.
	 */
	public function newsletter_form_settings( $settings, $form ) {
		if ( 662 !== (int) $form->ID ) {
			return $settings;
		}

		$settings['lists']        = [ 'a947082c65' ];
		$settings['double_optin'] = false;

		return $settings;
	}

	/**
	 * Repair the email field stored in the legacy MC4WP form.
	 */
	public function newsletter_form_content( $content, $form ) {
		if ( 662 !== (int) $form->ID ) {
			return $content;
		}

		$email_field = '<input type="email" class="form-control" name="EMAIL" placeholder="Email" autocomplete="email" required>';

		return preg_replace( '/<input\b[^>]*\btype=(["\'])email\1[^>]*>/i', $email_field, $content, 1 );
	}

	/**
	 * Use the approved newsletter confirmation copy on the form response.
	 */
	public function newsletter_form_messages( $messages, $form ) {
		if ( 662 !== (int) $form->ID ) {
			return $messages;
		}

		$messages['subscribed'] = $this->newsletter_confirmation_message();

		return $messages;
	}

	/**
	 * Send the same confirmation copy by email after a successful subscription.
	 */
	public function send_newsletter_confirmation( $form, $email ) {
		if ( 662 !== (int) $form->ID || ! is_email( $email ) ) {
			return;
		}

		$args_notification = [
			'h1'                 => false,
			'body'               => $this->newsletter_confirmation_message(),
			'call_to_action'     => false,
			'call_to_action_url' => false,
			'footer'             => false,
		];

		\Mapparte\Email_Notification::send_email(
			$email,
			__( 'Mapparte - Iscrizione alla newsletter', 'mapparte' ),
			$args_notification
		);
	}

	private function newsletter_confirmation_message() {
		return __( 'Ora sei iscritta/o alla nostra Newsletter!<br>
News, novità dalla community di Mapparte, sulla creatività e molto altro..<br>
Non preoccuparti, ci sentiremo solo una volta al mese!', 'mapparte' );
	}

	public function registration_residence_label( $html, $field_id, $field_data ) {
		if ( 'xoo_aff_text_residenza' !== $field_id && false === strpos( $html, 'xoo_aff_text_residenza' ) ) {
			return $html;
		}

		return str_replace( 'Residenza', __( 'Indirizzo di residenza', 'mapparte' ), $html );
	}

	public function link_contact_form_terms( $html ) {
		$contact_form = function_exists( 'wpcf7_get_current_contact_form' ) ? wpcf7_get_current_contact_form() : null;
		if ( ! $contact_form || 322 !== (int) $contact_form->id() ) {
			return $html;
		}

		$terms_link = sprintf(
			'<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
			esc_url( home_url( '/termini-e-condizioni-duso/' ) ),
			esc_html__( "termini e le condizioni d'uso", 'mapparte' )
		);

		return str_replace(
			"termini e le condizioni d'uso",
			$terms_link,
			$html
		);
	}

	public function my_body_classes( $classes ) {
		global $post;
		if ( is_post_type_archive( 'space' ) ) {
			$classes[] = 'search-page';
		}
		if ( \Mapparte\Frontend_Utils::is_blog_page() && ! is_single() ) {
			$classes[] = 'magazine-page';
		}
		if ( is_page( "inserisci-il-tuo-spazio" ) ) {
			$classes[] = 'my-space-page';
		}
		if ( is_singular( 'post' ) ) {
			$classes[] = 'magazine-detail-page';
		}
		if ( is_search() || is_page( "lista-spazi" ) ) {
			$classes[] = 'magazine-result-page';
		}
		if ( is_page( "profilo" ) ) {
			$classes[] = 'profile-page';
		}
		if ( is_page( "contatti" ) ) {
			$classes[] = 'contact-page';
		}

		if ( is_page( 'attiva-sponsorizzazione' ) ) {
			$classes[] = 'subscription-list-page';
		}

		if ( is_page( 'dettaglio-sponsorizzazione' ) ) {
			$classes[] = 'subscription-detail-page';
		}

		if ( is_singular( 'space' ) ) {
			$classes[] = 'detail-logged-page';
		}

		if ( is_singular( 'space' ) && ! is_user_logged_in() ) {
			$classes[] = 'detail-not-logged-page';
		}

		if ( is_singular( 'booking' ) ) {
			$classes[] = 'new-booking-page';
		}

		return $classes;
	}

	public function add_admin_link( $items, $args ) {
		if ( $args->theme_location === 'not-logged' ) {

			$redirect = isset( $_REQUEST['redirect'] ) ? urldecode( $_REQUEST['redirect'] ) : get_permalink();

			$items .= '<li class="nav-item">';
			$items .= '<a href="' . get_permalink() . '" id="nav-login" class="xoo-el-login-tgr" data-redirect="' . esc_attr( $redirect ) . '"><img src="' . get_template_directory_uri() . '/assets/images/logout.svg" alt="menu-icon"></a>';
			$items .= '</li>';
		} else {
			$current_user = wp_get_current_user();
			$avatar = get_user_meta( $current_user->ID, 'immagine', true ) ? wp_get_attachment_image_src( get_user_meta( $current_user->ID, 'immagine', true ) )[0] : get_template_directory_uri() . '/assets/images/user.png';

			$items        .= '<li class="nav-item logged">';
			$items        .= '<a class="nav-link" href="javascript:void(0)">';
			if ( ! empty( $avatar ) ) {
				$items .= '<img src="' . $avatar . '" alt="menu-icon" />';
			} else {
				$items .= '<i class="fa fa-user-circle"></i>';
			}
			$items .= '</a>';
			$items .= '<div class="logged-popup">';
			$items .= '<ul class="user-detail-list">';
			$items .= '<li class="user-detail-item d-flex align-items-center">';
			$items .= '<div  class="image-profile-container">';
			if ( ! empty( $avatar ) ) {
				$items .= '<img style="width: 4rem;" src="' . $avatar . '" alt="menu-icon" />';
			} else {
				$items .= '<i class="fa fa-user-circle"></i>';
			}
			$items .= '</div>';
			$items .= '<p class="user-name">';
			$items .= $current_user->display_name . ' <a href="mailto:' . $current_user->user_email . '">' . $current_user->user_email . '</a>';
			$items .= '</p>';
			$items .= '</li>';
			$items .= '<li class="user-detail-item">';
			$items .= '<a href="'. get_home_url() .'/profilo/">'.__("Gestisci", 'mapparte' ).' </a>'; //<span class="notify"></span>
			$items .= '</li>';
			$items .= '<li class="user-detail-item">';
			$items .= '<a href="' . wp_logout_url( $_SERVER["REQUEST_URI"] ) . '">Esci</a>';
			$items .= '</li>';
			$items .= '</ul>';
			$items .= '</div>';
			$items .= '</li>';
		}

		return $items;
	}

	public function remove_app_menu_item( $items, $args ) {
		return array_values( array_filter( $items, function ( $item ) {
			$title = strtolower( trim( wp_strip_all_tags( $item->title ) ) );
			$url   = strtolower( trim( $item->url ) );
			$classes = isset( $item->classes ) && is_array( $item->classes )
				? strtolower( implode( ' ', $item->classes ) )
				: '';

			$is_app_download = ( false !== strpos( $title, 'scarica' ) && false !== strpos( $title, 'app' ) )
				|| false !== strpos( $url, 'scarica-lapp' )
				|| false !== strpos( $url, 'scarica-app' );

			$is_language_switcher = false !== strpos( $classes, 'wpglobus' );

			return ! $is_app_download && ! $is_language_switcher;
		} ) );
	}

	public function add_additional_class_on_li( $classes, $item, $args ) {
		if ( isset( $args->add_li_class ) ) {
			$classes[] = $args->add_li_class;
		}

		return $classes;
	}
}

new Filters();

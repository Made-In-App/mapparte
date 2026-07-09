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
		add_filter( 'nav_menu_css_class', [ $this, 'add_additional_class_on_li' ], 10, 3 );
		add_filter( 'wp_is_application_passwords_available', '__return_true' );
		add_filter( 'body_class', [ $this, 'my_body_classes' ] );
		add_filter( 'use_block_editor_for_post', '__return_false' );
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

	public function add_additional_class_on_li( $classes, $item, $args ) {
		if ( isset( $args->add_li_class ) ) {
			$classes[] = $args->add_li_class;
		}

		return $classes;
	}
}

new Filters();

<?php

namespace Mapparte;

/**
 * Class Easy_Login
 *
 * @package Mapparte
 */
class Easy_Login{

	public function __construct() {
		add_filter( 'nav_menu_link_attributes', [ $this, 'enable_modal_login_redirect' ], 10, 2 );
		add_filter( 'xoo_sl_new_customer_data', [ $this, 'set_sl_customer_role' ], 10, 1 );
		// Login social via admin-ajax: evita cache che mangia Set-Cookie; host ajax = host pagina (www/non-www).
		add_action( 'wp_ajax_nopriv_xoo_sl_fb_data', [ $this, 'social_ajax_no_cache' ], 0 );
		add_action( 'wp_ajax_xoo_sl_fb_data', [ $this, 'social_ajax_no_cache' ], 0 );
		add_filter( 'admin_url', [ $this, 'match_admin_ajax_host_to_request' ], 10, 3 );
	}

	/**
	 * Risposta AJAX login social senza cache (CDN/proxy a volte non applicano i cookie al client).
	 */
	public function social_ajax_no_cache() {
		if ( ! headers_sent() ) {
			nocache_headers();
		}
	}

	/**
	 * Se il sito è aperto come www.… ma admin_url() punta a … senza www (o viceversa), la richiesta AJAX
	 * è “cross-host”: il browser può ignorare i Set-Cookie della risposta. Allinea admin-ajax all’host corrente.
	 *
	 * @param string $url     URL completo.
	 * @param string $path    Es. admin-ajax.php
	 * @param int    $blog_id Blog (multisite).
	 */
	public function match_admin_ajax_host_to_request( $url, $path, $blog_id ) {
		if ( 'admin-ajax.php' !== $path || is_admin() ) {
			return $url;
		}
		if ( empty( $_SERVER['HTTP_HOST'] ) ) {
			return $url;
		}
		$current = strtolower( preg_replace( '/:\d+$/', '', sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) ) );
		$parts   = wp_parse_url( $url );
		if ( empty( $parts['host'] ) ) {
			return $url;
		}
		$url_host = strtolower( $parts['host'] );
		if ( $url_host === $current ) {
			return $url;
		}
		$scheme = is_ssl() ? 'https' : 'http';
		$path_q = isset( $parts['path'] ) ? $parts['path'] : '/wp-admin/admin-ajax.php';
		$new    = $scheme . '://' . $current . $path_q;
		if ( ! empty( $parts['query'] ) ) {
			$new .= '?' . $parts['query'];
		}
		if ( ! empty( $parts['fragment'] ) ) {
			$new .= '#' . $parts['fragment'];
		}

		return $new;
	}

	public function set_sl_customer_role( $new_customer_data ) {
		$new_customer_data['role'] = 'contributor';
		return $new_customer_data;
	}

	public function enable_modal_login_redirect( $atts, $item ) {
		if (!is_user_logged_in()) {
			if (in_array( 'xoo-el-login-tgr', $item->classes ) ) {
				$atts['class'] = "xoo-el-login-tgr";
				$atts['data-redirect'] = $atts['href'];
			}
		}
		return $atts;
	}

}

new Easy_Login();



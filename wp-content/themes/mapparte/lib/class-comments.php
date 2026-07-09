<?php

namespace Mapparte;

/**
 * Class Comments
 *
 * @package Mapparte
 */
class Comments {

	public function __construct() {
		add_action( 'pre_ping', [ $this, 'internal_pingbacks' ] );
		add_filter( 'wp_headers', [ $this, 'x_pingback' ] );
		add_filter( 'bloginfo_url', [ $this, 'pingback_url' ] );
		add_filter( 'bloginfo', [ $this, 'pingback_url' ] );
		add_filter( 'xmlrpc_enabled', [ $this, '__return_false' ] );
		add_filter( 'xmlrpc_methods', [ $this, 'xmlrpc_methods' ] );
		add_action( 'admin_menu', [ $this, 'remove_wp_admin_comments' ] );
		add_action( 'wp_before_admin_bar_render', [ $this, 'mapparte_admin_bar_render' ] );
		add_filter( 'comment_row_actions', [ $this, 'remove_row_actions' ] );
		add_filter( 'bulk_actions-edit-comments', [ $this, 'remove_row_actions' ] );
	}

	function internal_pingbacks( &$links ) { // Disable internal pingbacks
		foreach ( $links as $l => $link ) {
			if ( 0 === strpos( $link, get_option( 'home' ) ) ) {
				unset( $links[ $l ] );
			}
		}
	}

	function x_pingback( $headers ) { // Disable x-pingback
		unset( $headers['X-Pingback'] );
		return $headers;
	}


	function pingback_url( $output, $show = '' ) { // Remove pingback URLs
		if ( $show == 'pingback_url' ) $output = '';
		return $output;
	}

	function xmlrpc_methods( $methods ) { // Disable XML-RPC methods
		unset( $methods['pingback.ping'] );
		return $methods;
	}

	function remove_wp_admin_comments() {
		remove_menu_page( 'edit-comments.php' );
		remove_meta_box( 'commentsdiv', 'space', 'normal' );
		remove_meta_box( 'commentsdiv', 'booking', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'space', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'booking', 'normal' );
	}

	// Removes from admin bar
	function mapparte_admin_bar_render() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	}

	function remove_row_actions( $actions ) {
		return [];
	}


}

new Comments();
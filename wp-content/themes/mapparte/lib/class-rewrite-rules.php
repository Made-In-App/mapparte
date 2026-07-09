<?php

namespace Mapparte;

/**
 * Class for URL Rewrite Rules
 *
 * @package Mapparte
 */
class Rewrite_Rules{

	public function __construct() {
		add_action( 'init', array( $this, 'rewrite_rules' ) );
	}

	/**
	 * Add endpoints for my-bookings
	 */
	public function rewrite_rules() {
		add_rewrite_tag( '%mine%', '([^&]+)' );
		add_rewrite_rule( 'my-bookings/?$', 'index.php?post_type=booking&mine=1', 'top' );
		add_rewrite_rule( 'my-bookings/page/?([0-9]{1,})/?$', 'index.php?post_type=booking&paged=$matches[1]&mine=1', 'top' );

		add_rewrite_rule( 'my-spaces/?$', 'index.php?post_type=space&mine=1', 'top' );
		add_rewrite_rule( 'my-spaces/page/?([0-9]{1,})/?$', 'index.php?post_type=space&paged=$matches[1]&mine=1', 'top' );

		add_rewrite_tag( '%comment_id%', '([0-9]{1,})' );
		add_rewrite_rule( 'messaggi/inviati/?$', 'index.php?pagename=messaggi&mine=1', 'top' );
		add_rewrite_rule( 'messaggi/inviati/page/?([0-9]{1,})/?$', 'index.php?pagename=messaggi&paged=$matches[1]&mine=1', 'top' );
		add_rewrite_rule( 'messaggi/?([0-9]{1,})/?$', 'index.php?pagename=messaggi&comment_id=$matches[1]', 'top' );
		add_rewrite_rule( 'messaggi/page/?([0-9]{1,})/?$', 'index.php?pagename=messaggi&paged=$matches[1]', 'top' );
	}

}

new Rewrite_Rules();
<?php
require get_stylesheet_directory() . '/lib/class-post-types.php';
require get_stylesheet_directory() . '/lib/class-rewrite-rules.php';
require get_stylesheet_directory() . '/lib/class-taxonomies.php';
require get_stylesheet_directory() . '/lib/class-admin.php';
require get_stylesheet_directory() . '/lib/class-core.php';
require get_stylesheet_directory() . '/lib/class-push-notifications.php';
require get_stylesheet_directory() . '/lib/class-email-notifications.php';
require get_stylesheet_directory() . '/lib/class-frontend-utils.php';
require get_stylesheet_directory() . '/lib/class-filters.php';
require get_stylesheet_directory() . '/lib/class-frontend-scripts.php';
require get_stylesheet_directory() . '/lib/class-messages.php';
require get_stylesheet_directory() . '/lib/class-edit-space.php';
require get_stylesheet_directory() . '/lib/class-media-upload.php';
require get_stylesheet_directory() . '/lib/rest-api/v1/class-rest-api.php';
require get_stylesheet_directory() . '/lib/class-voucher.php';
require get_stylesheet_directory() . '/lib/class-space.php';
require get_stylesheet_directory() . '/lib/plugins/class-acf.php';
require get_stylesheet_directory() . '/lib/plugins/class-easy-login.php';
require get_stylesheet_directory() . '/lib/class-script-cache-bust.php';
require get_stylesheet_directory() . '/lib/class-magazine-cache.php';
require get_stylesheet_directory() . '/lib/class-comments.php';
require get_stylesheet_directory() . '/lib/class-bookings.php';
require get_stylesheet_directory() . '/lib/class-sponsorship.php';
require get_stylesheet_directory() . '/lib/class-edit-profile.php';
require get_stylesheet_directory() . '/stripe/Init.php';

// custom GLOBAL functions
function pre( $text, $stop = false ) {
	echo "<pre>";
	print_r( $text );
	echo "</pre>";
	if ( $stop ) {
		exit;
	}
}

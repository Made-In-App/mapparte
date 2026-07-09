<?php
/**
 * The template for displaying all spaces
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Blank Canvas
 * @since 1.0.0
 */
get_header();
if ( isset( $wp_query->query_vars['mine'] ) && $wp_query->query_vars['mine'] ) {
	get_template_part( 'template-parts/admin/my-spaces' );
} else {
	get_template_part( 'template-parts/search/panel', '', $_REQUEST );
	if ( is_user_logged_in() ) {
		get_template_part( 'template-parts/search/featured-logged', '', $_REQUEST );
	} else {
		get_template_part( 'template-parts/search/featured', '', $_REQUEST );
	}
	get_template_part( 'template-parts/footer' );
}
get_footer();
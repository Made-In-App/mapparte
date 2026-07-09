<?php
/**
 * Template Name: il tuo spazio
 */
get_header();
?>
<!--my space section start-->
<section class="my-space-wrapper">
<?php
global $page_slug;
$queried_object = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
$page_slug = $queried_object->guid;

if (is_user_logged_in()){
	get_template_part( 'template-parts/edit-space/logged' );
}else{
	get_template_part( 'template-parts/edit-space/not-logged' );
}
?>
</section>
<!--my space section end-->
<?php
get_footer();

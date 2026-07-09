<?php
/**
 * Template Name: Inserisci il tuo spazio
 */
get_header();
?>
<!-- Inserire controllo solo pagine backend -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/cms.css">
<!--my space section start-->
<section class="my-space-wrapper">
<?php
global $wp, $page_slug;
$page_slug = home_url( $wp->request );

if ( is_user_logged_in() ) :
	get_template_part( 'template-parts/edit-space/logged' );
else :
	get_template_part( 'template-parts/edit-space/not-logged' );
endif;
?>
</section>
<!--my space section end-->
<?php
get_footer();

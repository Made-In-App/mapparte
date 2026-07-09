<?php
/**
 * The template for displaying all booking
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Blank Canvas
 * @since 1.0.0
 */
get_header();
?>
<?php get_template_part( 'template-parts/admin/mobile-button' ); ?>
    <!--my space section start-->
    <section class="booking-wrapper ">
        <div class="container-fluid">
            <div class="row">
				<?php get_template_part( 'template-parts/admin/sidebar' );
				if ( isset( $wp_query->query_vars['mine'] ) && $wp_query->query_vars['mine'] ) {
					get_template_part( 'template-parts/admin/my-bookings' );
				} else {
					get_template_part( 'template-parts/admin/received-bookings' );
				}
				?>
            </div>
        </div>
    </section>
    <!--my space section end-->
<?php get_template_part( 'template-parts/magazine/pagination' ); ?>
<?php
get_footer();


<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Blank Canvas
 * @since 1.0.0
 */

get_header();
get_template_part( 'template-parts/magazine/breadcrumb' );
?>
<!--magazine section start-->
<section class="magazine-wrapper">
    <div class="container gx-5">
        <h1 class="magazine-section-ttl">Mapparte Magazine</h1>
        <?php get_template_part( 'template-parts/magazine/search' );?>
    </div>
</section>
<!--magazine section end-->
<?php
get_template_part( 'template-parts/magazine/lists' );
get_template_part( 'template-parts/newsletter' );
get_template_part( 'template-parts/footer' );
get_footer();

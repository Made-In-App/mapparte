<?php
/**
 * The template for displaying single booking
 */
get_header();
?>
    <!--my space section start-->
    <section class="booking-wrapper new-booking-wrapper">
        <div class="container-fluid">
            <div class="row">
				<?php if ( is_user_logged_in() ) : ?>
					<?php
                    get_template_part( 'template-parts/admin/sidebar' );
					get_template_part( 'template-parts/admin/booking-details' );
				endif; ?>
            </div>
        </div>
    </section>
    <!--my space section end-->
<?php
get_footer();


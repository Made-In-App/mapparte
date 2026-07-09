<?php
/**
 * Template Name: Attiva Sponsorizzazione
 */
get_header();
?>
<?php get_template_part( 'template-parts/admin/mobile-button' ); ?>
	<!--my space section start-->
	<section class="booking-wrapper new-booking-wrapper">
		<div class="container-fluid">
			<div class="row">
				<?php if ( is_user_logged_in() ) : ?>
					<?php get_template_part( 'template-parts/admin/sidebar' );?>
					<?php if ( isset( $_REQUEST['space_id'] ) ) get_template_part('template-parts/admin/subscription'); ?>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<!--my space section end-->
<?php
get_footer();

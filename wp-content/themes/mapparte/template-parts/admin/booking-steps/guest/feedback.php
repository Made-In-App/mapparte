<div class="col-xl-10 col-md-12">
	<div class="col-md-8">
		<h6 class="booking-subttl"><?php echo __("Richiesta di prenotazione per","mapparte");?></h6>
		<h1 class="booking-ttl"><?php echo esc_html( $args['spaceTitle'] ); ?> <!--i class="fas fa-arrow-right"--></i></h1>
	</div>
	<div class="col-md-12">
		<div class="row mx-0 align-items-center">
			<div class="col-md-8">
				<ul class="status-wrapper feedback-status-wrapper row align-items-center">
					<li class="col-sm-3">
						<p><?php echo \Mapparte\Frontend_Utils::format_date_time( $args['date'] ); ?></p>
						<h6><?php echo __("COMPLETATA","mapparte");?></h6>
					</li>
					<li class="col-sm-6 col-10">
						<?php if ( !get_post_meta( $post->ID, '_rating_guest', true ) ) : ?>
                            <?php echo __("Lascia un feedback","mapparte");?>
						<?php endif; ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<?php get_template_part( "template-parts/admin/booking-steps/guest/details", '', $args ); ?>
</div>

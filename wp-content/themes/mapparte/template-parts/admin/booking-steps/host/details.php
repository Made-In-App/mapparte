<?php
$guest      = get_userdata( $post->post_author );
$activity   = get_term_by( 'id', $args['planningTo'], 'activity' );
$planningTo = ( isset( $activity->name ) ) ? $activity->name : "N.D.";
?>
<div class="col-md-12 booking-details">
    <h6 class="booking-subttl"><?php echo __("DETTAGLI DELLA RICHIESTA","mapparte");?></h6>
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-4">
                    <h6 class="booking-detail-ttl"><?php echo __("ID prenotazione","mapparte");?></h6>
                </div>
                <div class="col-8">
                    <p class="booking-detail-content"><?php echo esc_html( $post->ID ); ?></p>
                </div>
                <div class="col-4">
                    <h6 class="booking-detail-ttl"><?php echo __("utente","mapparte");?></h6>
                </div>
                <div class="col-8">
                    <p class="booking-detail-content"><?php echo esc_html( $guest->data->display_name ); ?></p>
                    <div class="review">
						<?php
						if ( $post->post_status === 'feedback' && !get_post_meta( $post->ID, '_rating_host', true ) ) {
							include( 'rating.php' );
						} else {
							include( 'rating-host.php' );
                        }
						?>
                    </div>
                </div>
                <div class="col-4">
                    <h6 class="booking-detail-ttl">checkin</h6>
                </div>
                <div class="col-8">
                    <p class="booking-detail-content"><?php echo \Mapparte\Frontend_Utils::format_date_time( $args['fromDateTime'] ); ?></p>
                </div>
                <div class="col-4">
                    <h6 class="booking-detail-ttl">checkout</h6>
                </div>
                <div class="col-8">
                    <p class="booking-detail-content"><?php echo \Mapparte\Frontend_Utils::format_date_time( $args['toDateTime'] );; ?></p>
                </div>
                <div class="col-4">
                    <h6 class="booking-detail-ttl"><?php echo __("Prezzo","mapparte");?></h6>
                </div>
                <div class="col-8">
                    <p class="booking-detail-content"><?php echo esc_html( $args['finalPrice'] ); ?> euro</p>
                </div>
                <div class="col-4">
                    <h6 class="booking-detail-ttl"><?php echo __("Attività prevista","mapparte");?></h6>
                </div>
                <div class="col-8">
                    <p class="booking-detail-content"><?php echo esc_html( $planningTo ); ?></p>
                </div>
                <div class="col-4">
                    <h6 class="booking-detail-ttl"><?php echo __("Numero di ospiti previsti","mapparte");?></h6>
                </div>
                <div class="col-8">
                    <p class="booking-detail-content"><?php echo esc_html( $args['guests'] ); ?></p>
                </div>
                <div class="col-4">
                    <h6 class="booking-detail-ttl"><?php echo __("Policy di cancellazione","mapparte");?></h6>
                </div>
                <div class="col-8">
                    <p class="booking-detail-content"><a href="<?php echo site_url( '/termini-e-condizioni-duso' ); ?>"
                                                         target="_blank"><?php echo __("Leggi","mapparte");?></a></p>
                </div>
				<?php get_template_part( "template-parts/admin/booking-steps/booking-messages" ); ?>
            </div>
        </div>
		<?php if ( 'feedback' === $post->post_status && !get_post_meta( $post->ID, '_rating_host', true ) ) : ?>
            <div class="col-md-4 feedback-note"><?php echo __("Puoi lasciare un feedback sul cliente","mapparte");?></div>
		<?php endif; ?>
    </div>
</div>
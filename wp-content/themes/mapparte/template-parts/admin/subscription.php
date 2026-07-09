<?php
$sponsored_expiry_date = get_post_meta( $_REQUEST['space_id'], 'sponsored_expired', true );
$sponsored_type        = get_post_meta( $_REQUEST['space_id'], 'sponsored_type', true );
$plan                  = ( $sponsored_type ) ? \Mapparte\Sponsorship::get_plan( $sponsored_type ) : [];
?>
<div class="col-md-10 booking-details-section">
	<div class="header-top">
		<div class="row align-items-center justify-content-between mx-0">
			<div class="col-md-6 col-6 header-left">
				<a href="javascript:window.history.back();">
					<i class="fas fa-long-arrow-alt-left me-2"></i>
					<?php echo esc_html__( 'INDIETRO', 'mapparte' ); ?>
				</a>
			</div>
		</div>
	</div>
	<?php if ( $sponsored_expiry_date && $sponsored_expiry_date >= date( 'Y-m-d H:i:s' ) && ! empty( $plan ) ) : ?>
	<?php if ( isset( $_REQUEST['success'] ) && $_REQUEST['success'] === 'success' ) : ?>
        <h6 class="booking-subttl"><?php echo esc_html__( 'Congratulazioni! Piano acquistato con successo!', 'mapparte' ); ?></h6>
		<?php endif; ?>
        <div class="row align-items-center col-lg-10 mx-auto">
            <div class="col-md-2">
                <p class="active-plan-ttl"><?php echo esc_html( get_the_title( $_REQUEST['space_id'] ) ); ?></p>
            </div>
            <div class="col-md-9">
                <ul class="status-wrapper row align-items-center">
                    <li class="col-sm-2">
                        <p><?php echo esc_html__( 'Piano Attivo', 'mapparte' ); ?></p>
                        <h6><?php echo esc_html( strtoupper( $plan['name'] ) ); ?></h6>
                    </li>
                    <li class="col-sm-10">
	                    <p><?php echo esc_html( $plan['desc'] ); ?></p>
                        <p class="status-note ps-0"><?php echo esc_html__( 'Valido fino', 'mapparte' ); ?>
                            al <?php echo esc_html( \Mapparte\Frontend_Utils::format_date_time( $sponsored_expiry_date ) ); ?> </p>
                    </li>
                </ul>
            </div>
        </div>
	<?php endif; ?>
	<?php if ( ! $sponsored_expiry_date || $sponsored_expiry_date < date( 'Y-m-d H:i:s' ) ) : ?>
        <h6 class="plan-wrapper-ttl"><?php echo esc_html__( 'Scegli un piano per', 'mapparte' ); ?> <?php echo esc_html( get_the_title( $_REQUEST['space_id'] ) ); ?></h6>
        <div class="row align-items-center plan-list col-lg-10 mx-auto">
            <div class="col-md-4 col-sm-8 col-10">
                <a href="<?php echo esc_url( get_home_url() . '/dettaglio-sponsorizzazione/?space_id=' . (int) $_REQUEST['space_id'] . '&plan=silver' ); ?>">
                    <div class="plan-wrapper">
                        <h6 class="plan-ttl">SILVER</h6>
                        <h4 class="plan-price"><span>€</span>15<span class="points">,00</span></h4>
                        <p class="status-note ps-0"><?php echo esc_html__( 'Incrementa la visibilità del tuo spazio per un mese', 'mapparte' ); ?></p>

                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-8 col-10">
                <a href="<?php echo esc_url( get_home_url() . '/dettaglio-sponsorizzazione/?space_id=' . (int) $_REQUEST['space_id'] . '&plan=gold' ); ?>">
                    <div class="plan-wrapper">
                        <h6 class="plan-ttl">GOLD</h6>
                        <h4 class="plan-price"><span>€</span>150<span class="points">,00</span></h4>
                        <p class="status-note ps-0"><?php echo esc_html__( 'Incrementa la visibilità del tuo spazio per un anno', 'mapparte' ); ?></p>
                    </div>
                </a>
            </div>
        </div>
	<?php endif; ?>
</div>

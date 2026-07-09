<?php
$sponsored_expiry_date = get_post_meta( $_REQUEST['space_id'], 'sponsored_expired', true );
$sponsored_type        = get_post_meta( $_REQUEST['space_id'], 'sponsored_type', true );
$plan                  = \Mapparte\Sponsorship::get_plan( $_REQUEST['plan'] );
if ( ! $sponsored_expiry_date || $sponsored_expiry_date < date( 'Y-m-d H:i:s' ) ) :
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
	<?php if ( ! empty( $plan ) && get_post( (int) $_REQUEST['space_id'] ) ) : ?>
        <div class="row mx-0">
            <div class="col-xl-10 col-md-12">

                <h6 class="booking-subttl"><?php echo esc_html__( 'Acquisto sponsorizzazione', 'mapparte' ); ?></h6>
                <h1 class="booking-ttl"><?php echo esc_html( get_the_title( (int) $_REQUEST['space_id'] ) ); ?></h1>

				<?php \Mapparte\Stripe\Utils::stripe_sponsorship_form( (int) $_REQUEST['space_id'], $plan ); ?>

            </div>
        </div>
	<?php endif; ?>
</div>
<?php endif; ?>

<?php
$space_id              = isset( $_REQUEST['space_id'] ) ? (int) $_REQUEST['space_id'] : 0;
$plan_name             = isset( $_REQUEST['plan'] ) ? sanitize_key( wp_unslash( $_REQUEST['plan'] ) ) : '';
$sponsored_expiry_date = get_post_meta( $space_id, 'sponsored_expired', true );
$sponsored_type        = get_post_meta( $space_id, 'sponsored_type', true );
$plan                  = \Mapparte\Sponsorship::get_plan( $plan_name );
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
	<?php if ( ! empty( $plan ) && get_post( $space_id ) ) : ?>
        <div class="row mx-0">
            <div class="col-xl-10 col-md-12">

                <h6 class="booking-subttl"><?php echo esc_html__( 'Sponsorizzazione', 'mapparte' ); ?></h6>
                <h1 class="booking-ttl"><?php echo esc_html( get_the_title( $space_id ) ); ?></h1>
                <p class="booking-note">
					<?php echo esc_html__( 'Contattaci via email a', 'mapparte' ); ?>
                    <a href="mailto:info@mapparte.com">info@mapparte.com</a>
					<?php echo esc_html__( 'per un’offerta personalizzata. Scrivi nel messaggio: Nome dello spazio - tipo di piano di sponsorizzazione (silver o gold) - data di attivazione - e i tuoi dati anagrafici compresa la residenza e il codice fiscale. Ti contatteremo entro poche ore. Grazie', 'mapparte' ); ?>
                </p>

            </div>
        </div>
	<?php endif; ?>
</div>
<?php endif; ?>

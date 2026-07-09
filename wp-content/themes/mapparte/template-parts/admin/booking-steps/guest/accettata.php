<div class="col-xl-10 col-md-12">
    <div class="col-md-8">
        <h6 class="booking-subttl"><?php echo __("Richiesta di prenotazione per","mapparte");?></h6>
        <h1 class="booking-ttl"><?php echo esc_html( $args['spaceTitle'] ); ?> <!--i class="fas fa-arrow-right"--></i></h1>
    </div>
    <div class="col-md-12">
        <div class="row mx-0 align-items-center">
            <div class="col-md-8">
                <ul class="status-wrapper accepted-status-wrapper row align-items-center">
                    <li class="col-sm-3">
                        <p><?php echo \Mapparte\Frontend_Utils::format_date_time( $args['date'] ); ?></p>
                        <h6><?php echo __("ACCETTATA","mapparte");?></h6>
                    </li>
                    <li class="col-sm-5 col-6">
                    <?php echo __("In attesa del pagamento","mapparte");?>
                    </li>
                    <li class="col-sm-4 col-6">
                        <i class="fas fa-clock"></i>
                        <?php echo __("2 giorni rimanenti","mapparte");?>
                    </li>
                </ul>
            </div>
            <div class="col-md-4 status-note"><?php echo __("Se l’utente non prenoterà entro la scadenza dei giorni rimanenti la richiesta verrà automaticamente marcata come “cancellata”","mapparte");?>
            </div>
        </div>
    </div>

        <div class="col-md-12 status-form">
            <p class="form-ttl"><?php echo __("EFFETTUA IL PAGAMENTO SU STRIPE","mapparte");?></p>
            <div class="col-md-12">
                <div class="row mx-0 px-0 align-items-center submit-btns">

	                <?php \Mapparte\Stripe\Utils::stripe_checkout_form( $args, $post ); ?>

                    <form id="delete-form" method="post" action="<?php echo get_the_permalink() ?>"
                          class="row align-items-center">
                        <div class="col-md-8">
                            <div class="row mx-0 px-0 status-note align-items-center submit-btns">
                                <div class="col-9 ps-0">
                                    <button type="submit" id="status" name="status" value="<?php echo __("CANCELLATA","mapparte");?>" class="btn btn-secondary"><?php echo __("ANNULLA LA PRENOTAZIONE","mapparte");?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
	<?php get_template_part( "template-parts/admin/booking-steps/guest/details", '', $args ); ?>
</div>

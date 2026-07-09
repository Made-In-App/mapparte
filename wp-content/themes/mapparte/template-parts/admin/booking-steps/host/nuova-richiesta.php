<div class="col-xl-10 col-md-12">
    <div class="col-md-8">
        <h6 class="booking-subttl"><?php echo __("Richiesta di prenotazione per","mapparte");?></h6>
        <h1 class="booking-ttl"><?php echo esc_html( $args['spaceTitle'] ); ?> <!--i class="fas fa-arrow-right"--></i></h1>
    </div>
    <div class="col-md-12">
        <div class="row mx-0 align-items-center">
            <div class="col-md-8">
                <ul class="status-wrapper new-status-wrapper row align-items-center">
                    <li class="col-sm-3">
                        <p><?php echo \Mapparte\Frontend_Utils::format_date_time( $args['date'] ); ?></p>
                        <h6><?php echo __("NUOVA","mapparte");?></h6>
                    </li>
                    <li class="col-sm-5 col-6">
                    <?php echo __("In attesa di una tua risposta","mapparte");?>
                    </li>
                    <li class="col-sm-4 col-6">
                        <i class="fas fa-clock"></i>
                        <?php echo __("2 giorni rimanenti","mapparte");?>
                    </li>
                </ul>
            </div>
            <div class="col-md-4 status-note">
                <?php echo __("Ricorda che alla scadenza dei giorni rimanenti la richiesta verrà automaticamente marcata come “cancellata”","mapparte");?> 
            </div>
        </div>
    </div>
    <div class="col-md-8 status-form">
        <form method="post" action="<?php echo get_the_permalink() ?>" class="row mx-0">
            <div class="mb-3 px-0">
                                        <textarea id="message" name="message" class="form-control"
                                                  placeholder="<?php echo __("Invia un messaggio all’utente","mapparte");?>…"></textarea>
            </div>
            <div class="row mx-0 px-0 align-items-center justify-content-center submit-btns">
                <div class="col-6 ps-0">
                    <button id="status" name="status" type="submit" value="<?php echo __("accettata","mapparte");?>" class="btn btn-outline-primary">
                    <?php echo __("ACCETTA LA PRENOTAZIONE","mapparte");?>
                    </button>
                </div>
                <div class="col-6 pe-0">
                    <button id="status" name="status" type="submit" value="<?php echo __("CANCELLATA","mapparte");?>" class="btn btn-secondary"><?php echo __("RIFIUTA LA PRENOTAZIONE","mapparte");?>
                    </button>
                </div>
            </div>
        </form>
    </div>
	<?php get_template_part( "template-parts/admin/booking-steps/host/details", '', $args ); ?>
</div>
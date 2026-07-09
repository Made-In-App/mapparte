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
    <?php get_template_part( "template-parts/admin/booking-steps/host/details", '', $args ); ?>
</div>

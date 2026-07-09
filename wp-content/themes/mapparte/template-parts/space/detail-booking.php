<div class="col-md-5">
    <div name="booking-form" id="booking-form"  class="booking-form-wrapper d-sm-block">
		<?php
        if ( $args['status'] !== 'publish') {
	        echo '<p>Prenotazione non disponibile.</p>';
        } elseif ( $args['author'] === get_current_user_id() ) {
	        echo '<p>Non puoi prenotare un tuo spazio.</p>';
        } else {
         get_template_part( 'template-parts/space/booking-form', '', $args );
        }
        ?>
    </div>
    <div class="row mx-0 d-sm-none mbl-action-btn">
		<?php if ( ! is_user_logged_in() ) : ?>
            <button type="button" class="btn btn-secondary col-6"><?php echo __("invia richiesta prenotazione","mapparte");?>
            </button>
            <button type="button"
                    class="btn btn-secondary-outline col-6"><?php echo __("Contatta l'host","mapparte");?>
            </button>
		<?php else : ?>
            <button id="send-booking-request" name="send-booking-request" type="button"
                    class="btn btn-secondary col-6"><?php echo __("invia richiesta prenotazione","mapparte");?>
            </button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#message-popup"
                    class="btn btn-secondary-outline col-6"><?php echo __("Contatta l'host","mapparte");?>
            </button>
		<?php endif; ?>
    </div>
</div>
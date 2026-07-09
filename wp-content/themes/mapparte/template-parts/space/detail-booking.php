<div class="col-md-5">
    <div name="booking-form" id="booking-form"  class="booking-form-wrapper d-sm-block">
		<?php
        if ( $args['status'] !== 'publish') {
	        echo '<p>Prenotazione non disponibile.</p>';
        } elseif ( $args['author'] === get_current_user_id() ) {
	        echo '<p>Non puoi prenotare un tuo spazio.</p>';
        } elseif ( ! empty( $args['hide_prices'] ) ) {
	        if ( ! is_user_logged_in() ) {
		        ?>
                <a data-redirect="<?php echo get_permalink(); ?>" href="<?php echo get_permalink(); ?>"
                   class="xoo-el-login-tgr">
                    <button type="button" class="btn btn-secondary-outline"><?php echo __("Richiedi un contatto con lo spazio","mapparte");?></button>
                </a>
		        <?php
	        } else {
		        ?>
                <button type="button" data-bs-toggle="modal" data-bs-target="#message-popup"
                        class="btn btn-secondary-outline"><?php echo __("Richiedi un contatto con lo spazio","mapparte");?></button>
		        <?php
	        }
        } else {
         get_template_part( 'template-parts/space/booking-form', '', $args );
        }
        ?>
    </div>
    <div class="row mx-0 d-sm-none mbl-action-btn">
		<?php if ( ! is_user_logged_in() ) : ?>
			<?php if ( empty( $args['hide_prices'] ) ) : ?>
                <button type="button" class="btn btn-secondary col-6"><?php echo __("invia richiesta prenotazione","mapparte");?>
                </button>
			<?php endif; ?>
            <button type="button"
                    class="btn btn-secondary-outline <?php echo empty( $args['hide_prices'] ) ? 'col-6' : 'col-12'; ?>"><?php echo empty( $args['hide_prices'] ) ? __("Contatta l'host","mapparte") : __("Richiedi un contatto con lo spazio","mapparte");?>
            </button>
		<?php else : ?>
			<?php if ( empty( $args['hide_prices'] ) ) : ?>
                <button id="send-booking-request" name="send-booking-request" type="button"
                        class="btn btn-secondary col-6"><?php echo __("invia richiesta prenotazione","mapparte");?>
                </button>
			<?php endif; ?>
            <button type="button" data-bs-toggle="modal" data-bs-target="#message-popup"
                    class="btn btn-secondary-outline <?php echo empty( $args['hide_prices'] ) ? 'col-6' : 'col-12'; ?>"><?php echo empty( $args['hide_prices'] ) ? __("Contatta l'host","mapparte") : __("Richiedi un contatto con lo spazio","mapparte");?>
            </button>
		<?php endif; ?>
    </div>
</div>

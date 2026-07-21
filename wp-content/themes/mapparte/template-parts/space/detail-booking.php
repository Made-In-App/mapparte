<div class="col-md-5">
	    <div name="booking-form" id="booking-form" class="booking-form-wrapper d-none d-sm-block">
		<?php
		$contact_only = ! empty( $args['hide_prices'] ) || ! empty( $args['hide_availability'] );
        if ( $args['status'] !== 'publish') {
	        echo '<p>Prenotazione non disponibile.</p>';
        } elseif ( $args['author'] === get_current_user_id() ) {
	        echo '<p>Non puoi prenotare un tuo spazio.</p>';
	        } elseif ( $contact_only ) {
		        $contact_attributes = is_user_logged_in()
			        ? 'data-bs-toggle="modal" data-bs-target="#message-popup"'
			        : sprintf( 'data-redirect="%s"', esc_url( get_permalink() ) );
		        ?>
	            <button type="button" <?php echo $contact_attributes; ?>
	                    class="btn btn-primary contact-host-button<?php echo is_user_logged_in() ? '' : ' xoo-el-login-tgr'; ?>"
	                    data-redirect="<?php echo esc_url( get_permalink() ); ?>">
	                <i class="far fa-comment-dots" aria-hidden="true"></i>
	                <span><?php echo esc_html__( "Richiedi un contatto con l'host", 'mapparte' ); ?></span>
	                <small><?php echo esc_html__( 'Apri la messaggistica', 'mapparte' ); ?></small>
	            </button>
		        <?php
        } else {
         get_template_part( 'template-parts/space/booking-form', '', $args );
        }
        ?>
    </div>
    <div class="row mx-0 d-sm-none mbl-action-btn">
		<?php if ( ! is_user_logged_in() ) : ?>
			<?php if ( ! $contact_only ) : ?>
                <button type="button" class="btn btn-secondary col-6"><?php echo __("invia richiesta prenotazione","mapparte");?>
                </button>
			<?php endif; ?>
	            <button type="button" data-redirect="<?php echo esc_url( get_permalink() ); ?>"
	                    class="btn btn-secondary-outline xoo-el-login-tgr <?php echo ! $contact_only ? 'col-6' : 'col-12 contact-host-button'; ?>"><?php echo ! $contact_only ? __("Per info contatta l'host","mapparte") : __("Richiedi un contatto con l'host","mapparte");?>
            </button>
		<?php else : ?>
			<?php if ( ! $contact_only ) : ?>
                <button id="send-booking-request" name="send-booking-request" type="button"
                        class="btn btn-secondary col-6"><?php echo __("invia richiesta prenotazione","mapparte");?>
                </button>
			<?php endif; ?>
            <button type="button" data-bs-toggle="modal" data-bs-target="#message-popup"
	                    class="btn btn-secondary-outline <?php echo ! $contact_only ? 'col-6' : 'col-12 contact-host-button'; ?>"><?php echo ! $contact_only ? __("Per info contatta l'host","mapparte") : __("Richiedi un contatto con l'host","mapparte");?>
            </button>
		<?php endif; ?>
    </div>
</div>

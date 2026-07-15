<?php if ( isset( $args['price_hour'] ) && $args['price_hour'] ) : ?>
    <h5 class="booking-form-ttl"><?php echo sprintf(__( 'A partire da %s € l\'ora', 'mapparte' ),$args["price_hour"])?></span></h5>
<?php endif; ?>
<form class="row booking-form">
    <div class="spinner">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/spinner.gif" alt="spinner"/>
    </div>
    <div class="mb-3 col-12 px-0">
        <label for="pianificando" class="form-label"><?php echo __("Cosa stai pianificando?","mapparte");?></label>
		<?php \Mapparte\Frontend_Utils::get_taxonomy_select( 'activity', __('Cosa stai pianificando?',"mapparte"), $first_option = __('Seleziona un\'attività',"mapparte") ) ?>
    </div>
    <div class="time-slot-wrapper">
        <div class="mb-3 time-slot-tile px-0">
            <label for="date" class="form-label"><?php echo __("Data","mapparte");?></label>
            <input type="date" class="form-control start_date" id="start_date" min="<?php echo date( "Y-m-d" ) ?>">
        </div>
        <div class="mb-3 time-slot-tile px-0">
            <label for="date" class="form-label"><?php echo __("Orario inizio","mapparte");?></label>
            <select class="form-control form-select start_time" id="start_time" aria-label="Default select example">

            </select>
        </div>
        <div class="mb-3 time-slot-tile d-none px-0">
            <label for="date" class="form-label"><?php echo __("Data","mapparte");?></label>
            <input type="date" class="form-control end_date" id="end_date">
        </div>
        <div class="mb-3 time-slot-tile px-0">
            <label for="date" class="form-label"><?php echo __("Orario fine","mapparte");?></label>
            <select class="form-control form-select end_time" id="end_time" aria-label="Default select example">

            </select>
        </div>
    </div>
    <div class="col-12 pe-0 form-check">
        <input type="checkbox" class="form-check-input more-days" id="more-days">
        <label class="form-check-label" for="more-days"><?php echo __("più giorni","mapparte");?></label>
        <p class="notice"></p>
    </div>
    <div class="col-sm-8 mb-3 px-0">
        <label for="floatingInput" class="form-label"><?php echo __("Numero ospiti","mapparte");?></label>
        <div class="input-group">
            <input type="number" name="quantity" max="" class="form-control quantity" id="floatingInput"
                   placeholder="0">
            <input type="button" value="+" class="button-plus" data-field="quantity">
            <input type="button" value="-" class="button-minus" data-field="quantity">
        </div>
    </div>
    <div class="mb-3 col-12 px-0">
        <label for="coupon" class="form-label"><?php echo __("Possiedi un coupon?","mapparte");?></label>
        <input type="text" class="form-control coupon" id="coupon">
    </div>
    <div class="col-12 px-0 estimeted-price-wrapper">
        <h4 class="estimeted-price-ttl hide"><?php echo __("Prezzo stimato","mapparte");?></h4>
        <div class="estimeted-price">
            <p class="hours"></p>
            <p class="discount"></p>
            <h4></h4>
        </div>
    </div>
    <div class="mb-3 col-12 px-0">
        <label for="message" class="form-label"><?php echo __("Se hai bisogno di conoscere ulteriori dettagli scrivi qui il tuo messaggio per l’Host","mapparte");?></label>
        <textarea class="form-control message" name="message" id="message"></textarea>
    </div>
    <div class="mb-3 col-12 px-0 action-btn">
		<?php if ( ! is_user_logged_in() ) : ?>
            <a data-redirect="<?php echo get_permalink(); ?>" id="open-modal-login"
               href="<?php echo get_permalink(); ?>" class="xoo-el-login-tgr">
                <button type="button" class="btn btn-secondary"><?php echo __("invia richiesta prenotazione","mapparte");?>
                </button>
            </a>
            <a data-redirect="<?php echo get_permalink(); ?>" href="<?php echo get_permalink(); ?>"
               class="xoo-el-login-tgr">
                <button type="button" class="btn btn-secondary-outline"><?php echo __("Per info contatta l'host","mapparte");?>
                </button>
            </a>
		<?php else : ?>
            <button type="button" class="btn btn-secondary send" id="send"><?php echo __("invia richiesta prenotazione","mapparte");?>
            </button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#message-popup"
                    class="btn btn-secondary-outline"><?php echo __("Per info contatta l'host","mapparte");?>
            </button>
		<?php endif; ?>
    </div>
</form>

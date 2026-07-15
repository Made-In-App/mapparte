<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $current_step, $space_data, $page_slug, $space_id, $space_terms_error;

if ( ! $space_id ) {
	$space_id = isset( $_REQUEST['space_id'] ) ? (int) $_REQUEST['space_id'] : 0;
	if ( $space_id && ! user_can( get_current_user_id(), 'edit_post', $space_id ) ) {
		return;
	}
}

if ( ! is_user_logged_in() ) {
	return;
}

$wizard       = \Mapparte\Edit_Space::setup_wizard();
$current_step = $wizard[0];
$next_step    = $wizard[1];
$prev_step    = $wizard[2];
$progress     = $wizard[3];
$max_steps    = $wizard[4];

$space_data = \Mapparte\Utils::return_space_data( $space_id );

if ( isset( $space_data['author'] ) && $space_data['author'] !== get_current_user_id() ) {
	return;
}

if ( isset( $space_data['status'] ) || ! $space_id ) {

	if ( is_int( $current_step ) && $current_step && $current_step == $max_steps + 1 ) :
		$approval_response = \Mapparte\Edit_Space::space_approval_request( $space_id, get_current_user_id(), $current_step );
		if ( is_wp_error( $approval_response ) ) {
			$space_terms_error = $approval_response->get_error_message();
			$current_step      = $max_steps;
			$next_step         = $max_steps + 1;
			$prev_step         = $max_steps - 1;
			$progress          = 100;
		}
	endif;

	if ( is_int( $current_step ) && $current_step && $current_step <= $max_steps ) :

		$space_id = \Mapparte\Edit_Space::save_space( $space_id, get_current_user_id(), $current_step );

		$space_id_query = ( $space_id ) ? sprintf( '&space_id=%d', $space_id ) : '';
		$next_url       = sprintf( "%s/?step=%d%s", $page_slug, $next_step, $space_id_query );
		?>
        <form action="<?php echo esc_attr( $next_url ); ?>" method="post" id="edit_space_form"
              name="edit_space_form">
            <div class="header-top">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-md-6 col-6 header-left">
							<?php
							if ( is_int( $prev_step ) && $prev_step > 0 ) :
								$back_url = sprintf( "%s?step=%d%s", $page_slug, $prev_step, $space_id_query );
								?>
                                <a id="prev" href="<?php echo esc_attr( $back_url ); ?>">
                                    <i class="fas fa-long-arrow-alt-left me-2"></i>
                                    <?php echo __("INDIETRO","mapparte"); ?>
                                </a>
							<?php endif ?>
                        </div>
                        <div class="col-md-6 col-6 text-end header-right">
                            <button id="annulla" href="<?php echo get_home_url(); ?>/my-spaces/" type="button"><?php echo __("Annulla","mapparte"); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
					<?php
					if ( $space_id && $current_step > 1 || $current_step === 1 ) {
						get_template_part( 'template-parts/edit-space/tabs' );
					}
					?>
                </div>
            </div>
            <div class="bottom-bar">
                <div class="row">
                    <div class="col-md-9 px-0 progress-bar-wrapper d-none d-md-block">
                        <div class="h-100 progress-bar" role="progressbar"
                             style="width: <?php echo esc_attr( $progress ); ?>%"
                             aria-valuenow="<?php echo esc_attr( $progress ); ?>"
                             aria-valuemin="0" aria-valuemax="100">
                            <h6>Step <?php echo esc_html( $current_step ); ?>
                                di <?php echo esc_html( $max_steps ); ?></h6>
							<?php global $step_name; ?>
                            <p><?php echo esc_html( $step_name ); ?></p>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-center justify-content-center justify-content-sm-end submit-btns">
	                    <input type="submit" id="save" name="save" value="salva e chiudi" class="btn btn-secondary" />
                        <input type="hidden" id="space_id" name="space_id" value="<?php echo esc_attr( $space_id ); ?>">
                        <input type="hidden" id="step" name="step" value="<?php echo esc_attr( $next_step ); ?>">
                        <input type="hidden" id="action" name="action" value="">
						<?php if ( $next_step <= $max_steps ) : ?>
                            <button type="submit" id="next" class="btn btn-primary"><?php echo __("Continua","mapparte"); ?></button>
						<?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
	<?php
	endif;
} else {
	?>
    <div class="col-md-6 form-wrapper">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="italian" role="tabpanel"
                 aria-labelledby="italian-tab">
                <h4 class="my-space-ttl"><?php echo __("Spazio non trovato","mapparte"); ?></h4>
                <p class="my-space-desc"><?php echo __("Unisciti alla community e dai la possibilità agli utenti di conoscerti e scoprire il tuo spazio.","mapparte"); ?></p>
            </div>
        </div>
    </div>
	<?php
}

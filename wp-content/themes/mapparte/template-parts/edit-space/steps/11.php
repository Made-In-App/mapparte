<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $step_name, $space_data, $space_terms_error;
$step_name = __('Richiesta approvazione spazio',"mapparte");
$space_url = get_post_meta( (int) $space_data['id'], 'space_url', true );
if ( 'draft' !== $space_data['status'] ) :
	echo "<script> jQuery(location).attr('href', '".$space_data['link']."'); </script>";
else :
?>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="italian" role="tabpanel"
         aria-labelledby="italian-tab">
        <h4 class="my-space-ttl"><?php echo esc_html($step_name); ?></h4>
          <div class="dimensioni-wrapper">
            <div class="row">
                <div class="col-sm-8">
                    <div>
                        <input type="hidden" id="request-approval" name="request-approval" value="1">
                        <?php wp_nonce_field( 'mapparte_space_approval_' . (int) $space_data['id'], 'space_approval_nonce' ); ?>
                        <?php if ( $space_terms_error ) : ?>
                            <p class="text-danger"><?php echo esc_html( $space_terms_error ); ?></p>
                        <?php endif; ?>
                        <div class="mb-4">
                            <label class="form-label" for="space_url">
								<?php echo esc_html__( 'Inserisci un link a sito o canale social del tuo spazio', 'mapparte' ); ?>
                            </label>
                            <input class="form-control" type="url" id="space_url" name="space_url"
                                   value="<?php echo esc_url( $space_url ); ?>" placeholder="https://">
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="space_terms_accepted"
                                   name="space_terms_accepted" value="1" required>
                            <label class="form-check-label" for="space_terms_accepted">
                                <?php echo esc_html__( 'Dichiaro di aver letto e compreso i', 'mapparte' ); ?>
                                <a href="<?php echo esc_url( home_url( '/termini-e-condizioni-duso/' ) ); ?>"
                                   target="_blank" rel="noopener noreferrer"><?php echo esc_html__( 'termini e le condizioni d’uso', 'mapparte' ); ?></a>
                            </label>
                        </div>
                        <p><a href="#" id="next" class="btn btn-primary"><?php echo __("Invia la richiesta di approvazione a mapparte","mapparte"); ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

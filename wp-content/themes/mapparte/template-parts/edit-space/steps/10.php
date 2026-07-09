<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $step_name, $space_data;
$step_name = __('Politica di cancellazione ed Ente gestore',"mapparte");

$cancel_policy = get_post_meta( $space_data['id'], 'cancel_policy' );
if ( isset( $cancel_policy[0] ) ) {
	$cancel_policy_it = \WPGlobus_Core::text_filter( $cancel_policy[0], 'it' );
	$cancel_policy_en = \WPGlobus_Core::text_filter( $cancel_policy[0], 'en' );
} else {
	$cancel_policy_it = '';
    $cancel_policy_en = '';
}
?>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="italian" role="tabpanel"
         aria-labelledby="italian-tab">
        <h4 class="my-space-ttl"><?php echo esc_html( $step_name ); ?></h4>
        <div class="dimensioni-wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <h6><?php echo __("Politica di cancellazione","mapparte"); ?></h6>
                    <div class="form-floating input-group">
                        <textarea cols="50" name="politica_it" id="politica_it" class="form-control"
                                  placeholder="<?php echo __("Politica di cancellazione","mapparte"); ?>"><?php echo esc_html( $cancel_policy_it ); ?></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h6><?php echo __("Ente Gestore","mapparte"); ?></h6>
                    <div class="form-floating input-group">
						<?php $options = array(
							'field_groups'       => array(),
							// this will find the field groups for this post (post ID's of the acf post objects)
							'fields'             => array( 'ente_gestore' ),
							'form'               => false,
							// set this to false to prevent the <form> tag from being created
							'html_before_fields' => '',
							// html inside form before fields
							'html_after_fields'  => '',
							// html inside form after fields
						);
						acf_form( $options );
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="english-tab">
        <h4 class="my-space-ttl"><?php echo esc_html( $step_name ); ?></h4>
        <div class="dimensioni-wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <h6><?php echo __("Politica di cancellazione","mapparte"); ?></h6>
                    <div class="form-floating input-group">
                        <textarea cols="50" name="politica_en" id="politica_en" class="form-control"
                                  placeholder="<?php echo __("Politica di cancellazione","mapparte"); ?>"><?php echo $cancel_policy_en; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
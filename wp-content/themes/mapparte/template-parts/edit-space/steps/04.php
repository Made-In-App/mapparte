<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $step_name, $space_data;
$step_name = __('Elenca i prezzi',"mapparte");
?>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="italian" role="tabpanel"
         aria-labelledby="italian-tab">
        <h4 class="my-space-ttl"><?php echo esc_html($step_name); ?></h4>
        <div class="dimensioni-wrapper">
            <div class="row">
                <div class="col-sm-6">
                    <h6><?php echo __("Tariffa oraria infrasettimanale","mapparte"); ?></h6>
                    <div class="form-floating input-group">
				        <?php $options = array(
					        'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
					        'fields'       => array( 'price_hour' ),
					        'form'         => false, // set this to false to prevent the <form> tag from being created
					        'html_before_fields' => '', // html inside form before fields
					        'html_after_fields' => '', // html inside form after fields
				        );
				        acf_form( $options );
				        ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h6><?php echo __("Sconto giornaliero","mapparte"); ?> %</h6>
                    <div class="form-floating input-group">
				        <?php $options = array(
					        'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
					        'fields'       => array( 'discount_perc_day' ),
					        'form'         => false, // set this to false to prevent the <form> tag from being created
					        'html_before_fields' => '', // html inside form before fields
					        'html_after_fields' => '', // html inside form after fields
				        );
				        acf_form( $options );
				        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <h6><?php echo __("Tariffa oraria weekend","mapparte"); ?></h6>
                    <div class="form-floating input-group">
				        <?php $options = array(
					        'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
					        'fields'       => array( 'price_hour_weekend' ),
					        'form'         => false, // set this to false to prevent the <form> tag from being created
					        'html_before_fields' => '', // html inside form before fields
					        'html_after_fields' => '', // html inside form after fields
				        );
				        acf_form( $options );
				        ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h6><?php echo __("Sconto weekend","mapparte"); ?> %</h6>
                    <div class="form-floating input-group">
				        <?php $options = array(
					        'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
					        'fields'       => array( 'discount_perc_weekend' ),
					        'form'         => false, // set this to false to prevent the <form> tag from being created
					        'html_before_fields' => '', // html inside form before fields
					        'html_after_fields' => '', // html inside form after fields
				        );
				        acf_form( $options );
				        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-check">
                        <input type="hidden" name="hide_prices" value="0">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="hide_prices"
                            name="hide_prices"
                            value="1"
							<?php checked( ! empty( $space_data['hide_prices'] ) ); ?>
                        >
                        <label class="form-check-label" for="hide_prices">
							<?php echo __( 'Preferisco non mostrare i prezzi e ricevere solo richieste di contatto', 'mapparte' ); ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

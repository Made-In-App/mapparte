<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $step_name, $space_data;
$step_name = __('Dettagli creativi',"mapparte");
?>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="italian" role="tabpanel"
         aria-labelledby="italian-tab">
        <h4 class="my-space-ttl"><?php echo esc_html($step_name); ?></h4>
        <div class="dimensioni-wrapper">
            <div class="row">
                <div class="col-sm-4">
                    <h6><?php echo __("Caratteristiche","mapparte"); ?> * (<?php echo __("Obbligatorie","mapparte"); ?>)</h6>
                    <div class="form-check">
				        <?php $options = array(
					        'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
					        'fields'       => array( 'features' ),
					        'form'         => false, // set this to false to prevent the <form> tag from being created
					        'html_before_fields' => '', // html inside form before fields
					        'html_after_fields' => '', // html inside form after fields
				        );
				        acf_form( $options );
				        ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h6><?php echo __("Attrezzature","mapparte"); ?></h6>
                    <div class="form-check">
				        <?php $options = array(
					        'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
					        'fields'       => array( 'equipment' ),
					        'form'         => false, // set this to false to prevent the <form> tag from being created
					        'html_before_fields' => '', // html inside form before fields
					        'html_after_fields' => '', // html inside form after fields
				        );
				        acf_form( $options );
				        ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h6><?php echo __("Illuminazione","mapparte"); ?></h6>
                    <div class="form-check">
			            <?php $options = array(
				            'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
				            'fields'       => array( 'light' ),
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
                <div class="col-sm-4">
                    <h6><?php echo __("Aerazione","mapparte"); ?></h6>
                    <div class="form-check">
				        <?php $options = array(
					        'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
					        'fields'       => array( 'ventilation' ),
					        'form'         => false, // set this to false to prevent the <form> tag from being created
					        'html_before_fields' => '', // html inside form before fields
					        'html_after_fields' => '', // html inside form after fields
				        );
				        acf_form( $options );
				        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="english-tab">
        <h4 class="my-space-ttl"><?php echo esc_html($step_name); ?></h4>
        <div class="dimensioni-wrapper">
            <div class="row">
                <div class="col-sm-4">

                </div>
            </div>
        </div>
    </div>
</div>
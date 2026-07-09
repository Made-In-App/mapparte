<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $step_name, $space_data;
$step_name = __('Dove si trova il tuo spazio?',"mapparte");
?>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="italian" role="tabpanel"
         aria-labelledby="italian-tab">
        <h4 class="my-space-ttl"><?php echo esc_html($step_name); ?></h4>
        <p class="my-space-desc"><?php echo __("Iniziamo","mapparte"); ?><?php echo __("Inserisci l’indirizzo oppure sposta il marker sulla mappa.","mapparte"); ?></p>
        <div class="dimensioni-wrapper">
            <div class="row">
                <div class="col-sm-12">
					<?php
					acf_form_head();
					$options = array(
						'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
						'fields'       => array( 'address' ),
						'form'         => false, // set this to false to prevent the <form> tag from being created
						'html_before_fields' => '', // html inside form before fields
						'html_after_fields' => '', // html inside form after fields
					);
					acf_form( $options );
					?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                <h6><?php echo __("Quartiere","mapparte"); ?></h6>
                    <div class="form-floating input-group">
						<?php $options = array(
							'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
							'fields'       => array( 'neighbourhood' ),
							'form'         => false, // set this to false to prevent the <form> tag from being created
							'html_before_fields' => '', // html inside form before fields
							'html_after_fields' => '', // html inside form after fields
						);
						acf_form( $options );
						?>
                    </div>
                </div>
                <div class="col-sm-4">
                <h6><?php echo __("Piano","mapparte"); ?></h6>
                    <div class="form-floating input-group">
	                    <?php $options = array(
		                    'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
		                    'fields'       => array( 'floor' ),
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
</div>
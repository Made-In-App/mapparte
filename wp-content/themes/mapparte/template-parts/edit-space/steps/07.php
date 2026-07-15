<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $step_name, $space_data;
$step_name = __('Dettagli generali',"mapparte");
?>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="italian" role="tabpanel"
         aria-labelledby="italian-tab">
        <h4 class="my-space-ttl"><?php echo esc_html($step_name); ?></h4>
        <div class="dimensioni-wrapper">
            <div class="row">
                <div class="col-sm-8">
                    <h6><?php echo __("Dimensione in metri quadri","mapparte"); ?> * (mq)</h6>
                    <div class="form-floating input-group">
				        <?php $options = array(
					        'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
					        'fields'       => array( 'space_mq' ),
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
                    <h6><?php echo __("Altezza sala (m.)","mapparte"); ?></h6>
                    <div class="form-floating input-group">
			            <?php $options = array(
				            'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
				            'fields'       => array( 'space_height' ),
				            'form'         => false, // set this to false to prevent the <form> tag from being created
				            'html_before_fields' => '', // html inside form before fields
				            'html_after_fields' => '', // html inside form after fields
			            );
			            acf_form( $options );
			            ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h6><?php echo __("Larghezza sala (m.)","mapparte"); ?></h6>
                    <div class="form-floating input-group">
			            <?php $options = array(
				            'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
				            'fields'       => array( 'space_width' ),
				            'form'         => false, // set this to false to prevent the <form> tag from being created
				            'html_before_fields' => '', // html inside form before fields
				            'html_after_fields' => '', // html inside form after fields
			            );
			            acf_form( $options );
			            ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h6><?php echo __("Lunghezza sala (m.)","mapparte"); ?></h6>
                    <div class="form-floating input-group">
			            <?php $options = array(
				            'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
				            'fields'       => array( 'space_length' ),
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
                    <h6><?php echo __("Numero massimo di persone","mapparte"); ?> *</h6>
                    <div class="form-floating input-group">
				        <?php $options = array(
					        'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
					        'fields'       => array( 'max_people' ),
					        'form'         => false, // set this to false to prevent the <form> tag from being created
					        'html_before_fields' => '', // html inside form before fields
					        'html_after_fields' => '', // html inside form after fields
				        );
				        acf_form( $options );
				        ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h6><?php echo __("Accessibilità per disabili","mapparte"); ?> *</h6>
                    <div class="form-check">
				        <?php $options = array(
					        'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
					        'fields'       => array( 'Accessibility' ),
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
                    <h6><?php echo __("Pavimento","mapparte"); ?> *</h6>
                    <div class="form-check">
			            <?php $options = array(
				            'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
				            'fields'       => array( 'floor_type' ),
				            'form'         => false, // set this to false to prevent the <form> tag from being created
				            'html_before_fields' => '', // html inside form before fields
				            'html_after_fields' => '', // html inside form after fields
			            );
			            acf_form( $options );
			            ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h6><?php echo __("Accesso allo spazio","mapparte"); ?> *</h6>
                    <div class="form-check">
				        <?php $options = array(
					        'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
					        'fields'       => array( 'space_access' ),
					        'form'         => false, // set this to false to prevent the <form> tag from being created
					        'html_before_fields' => '', // html inside form before fields
					        'html_after_fields' => '', // html inside form after fields
				        );
				        acf_form( $options );
				        ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h6><?php echo __("Disponibilità spazio esterno","mapparte"); ?></h6>
                    <div class="form-check">
				        <?php $options = array(
					        'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
					        'fields'       => array( 'space_external' ),
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
                    <h6><?php echo __("Parcheggio","mapparte"); ?></h6>
                    <div class="form-check">
			            <?php $options = array(
				            'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
				            'fields'       => array( 'parking' ),
				            'form'         => false, // set this to false to prevent the <form> tag from being created
				            'html_before_fields' => '', // html inside form before fields
				            'html_after_fields' => '', // html inside form after fields
			            );
			            acf_form( $options );
			            ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h6><?php echo __("Servizi","mapparte"); ?> *</h6>
                    <div class="form-check">
				        <?php $options = array(
					        'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
					        'fields'       => array( 'services' ),
					        'form'         => false, // set this to false to prevent the <form> tag from being created
					        'html_before_fields' => '', // html inside form before fields
					        'html_after_fields' => '', // html inside form after fields
				        );
				        acf_form( $options );
				        ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h6><?php echo __("Accessori","mapparte"); ?></h6>
                    <div class="form-check">
				        <?php $options = array(
					        'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
					        'fields'       => array( 'accessories' ),
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
                    <h6><?php echo __("Apparecchiature a/v","mapparte"); ?></h6>
                    <div class="form-check">
			            <?php $options = array(
				            'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
				            'fields'       => array( 'equipment_av' ),
				            'form'         => false, // set this to false to prevent the <form> tag from being created
				            'html_before_fields' => '', // html inside form before fields
				            'html_after_fields' => '', // html inside form after fields
			            );
			            acf_form( $options );
			            ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h6><?php echo __("Regole dello spazio","mapparte"); ?></h6>
                    <div class="form-check">
				        <?php $options = array(
					        'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
					        'fields'       => array( 'space_rules' ),
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
        <p class="my-space-desc">* <?php echo __("campi obbligatori","mapparte"); ?></p>
        <p class="my-space-desc">&nbsp;</p>
    </div>
</div>

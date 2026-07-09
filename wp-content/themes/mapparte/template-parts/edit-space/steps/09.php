<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $step_name, $space_data;
$step_name = 'COVID-19';
$covid_notes = get_post_meta( $space_data['id'], 'covid_notes' );
if ( isset( $covid_notes[0] ) ) {
	$covid_notes_it = \WPGlobus_Core::text_filter( $covid_notes[0], 'it' );
	$covid_notes_en = \WPGlobus_Core::text_filter( $covid_notes[0], 'en' );
} else {
	$covid_notes_it = '';
	$covid_notes_en = '';
}
?>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="italian" role="tabpanel"
         aria-labelledby="italian-tab">
        <h4 class="my-space-ttl"><?php echo esc_html( $step_name ); ?></h4>
        <p class="my-space-desc"><?php echo __("In relazione alla pandemia in corso è necessario utilizzare le idonee misure di sicurezza. Quale è la tua strategia?","mapparte"); ?></p>
        <div class="dimensioni-wrapper">
            <div class="row">
                <div class="col-sm-8">
                    <h6><?php echo __("Precauzioni prese","mapparte"); ?>:</h6>
                    <div class="form-check">
						<?php $options = array(
							'field_groups'       => array(), // this will find the field groups for this post (post ID's of the acf post objects)
							'fields'             => array( 'covid' ),
							'form'               => false, // set this to false to prevent the <form> tag from being created
							'html_before_fields' => '', // html inside form before fields
							'html_after_fields'  => '', // html inside form after fields
						);
						acf_form( $options );
						?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h6><?php echo __("Massimo di persone","mapparte"); ?></h6>
                    <p><?php echo __("quante persone alla volta puoi ospitare?","mapparte"); ?></p>
                    <div class="form-floating input-group">
						<?php $options = array(
							'field_groups'       => array(), // this will find the field groups for this post (post ID's of the acf post objects)
							'fields'             => array( 'max_people_covid' ),
							'form'               => false, // set this to false to prevent the <form> tag from being created
							'html_before_fields' => '', // html inside form before fields
							'html_after_fields'  => '', // html inside form after fields
						);
						acf_form( $options );
						?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h6><?php echo __("Campo libero di testo","mapparte"); ?></h6>
                    <p><?php echo __("Qualcos’altro che vorresti condividere sulle tue precauzioni anti-covid?","mapparte"); ?></p>
                    <div class="form-floating input-group">
                        <textarea cols="50" name="covid_notes_it" id="covid_notes_it" class="form-control" placeholder="<?php echo __("Campo libero di testo Covid","mapparte"); ?>"><?php echo esc_html( $covid_notes_it ); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="english-tab">
        <h4 class="my-space-ttl"><?php echo esc_html( $step_name ); ?></h4>
        <p class="my-space-desc"><?php echo __("In relazione alla pandemia in corso è necessario utilizzare le idonee misure di sicurezza. Quale è la tua strategia?","mapparte"); ?></p>
        <div class="dimensioni-wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <h6><?php echo __("Campo libero di testo","mapparte"); ?></h6>
                    <p><?php echo __("Qualcos’altro che vorresti condividere sulle tue precauzioni anti-covid?","mapparte"); ?></p>
                    <div class="form-floating input-group">
                        <textarea cols="50" name="covid_notes_en" id="covid_notes_en" class="form-control" placeholder="<?php echo __("Campo libero di testo Covid","mapparte"); ?>"><?php echo esc_html( $covid_notes_en ); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
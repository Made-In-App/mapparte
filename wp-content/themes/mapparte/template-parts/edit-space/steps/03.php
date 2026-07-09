<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $step_name, $space_data;
$post      = get_post( $space_data['id'] );
$step_name = __('Informazioni di base',"mapparte");
?>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="italian" role="tabpanel"
         aria-labelledby="italian-tab">
        <h4 class="my-space-ttl"><?php echo esc_html( $step_name ); ?></h4>
        <div class="dimensioni-wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <h6><?php echo __("Nome dello spazio","mapparte"); ?></h6>
                    <div class="form-floating input-group">
                        <input type="text" name="post_title" id="post_title" class="form-control"
                               value="<?php echo \WPGlobus_Core::text_filter( $post->post_title, 'it' ); ?>"
                               placeholder="<?php echo __("Nome dello spazio","mapparte"); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h6><?php echo __("Breve descrizione (max 40 caratteri)","mapparte"); ?></h6>
                    <div class="form-floating input-group">
                        <input type="text" name="post_excerpt" id="post_excerpt" maxlength="40" class="form-control"
                               value="<?php echo \WPGlobus_Core::text_filter( $post->post_excerpt, 'it' ); ?>"
                               placeholder="<?php echo __("Breve descrizione dello spazio","mapparte"); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h6><?php echo __("Descrizione dettagliata","mapparte"); ?></h6>
                    <div class="form-floating input-group">
                        <textarea cols="50" name="post_content" id="post_content" class="form-control"
                                  placeholder="<?php echo __("Descrizione dettagliata dello spazio","mapparte"); ?>"><?php echo \WPGlobus_Core::text_filter( $post->post_content, 'it' ); ?></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <h6><?php echo __("Numero minimo ore prenotabili","mapparte"); ?></h6>
                    <div class="form-floating input-group">
						<?php $options = array(
							'field_groups'       => array(),
							// this will find the field groups for this post (post ID's of the acf post objects)
							'fields'             => array( 'min_hours' ),
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
                <div class="col-sm-6">
                    <h6><?php echo __("Numero sale","mapparte"); ?></h6>
                    <div class="form-floating input-group">
						<?php $options = array(
							'field_groups'       => array(),
							// this will find the field groups for this post (post ID's of the acf post objects)
							'fields'             => array( 'rooms' ),
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
            <div class="row">
                <div class="col-sm-6">
                    <h6><?php echo __("Attività","mapparte"); ?></h6>
                    <div class="form-check">
						<?php $options = array(
							'field_groups'       => array(),
							// this will find the field groups for this post (post ID's of the acf post objects)
							'fields'             => array( 'activities' ),
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
                    <h6><?php echo __("Nome dello spazio","mapparte"); ?></h6>
                    <div class="form-floating input-group">
                        <input type="text" name="post_title_en" id="post_title_en" class="form-control"
                               value="<?php echo \WPGlobus_Core::text_filter( $post->post_title, 'en' ); ?>"
                               placeholder="<?php echo __("Nome dello spazio","mapparte"); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h6><?php echo __("Breve descrizione (max 40 caratteri)","mapparte"); ?></h6>
                    <div class="form-floating input-group">
                        <input type="text" name="post_excerpt_en" id="post_excerpt_en" maxlength="40"  class="form-control"
                               value="<?php echo \WPGlobus_Core::text_filter( $post->post_excerpt, 'en' ); ?>"
                               placeholder="<?php echo __("Breve descrizione dello spazio","mapparte"); ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <h6><?php echo __("Descrizione dettagliata","mapparte"); ?></h6>
                    <div class="form-floating input-group">
                        <textarea cols="50" name="post_content_en" id="post_content_en" class="form-control"
                                  placeholder="<?php echo __("Descrizione dettagliata dello spazio","mapparte"); ?>"><?php echo \WPGlobus_Core::text_filter( $post->post_content, 'en' ); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
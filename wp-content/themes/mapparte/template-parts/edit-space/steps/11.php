<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $step_name, $space_data;
$step_name = __('Richiesta approvazione spazio',"mapparte");
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
                        <p><a href="#" id="next" class="btn btn-primary"><?php echo __("Invia la richiesta di approvazione a mapparte","mapparte"); ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
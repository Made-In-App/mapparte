<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $step_name,$space_data;
if ( isset( $space_data['typology'] ) && isset( $space_data['typology'][0] ) ) {
	$typology = $space_data['typology'][0];
} else {
	$typology = '';
}
$step_name = __('Benvenuto',"mapparte");
?>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="italian" role="tabpanel"
         aria-labelledby="italian-tab">
        <h4 class="my-space-ttl"><?php echo esc_html($step_name); ?></h4>
        <p class="my-space-desc"><?php echo __("Unisciti alla community e dai la possibilità agli utenti di conoscerti e scoprire il tuo spazio.","mapparte"); ?></p>
        <div class="dimensioni-wrapper">
                <div class="row">
                    <div class="col-sm-4">
                        <h6><?php echo __("Che tipo di spazio hai?","mapparte"); ?></h6>
                        <div class="form-floating input-group">
                            <?php \Mapparte\Frontend_Utils::get_taxonomy_select('typology', 'Tipologia', 'Seleziona la tipologia...', $typology ); ?>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
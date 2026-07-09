<script>
    jQuery(function () {
        jQuery("#sortable").sortable();
        jQuery("#sortable").disableSelection();
    });
</script>
<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $step_name, $space_data;
$step_name = __('Crea la Photo Gallery del tuo spazio',"mapparte");
?>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="italian" role="tabpanel"
         aria-labelledby="italian-tab">
        <h4 class="my-space-ttl"><?php echo esc_html( $step_name ); ?></h4>
        <p class="my-space-desc"><?php echo __("Le foto valorizzano il tuo spazio.","mapparte"); ?></p>
        <div class="dimensioni-wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-check">
						<?php echo do_shortcode( '[frontend-button]' ); ?>
                    </div>
                </div>
            </div>
            <div id="images-container" class="row">
                <ul id="sortable">
                    <li class="col-sm-3"><img class="frontend-image" src=""/></li>
					<?php
					$images_str = '';
					$images = get_post_meta( $space_data['id'], 'photos', true );
					if ( is_array( $images ) && ! empty( $images ) ) {
						$images = array_map( 'absint', array_filter( $images ) );
						$images_str = implode( ',', $images );
						foreach ( $images as $image_id ) {
							if ( ! $image_id ) {
								continue;
							}
							$image = wp_get_attachment_image_src( $image_id, 'thumbnail' );
							if ( isset( $image[0] ) ) {
								echo "<li class=\"col-sm-3\"><img src=\"" . esc_url( $image[0] ) . "\" id=\"" . esc_attr( $image_id ) . "\"/><a class=\"remove\" href=\"#\">Elimina</a></li>\n";
							}
						}
					}
					?>
                </ul>
            </div>
            <input type="hidden" id="gallery_imgs" name="gallery_imgs" value="<?php echo esc_attr( $images_str ); ?>">
        </div>
    </div>
</div>
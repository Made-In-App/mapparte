<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $current_step, $space_data;
$enable_tabs = [ 3, 9, 10 ];
$step_template = sprintf( "%02d", $current_step );
?>
<div class="col-md-6 form-wrapper">
	<?php if ( in_array ( $current_step, $enable_tabs ) ) get_template_part( 'template-parts/edit-space/nav-tabs' ); ?>
    <?php
	get_template_part( sprintf( 'template-parts/edit-space/steps/%s', $step_template ) );
	?>
</div>
<div class="col-md-6 my-space-content-wrapper">
    <div class="row my-space-content justify-content-center">
        <div class="col-sm-4">
            <img class="my-space-img"
                 src="<?php echo get_template_directory_uri(); ?>/assets/images/photoshooting.png"
                 alt="my-space">
        </div>
        <div class="col-sm-8">
			<?php get_template_part( sprintf( 'template-parts/edit-space/steps/sidebars/%s', $step_template ) ); ?>
        </div>
    </div>
</div>
<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 my-space-content-wrapper">
            <div class="row my-space-content justify-content-center">
                <div class="col-sm-4">
                    <img class="my-space-img"
                         src="<?php echo get_template_directory_uri(); ?>/assets/images/photoshooting.png"
                         alt="my-space">
                </div>
                <div class="col-sm-8">
                   <?php get_template_part( 'template-parts/not-logged' ); ?>
                </div>
            </div>
        </div>
    </div>
</div>

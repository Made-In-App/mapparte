<!--banner section start-->
<section class="banner-wrapper">
    <div class="detail-logged-slider owl-theme owl-carousel">
		<?php
		if ( isset( $args['photos'] ) && is_array( $args['photos'] ) ) :
			$photos = $args['photos'];
			if ( ! is_user_logged_in() ) {
				$photos = array_slice( $photos, 0, 3 );
			}
			foreach ( $photos as $photo ) {
				?>
                <div class="item">
                    <img class="d-none d-md-block"
                         src="<?php echo esc_attr( $photo['sizes']['featured-image-desktop'] ); ?>"
                         alt="<?php echo esc_attr( $photo['alt'] ); ?>"/>
                    <img class="d-block d-md-none"
                         src="<?php echo esc_attr( $photo['sizes']['featured-image-mobile'] ); ?>"
                         alt="<?php echo esc_attr( $photo['alt'] ); ?>"/>
                </div>
			<?php }
		else : ?>
            <div class="item">
                <img class="d-none d-md-block"
                     src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-desktop-slideshow.png"
                     alt="slider"/>
                <img class="d-block d-md-none"
                     src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-mobile-slideshow.png"
                     alt="slider"/>
            </div>
		<?php
		endif; ?>
    </div>
	<?php if ( ! is_user_logged_in() ) : ?>
        <div class="text-center">
            <a data-redirect="<?php echo get_permalink(); ?>" href="<?php echo get_permalink(); ?>"
               class="gallery-btn xoo-el-login-tgr"><?php _e("Guarda la gallery completa","mapparte"); ?></a>
        </div>
	<?php endif; ?>
</section>
<!--banner section end-->
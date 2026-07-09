<!--services section start-->
<section class="services-wrapper">
    <div class="container-fluid gx-5">
        <div class="text-center">
            <h2 class="section-ttl text-body"><?php echo __("I Servizi","mapparte"); ?></h2>
        </div>
        <p class="text-center text-body section-subttl"><?php echo __("Scopri i partner di Mapparte e le offerte dedicate alla Community","mapparte"); ?></p>
        <div class="row justify-content-center mt-5 ">
			<?php
			$servizi = get_field( 'i_servizi', 'option' );
			if ( is_array( $servizi ) ) {
				foreach ( $servizi as $servizio ):
					?>
                    <div class="col-sm-3 service-tile">
                        <img class="service-img"
                             src="<?php echo get_the_post_thumbnail_url( $servizio->ID, 'full' ); ?>"
                             alt="<?php the_title(); ?>"/>
                        <img class="divider mt-3 mb-3"
                             src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg" alt="divider"/>
                        <h5 class="service-ttl"><?php echo get_the_title( $servizio->ID ); ?></h5>
                    </div>
				<?php
				endforeach;
			}
			?>
        </div>
        <img class="divider mt-sm-5 mb-5"
             src="<?php echo get_template_directory_uri(); ?>/assets/images/divider-big.svg" alt="divider"/>
        <div class="col-sm-3 col-8 col-lg-2 space-btn">
            <a class="read-more" href="/contatti/"><img
                        src="<?php echo get_template_directory_uri(); ?>/assets/images/more.svg" alt="btn">
                <p><?php echo __("Contattaci","mapparte"); ?></p>
            </a>
        </div>
    </div>
</section>
<!--services section end-->
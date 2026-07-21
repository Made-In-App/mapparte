<!--featured section start-->
<section class="featured-wrapper">
    <div class="container-fluid gx-5">
        <div class="text-center">
            <h2 class="section-ttl"><?php echo esc_html__( 'In vetrina', 'mapparte' ); ?></h2>
        </div>
        <p class="text-center text-body section-subttl"><?php echo esc_html__( 'Scopri gli spazi interessanti', 'mapparte' ); ?></p>
        <div class="mt-5 featured-tiles featured-slider owl-theme owl-carousel">
			<?php

			$results = \Mapparte\Sponsorship::get_sponsored();

			if ( count( $results ) > 0 ) {
				foreach ( $results as $id ) {

					$date                  = date( 'Y-m-d H:i:s' );
					$sponsored_expiry_date = get_field( 'sponsored_expired', $id );
					$subscribed_class      = ( $sponsored_expiry_date && $sponsored_expiry_date > $date ) ? 'subscribed' : '';
					?>
                    <div class="item">

                        <div class="bg-light featured-tile <?php echo esc_attr( $subscribed_class ); ?>">
                            <a href="<?php echo esc_url( get_the_permalink( $id ) ); ?>">
								<?php
								$photos = ( is_array( get_field( 'photos', $id ) ) ) ? get_field( 'photos', $id )[0]['sizes']['featured-image-desktop'] : get_template_directory_uri() . '/assets/images/logo-desktop-slideshow.png'; ?>
                                <img class="d-md-block" src="<?php echo esc_url( $photos ); ?>" alt="featured"/>
                                <div class="featured-content">
                                    <a data-subscribed="<?php echo esc_attr__( 'Sponsorizzato', 'mapparte' ); ?>" href="<?php echo esc_url( get_the_permalink( $id ) ); ?>">
										<?php if ( is_user_logged_in() ) : ?>
                                            <h6 class="featured-ttl"><?php echo esc_html( get_the_title( $id ) ); ?></h6>
                                            <span class="featured-subttl"><?php echo esc_html( get_the_excerpt( $id ) ); ?></span>
                                            <p class="featured-desc"><?php echo esc_html( substr( wp_strip_all_tags( get_the_content( null, false, $id ) ), 0, 100 ) ); ?>
                                                ...</p>
										<?php else : ?>
                                            <h6 class="featured-ttl"><?php echo esc_html( get_the_excerpt( $id ) ); ?></h6>
                                            <p class="featured-desc"><?php echo esc_html( substr( wp_strip_all_tags( get_the_content( null, false, $id ) ), 0, 100 ) ); ?>
                                                ...</p>
										<?php endif; ?>
                                        <p class="featured-price"><?php echo esc_html__( 'A partire da', 'mapparte' ); ?> <?php echo esc_html( get_field( 'price_hour', $id ) ); ?> € l'ora</p>
                                        <p class="featured-ppl"><?php echo esc_html( get_field( 'max_people', $id ) ); ?>
                                        <?php echo esc_html__( 'persone', 'mapparte' ); ?></p>

                                    </a>
                                    <?php \Mapparte\Frontend_Utils::get_rating( $id, 'star' ); ?>
                                </div>
                            </a>

                        </div>

                    </div>

					<?php
				}
			}
			?>
        </div>
        <div class="col-md-5 col-md-4 col-10 space-btn">
            <a class="find-out-more" href="<?php echo esc_url( home_url( '/sponsorizzazioni/' ) ); ?>"><img
                        src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/find-out.svg' ); ?>" alt="btn">
                <p><?php echo esc_html__( 'Scopri di più', 'mapparte' ); ?></p>
            </a>
        </div>
    </div>
</section>
<!--featured section end-->

<?php if (is_user_logged_in()) :?>
<div class="col-lg-4 col-md-4 col-12">
<?php else:?>
<div class="col-lg-3 col-md-4 col-12">
<?php endif;
$date                  = date( 'Y-m-d H:i:s' );
$sponsored_expiry_date = get_field( 'sponsored_expired' );
$subscribed_class      = ( $sponsored_expiry_date && $sponsored_expiry_date > $date ) ? 'subscribed' : '';
?>
    <div class="bg-light featured-tile <?php echo esc_attr( $subscribed_class ); ?>">
        <div data-subscribed="<?php echo esc_attr__( 'Sponsorizzato', 'mapparte' ); ?>" class="featured-img-slider owl-carousel owl-theme">
        <?php
        if ( is_array(get_field('photos')) ) :
			$photos = get_field('photos');
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
            <div class="item" >
                <img class="d-none d-md-block" src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-desktop-slideshow.png" alt="slider"/>
                <img class="d-none d-md-none" src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-mobile-slideshow.png" alt="slider"/>
            </div>
		<?php
		endif; ?>
        </div>
        <div class="featured-content">
            <a href="<?php the_permalink(); ?>">
                <?php if (is_user_logged_in()) :?>
                <h6 class="featured-ttl"><?php the_title(); ?></h6>
                <span class="featured-subttl"><?php the_excerpt();?></span>
                <p class="featured-desc"><?php echo substr(wp_strip_all_tags( get_the_content()),0,100)?>...</p>
                <?php else:?>
                <h6 class="featured-ttl"><?php the_excerpt(); ?></h6>
                <p class="featured-desc"><?php echo substr(wp_strip_all_tags( get_the_content()),0,100)?>...</p>
                <?php endif;?>

                
                <p class="featured-price"><?php echo  sprintf(__( 'A partire da %s € l\'ora', 'mapparte' ),get_field("price_hour"));?></p>
                <p class="featured-ppl"><?php echo get_field("max_people");?> <?php echo __("persone","mapparte"); ?></p>
            </a>
            <div class="wishlist-btn">
            <?php echo get_favorites_button(get_the_ID());?>
            </div>
            <?php \Mapparte\Frontend_Utils::get_rating(get_the_ID(),"star"); ?>
            <!-- <div class="rating">
                <img class="icon"
                        src="<?php echo get_template_directory_uri(); ?>/assets/images/star-filded.svg"
                        alt="star">
                <img class="icon"
                        src="<?php echo get_template_directory_uri(); ?>/assets/images/star-filded.svg"
                        alt="star">
                <img class="icon"
                        src="<?php echo get_template_directory_uri(); ?>/assets/images/star-filded.svg"
                        alt="star">
                <img class="icon"
                        src="<?php echo get_template_directory_uri(); ?>/assets/images/star-filded.svg"
                        alt="star">
                <img class="icon"
                        src="<?php echo get_template_directory_uri(); ?>/assets/images/star-filded-grey.svg"
                        alt="star">
            </div>
            -->
        </div>
    </div>
</div>
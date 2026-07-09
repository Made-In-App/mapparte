<div class="map-content d-none">
    <div class="mapcontent">
        <?php $photos = (is_array(get_field('photos'))) ?  get_field('photos')[0]['sizes']['featured-image-desktop'] : get_template_directory_uri() . "/assets/images/logo-desktop-slideshow.png";?>
        <img src="<?php echo $photos;?>" alt="featured" />
        <h3 class="map-title featured-ttl"><?php the_title(); ?></h3>
        <p class="featured-ppl"><?php echo get_field("max_people");?> persone</p>
        <div class="d-flex align-items-center justify-content-between">
			<?php if ( ! get_post_meta( get_the_ID(), 'hide_prices', true ) ) : ?>
                <p class="featured-price"><?php echo  sprintf(__( 'A partire da %s € l\'ora', 'mapparte' ),get_field("price_hour"));?>
                </p>
			<?php else : ?>
                <p class="featured-price"><?php echo __("Prezzo su richiesta","mapparte"); ?></p>
			<?php endif; ?>
            <h6 class="featured-price">4.5
                <img class="icon" src="<?php echo get_template_directory_uri();?>/assets/images/star-filded.svg" alt="star">
            </h6>
        </div>
    </div>
</div>

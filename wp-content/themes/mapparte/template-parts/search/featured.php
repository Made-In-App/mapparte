<!--featured section start-->
<section class="featured-wrapper">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/search-bg-half.webp" alt="featured"/>
    <div class="container gx-5">
        <?php if ($GLOBALS['wp_query']->post_count == 0 ) {?>
        <div class="row text-center">
            <h3><?php echo __("Non sono stati trovati risultati, riprovare modificando i filtri","mapparte"); ?> </h3>
        </div>
        <?php }?>
        <?php if ($GLOBALS['wp_query']->post_count > 0 ) {?>
        <div class="featured-tiles row">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
                include(locate_template('template-parts/search/card.php', false, false));
             endwhile; endif; 
             
             get_template_part( 'template-parts/magazine/pagination' );
             ?>
             
        </div>
        <?php }?>
    </div>
</section>
<!--featured section end-->
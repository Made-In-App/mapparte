<!--magazine detail section start-->
<?php
/* Start the Loop */
while ( have_posts() ) :
	the_post();
?>
<!--information section start-->
<section class="info-wrapper how-does-it-work">
        <img class="info-bg" src="/wp-content/themes/mapparte/assets/images/how-it-work-bg.png" alt="how-it-work">
        <?php get_template_part('template-parts/faq/breadcrumb');?>
        <div class="container">
            <h1 class="info-ttl"><?php the_title()?></h1>
            <div class="info-desc"><?php the_content();?></div>
            <img class="divider-lg" src="/wp-content/themes/mapparte/assets/images/divider-lg.png" alt="divider">
            <?php
            $parent = new WP_Query( ['post_type' => 'page','posts_per_page' => -1,'post_parent'    => get_the_ID(),'order' => 'ASC', 'orderby' => 'menu_order']);
            if ( $parent->have_posts() ) : 
                while ( $parent->have_posts() ) : $parent->the_post(); ?>
                <div class="how-does-work-details">
                    <img class="location-img" src="<?php echo get_field("icona");?>" alt="">
                    <h4 class="info-ttl"><?php the_title()?></h4>
                    <div class="info-desc"><?php the_excerpt();?></div>
                    <div class="col-sm-3 col-5 col-lg-2 space-btn">
                        <a href="<?php the_permalink()?>" class="read-more">
                            <img src="/wp-content/themes/mapparte/assets/images/more.svg" alt="btn">
                            <p><?php echo __("Approfondisci","mapparte"); ?></p>
                        </a>
                    </div>
                </div>
                <img class="divider-lg-small" src="/wp-content/themes/mapparte/assets/images/divider-big.svg" alt="divider">
                <?php endwhile;
            endif; wp_reset_postdata(); ?>
        </div>
    </section>
    <!--information section end-->

    <!--newsletter section start-->
    <section class="contactus-wrapper">
        <div class="container">
            <div class="col-sm-3 col-5 col-lg-2 space-btn">
                <a class="read-more" href="/contatti/"><img src="/wp-content/themes/mapparte/assets/images/more.svg" alt="btn">
                    <p><?php echo __("Contattaci","mapparte"); ?></p>
                </a>
            </div>
        </div>
    </section>
    <!--newsletter section end-->
    <?php
endwhile; // End of the loop.?>
    <!--magazine detail section end-->
<!--testimonial section start-->
<section class="testimonial-wrapper">
    <div class="container-fluid gx-5">
        <div class="text-center">
            <h2 class="section-ttl"><?php echo __("La nostra community: i Mapparters","mapparte"); ?></h2>
        </div>
        <p class="text-center text-body section-subttl"><?php echo __("Scopri cosa dicono di noi i Mapparters","mapparte"); ?></p>
        <div class="mt-5  testimonial-tiles owl-theme owl-carousel">
        <?php
        $count = 1;
        $query = new WP_Query( [
            'post_type'           => 'testimonial',
            'posts_per_page'      => -1,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'no_found_rows'       => true,
        ] );
        if ($query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
            ?>
            <div class="item">
                <div class="row testimonial-tile">
                    <div class="col-sm-4 text-center">
                        <img class="testi-user-img" src="<?php echo get_template_directory_uri();?>/assets/images/user-icon-<?php echo ($count%2==0) ? "1" : "2"?>.png" alt="user" />
                        <img class="divider w-75 mx-auto mt-3 mb-2" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider" />
                        <h6 class="testi-user-name"><?php the_title();?></h6>
                    </div>
                    <div class="col-sm-8 text-center text-sm-start mt-3 mt-sm-0">
                        <h6 class="testimonial-desc"><?php the_excerpt();?>
                        </h6>
                    </div>
                </div>
            </div>
            <?php
            $count++;
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
        </div>
    </div>
</section>
<!--testimonial section end-->

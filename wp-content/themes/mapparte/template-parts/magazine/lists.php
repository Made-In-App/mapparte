<!--magazine list section start-->
<section class="magazine-list-wrapper">
        <div class="container gx-5">
            <div class="row magazine-list-tiles">
            <?php
            if (have_posts()) :
              while (have_posts()) :
                the_post();
                $categories = get_the_category();
                ?>
                <div class="col-md-6">
                    <div class="magazine-list-tile d-flex">
                        <div class="magazine-img">
                          <a href="<?php echo get_the_permalink();?>" title="<?php echo get_the_title()?>">
                          <?php
                          $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'medium'); 
                          if (empty($featured_img_url)) 
                              $featured_img_url = get_template_directory_uri()."/assets/images/magazine-list.png";
                          ?>
                          <img src="<?php echo $featured_img_url?>" alt="<?php echo get_the_title()?>">
                          </a>
                        </div>
                        <div class="magazine-content">
                        <?php if (isset($categories[0]) && ! is_category()) :?>
                            <a href="<?php echo get_category_link($categories[0]->cat_ID)?>" title="<?php echo $categories[0]->name?>">
                            <p class="magazine-ttl"><?php echo $categories[0]->name?></p>
                            </a>
                          <?endif;?>
                            <a href="<?php echo get_the_permalink();?>" title="<?php echo get_the_title()?>">
                              <h3><?php echo get_the_title()?></h3>
                            </a>
                            <p class="magazine-desc"><?php echo wp_trim_words(get_the_excerpt(),20,"...");?></p>
                        </div>
                    </div>
                </div>
                <?php
                endwhile;
              endif;
              ?>
            </div>
        </div>
    </section>
    <!--magazine list section end-->
    <?php get_template_part( 'template-parts/magazine/pagination' );?>
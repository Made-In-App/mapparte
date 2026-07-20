<?php
$get_published_post = static function ( $value ) {
    $post = get_post( $value );

    return $post instanceof WP_Post && 'publish' === get_post_status( $post ) ? $post : null;
};

$filter_published_posts = static function ( $values ) use ( $get_published_post ) {
    return array_values( array_filter( array_map( $get_published_post, (array) $values ) ) );
};

$in_evidenza = $get_published_post( get_field( "in_evidenza", "option" ) );
$in_evidenza_altri = $filter_published_posts( get_field( "in_evidenza_altri", "option" ) );
$breaking_news = $filter_published_posts( get_field( "breaking_news", "option" ) );
?>
<div class="row magazine-tiles">

<div class="col-md-9 featured-articles">

  <div class="row">

    <div class="col-md-4 col-sm-6 order-md-1 order-sm-2 order-2 magazine-tile">
    <?php
      if (!empty($in_evidenza_altri[0])){
            $categories = get_the_category($in_evidenza_altri[0]->ID);
        ?>
        <div class="magazine-img">
                <?php
                $featured_img_url = get_the_post_thumbnail_url($in_evidenza_altri[0]->ID,'medium'); 
                if (empty($featured_img_url)) 
                    $featured_img_url = get_template_directory_uri()."/assets/images/magazine-list.png";
                ?>
                <img src="<?php echo $featured_img_url?>" alt="<?php echo $in_evidenza_altri[0]->post_title;?>">                       
        </div>
        <div class="magazine-content">
            <a href="<?php echo get_category_link($categories[0]->cat_ID)?>" title="<?php echo $categories[0]->name?>">
            <p class="magazine-ttl"><?php echo $categories[0]->name?></p>
            </a>
            <a href="<?php echo get_permalink($in_evidenza_altri[0]->ID);?>" title="<?php echo $in_evidenza_altri[0]->post_title;?>">
            <h3><?php echo $in_evidenza_altri[0]->post_title;?></h3>
            </a>
            <p class="magazine-desc"><?php echo wp_trim_words(get_the_excerpt($in_evidenza_altri[0]->ID),20,"...");?></p>
        </div>
        <?php
        }
        ?>
    </div>

    <div class="col-md-8 col-sm-12 order-md-2 order-sm-1 order-1 magazine-main-tile">
        <?php
            if (!empty($in_evidenza)){
                $categories = get_the_category($in_evidenza->ID);
            ?>            
           
            <div class="magazine-main-content">
                <a href="<?php echo get_category_link($categories[0]->cat_ID)?>" title="<?php echo $categories[0]->name?>">
                    <p class="magazine-ttl"><?php echo $categories[0]->name?></p>
                    </a>
                <a href="<?php echo get_permalink($in_evidenza);?>" title="<?php echo $in_evidenza->post_title;?>"><h3><?php echo $in_evidenza->post_title;?></h3></a>
                <?php
                $featured_img_url = get_the_post_thumbnail_url($in_evidenza->ID,'full'); 
                if (empty($featured_img_url)) 
                    $featured_img_url = get_template_directory_uri()."/assets/images/magazine-list.png";
                ?>
                <img src="<?php echo $featured_img_url?>" alt="<?php echo $in_evidenza->post_title;?>">
                
                <p class="magazine-desc">
                <?php echo wp_trim_words(get_the_excerpt($in_evidenza->ID),20,"...");?></p><br>
                    <div class="name" style="position:relative;margin-top:20px">
                        <img src="<?php echo get_template_directory_uri();?>/assets/images/user.png" alt="user">
                        <span><?php echo __("di","mapparte"); ?> <?php echo get_the_author_meta("nicename",$in_evidenza->post_author);?></span>
                    </div>
            </div>
            <?php
            }
            ?>  
    </div>
  </div>


  <div class="row">
    <div class="mt-md-5 order-sm-4 order-3">
        <div class="row">
        <?php
        for ($i=1;$i<=3;$i++){
            if (!empty($in_evidenza_altri[$i])){
                $categories = get_the_category($in_evidenza_altri[$i]->ID);
            ?>
            <div class="col-md-4 magazine-tile">
                <div class="magazine-img">
                <?php
                $featured_img_url = get_the_post_thumbnail_url($in_evidenza_altri[$i]->ID,'medium'); 
                if (empty($featured_img_url)) 
                    $featured_img_url = get_template_directory_uri()."/assets/images/magazine-list.png";
                ?>
                <img src="<?php echo $featured_img_url?>" alt="<?php echo $in_evidenza_altri[$i]->post_title;?>">
                </div>
                <div class="magazine-content">
                    <a href="<?php echo get_category_link($categories[0]->cat_ID)?>" title="<?php echo $categories[0]->name?>">
                        <p class="magazine-ttl"><?php echo $categories[0]->name?></p>
                    </a>
                    <a href="<?php echo get_permalink($in_evidenza_altri[$i]->ID);?>" title="<?php echo $in_evidenza_altri[$i]->post_title;?>"><h3><?php echo $in_evidenza_altri[$i]->post_title;?></h3></a>
                    <p class="magazine-desc"><?php echo wp_trim_words(get_the_excerpt($in_evidenza_altri[$i]->ID),20,"...");?></p>
                </div>
            </div>
            <?php 
            }
        }
        ?>
        </div>
    </div>
  </div>

</div>



<div class="col-md-3 aside">

    <div class="order-md-3 order-sm-3 order-4 magazine-tile align-self-center">
        <p class="news-ttl">BREAKING NEWS</p>
        <?php
        for ($i=0;$i<=3;$i++){
            if (!empty($breaking_news[$i])){
                $categories = get_the_category($breaking_news[$i]->ID);
            ?>
                <div class="magazine-content">
                <a href="<?php echo get_category_link($categories[0]->cat_ID)?>" title="<?php echo $categories[0]->name?>">
                    <p class="magazine-ttl"><?php echo $categories[0]->name?></p>
                </a>
                <a href="<?php echo get_permalink($breaking_news[$i]->ID);?>" title="<?php echo $breaking_news[$i]->post_title;?>"><h3><?php echo $breaking_news[$i]->post_title;?></h3></a>
                    <p class="magazine-desc"><?php echo wp_trim_words(get_the_excerpt($breaking_news[$i]->ID),20,"...");?></p>
            </div>
            <?php 
            }
        }
        ?>
    </div>
</div>
</div>
</div>
</section>
<!--magazine section end-->

<!--magazine detail section start-->
<?php
/* Start the Loop */
while ( have_posts() ) :
    the_post();
?>
<section class="magazine-detail-wrapper">
        <img class="background-img" src="<?php echo get_template_directory_uri();?>/assets/images/magazine-detail-bg.png" alt="magazine">
        <div class="container gx-5">
        <?php get_template_part( 'template-parts/magazine/search' );?>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h1 class="magazine-detail-ttl"><?php the_title()?></h1>
                    <div class="posting-time">
                        <h4><?php the_date()?></h4>
                    </div>
                    <div class="name">
                        <img src="<?php echo get_template_directory_uri();?>/assets/images/user.png" alt="user">
                        <span><?php echo __("di","mapparte"); ?> <?php echo get_the_author_meta("nicename");?></span>
                        
                    </div>
                </div>
                <div class="col-md-6">
                    <ul class="social-icons">
                        <li><a href="#"><img src="<?php echo get_template_directory_uri();?>/assets/images/facebook.png" alt="facebook"></a></li>
                        <li><a href="#"><img src="<?php echo get_template_directory_uri();?>/assets/images/twitter.png" alt="twitter"></a></li>
                        <li><a href="#"><img src="<?php echo get_template_directory_uri();?>/assets/images/instagram.png" alt="instagram"></a></li>
                        <li><a href="#"><img src="<?php echo get_template_directory_uri();?>/assets/images/world-wide.png" alt="worl-wide"></a></li>
                    </ul>
                    <p class="sectiondesc"><?php the_excerpt()?></p>
                </div>
            </div>
        </div>
        <div class="container gx-5">
            <div class="row justify-content-center">
                <div class="col-12 magazine-detail-img">
                <?php
                $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'large'); 
                if (empty($featured_img_url)) 
                    $featured_img_url = get_template_directory_uri()."/assets/images/magazine-detail.png";
                ?>
                <img class="w-100 d-none d-sm-block" src="<?php echo $featured_img_url?>" alt="<?php the_title();?>">
                <?php
                $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'large_mobile'); 
                if (empty($featured_img_url)) 
                    $featured_img_url = get_template_directory_uri()."/assets/images/slider-img-mbl.jpg";
                ?>
                <img class="w-100 d-sm-none" src="<?php echo $featured_img_url?>" alt="<?php the_title();?>">
                </div>
            </div>
        </div>
        <?php
        $images = get_field('galleria');
        if( $images ): ?>
            <div class="container-fluid px-0">
                <div class="magazine-slider">
                    <?php foreach( $images as $image ): ?>
                        <div class="magazine-detail-img">
                            <img class="w-100 d-none d-sm-block" src="<?php echo $image["sizes"]["gallery"] ?>" alt="<?php echo $image["title"]?>">
                            <img class="w-100 d-sm-none" src="<?php echo $image["sizes"]["large_mobile"] ?>" alt="<?php echo $image["title"]?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            
                
      
        <?php endif; ?>

       
        <div class="container gx-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 col-12 magazine-detail-desc">
                    <?php the_content();?>
                    <?php get_template_part( 'template-parts/content/social-icons' );?>
                </div>
            </div>
        </div>
    </section>
    <?php
    endwhile; // End of the loop.?>
    <!--magazine detail section end-->
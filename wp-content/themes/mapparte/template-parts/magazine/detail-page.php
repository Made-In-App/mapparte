<!--magazine detail section start-->
<?php
/* Start the Loop */
while ( have_posts() ) :
	the_post();
?>
<section class="magazine-detail-wrapper">
        <img class="background-img" src="<?php echo get_template_directory_uri();?>/assets/images/magazine-detail-bg.png" alt="magazine">
        <div class="container gx-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h1 class="magazine-detail-ttl"><?php the_title()?></h1>
                </div>
                <div class="col-md-6">
                   
                 
                </div>
            </div>
        </div>
        <div class="container gx-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 col-12 magazine-detail-desc">
                    <?php the_content();?>
                     <ul class="social-icons">
                        <li><a href="#"><img src="<?php echo get_template_directory_uri();?>/assets/images/facebook-b.png" alt="facebook"></a></li>
                        <li><a href="#"><img src="<?php echo get_template_directory_uri();?>/assets/images/twitter-b.png" alt="twitter"></a></li>
                        <li><a href="#"><img src="<?php echo get_template_directory_uri();?>/assets/images/instagram-b.png" alt="instagram"></a></li>
                        <li><a href="#"><img src="<?php echo get_template_directory_uri();?>/assets/images/world-wide-b.png" alt="worl-wide"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <?php
endwhile; // End of the loop.?>
    <!--magazine detail section end-->
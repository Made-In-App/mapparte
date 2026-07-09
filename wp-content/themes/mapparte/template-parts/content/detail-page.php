<!--magazine detail section start-->
<?php
/* Start the Loop */
while ( have_posts() ) :
	the_post();
?>
<section class="magazine-detail-wrapper">
        <img class="background-img" src="<?php echo get_template_directory_uri();?>/assets/images/detail-bg.png" alt="magazine">
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
                    <?php get_template_part( 'template-parts/content/social-icons' );?>
                </div>
            </div>
        </div>
    </section>
    <?php
endwhile; // End of the loop.?>
    <!--magazine detail section end-->
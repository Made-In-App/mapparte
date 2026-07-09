<!--magazine detail section start-->
<?php
/* Start the Loop */
while ( have_posts() ) :
	the_post();
?>
 <section class="contact-wrapper">
    <div class="container">
        <div class="contact-form-wrapper">
            <h1 class="section-ttl"><?php the_title()?></h1>
            <div class="section-desc">
                    <?php the_content();?>
            </div>
            <?php echo do_shortcode('[contact-form-7 id="322" title="Contact form 1"]');?>
        </div>
    </div>
</section>
    <?php
endwhile; // End of the loop.?>
    <!--magazine detail section end-->
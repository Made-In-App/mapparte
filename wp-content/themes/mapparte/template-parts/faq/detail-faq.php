<!--magazine detail section start-->
<?php
/* Start the Loop */
while ( have_posts() ) :
	the_post();
?>
<!--information section start-->
 <!--information section start-->
 <section class="info-wrapper">
        <img class="info-bg" src="/wp-content/themes/mapparte/assets/images/how-it-work-bg.png" alt="how-it-work">
        <?php get_template_part('template-parts/faq/breadcrumb');?>
        <div class="container">
            <div class="user-icon">
                <img class="mx-auto" src="<?php echo get_field("icona");?>" alt="user">
            </div>
            <h1 class="info-ttl"><?php the_title()?></h1>
            <div class="info-desc"><?php the_content();?></div>
            
            <img class="divider" src="/wp-content/themes/mapparte/assets/images/divider.svg" alt="divider">
            <div class="accordion how-work-accordian" id="accordionExample">
                <?php
                    $count = 1;
                    if (have_rows('faq_repeater')) { 
                    while (have_rows('faq_repeater')) : the_row(); ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo $count;?>">
                            <button class="accordion-button <?php echo ($count == 1) ? '' : 'collapsed'?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $count;?>" aria-expanded="true" aria-controls="collapse<?php echo $count;?>">
                                <h5 class="accordion-ttl"><?php echo get_sub_field('domanda');?> <i class="fa fa-angle-down" aria-hidden="true"></i></h5>
                            </button>
                        </h2>
                        <div id="collapse<?php echo $count;?>" class="accordion-collapse collapse <?php echo ($count == 1) ? 'show' : ''?>" aria-labelledby="heading<?php echo $count;?>"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                            <?php echo get_sub_field('risposta');?>
                            </div>
                        </div>
                    </div>
                    <?php
                        $count++;
                        endwhile; 
                }  
                ?>
            </div>
        </div>
    </section>
    <!--information section end-->

    <!--newsletter section start-->
    <section class="contactus-wrapper">
        <div class="container">
            <h2><?php echo __("CONSULTA LE NOSTRE","mapparte"); ?> <span>FAQ</span></h2>
            <h5><?php echo __("OPPURE","mapparte"); ?></h5>
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
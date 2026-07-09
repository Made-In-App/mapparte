 <!--numbers section start-->
 <section class="numbers-wrapper">
        <div class="container-fluid gx-5">
            <div class="text-center">
                <h2 class="section-ttl text-body"><?php echo __("I numeri di Mapparte","mapparte"); ?></h2>
            </div>
        
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5 col-xl-4">
                    <div class="numbers-tile">
                        <img class="number-img" src="<?php echo get_template_directory_uri();?>/assets/images/contact.png" alt="contact" />
                        <div class="numbers-count">
                            <span>
                            <?php
                            $result = count_users();
                            echo $result['total_users'];?>
                            </span>
                            <img class="divider divider-1" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider" />
                            <h6><?php echo __("ISCRITTI","mapparte"); ?></h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-5 col-xl-4">
                    <div class="numbers-tile">
                        <img class="number-img" src="<?php echo get_template_directory_uri();?>/assets/images/location.png" alt="contact" />
                        <div class="numbers-count">
                            <span><?php echo wp_count_posts('space')->publish;?></span>
                            <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider" />
                            <h6><?php echo __("SPAZI","mapparte"); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--numbers section end-->
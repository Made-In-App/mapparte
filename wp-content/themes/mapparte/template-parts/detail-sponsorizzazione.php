
 <!--information section start-->
 <section class="magazine-detail-wrapper">
        <img class="background-img" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/detail-bg.png' ); ?>" alt="magazine">
        <div class="container gx-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h1 class="magazine-detail-ttl"><?php the_title(); ?></h1>
                </div>
                <div class="col-md-6">

                </div>
            </div>
        </div>
        <div class="container gx-5">
            <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10 col-12 magazine-detail-desc">
                            <?php the_content(); ?><br><br><br><br>
                    </div>
                </div>
            <div class="row align-items-center plan-list col-lg-10 mx-auto">
                <div class="col-md-4 col-sm-8 col-10">

                        <div class="plan-wrapper">
                            <h6 class="plan-ttl">SILVER</h6>
                            <h4 class="plan-price"><span>€</span>15<span class="points">,00</span></h4>
                            <p class="status-note ps-0"><?php echo esc_html__( 'Incrementa la visibilità del tuo spazio per un mese', 'mapparte' ); ?></p>
                        </div>

                </div>
                <div class="col-md-4 col-sm-8 col-10">
                        <div class="plan-wrapper">
                            <h6 class="plan-ttl">GOLD</h6>
                            <h4 class="plan-price"><span>€</span>150<span class="points">,00</span></h4>
                            <p class="status-note ps-0"><?php echo esc_html__( 'Incrementa la visibilità del tuo spazio per un anno', 'mapparte' ); ?></p>
                        </div>

                </div>
            </div>
            <br><br><br><br><br><br><br><br><br>
        </div>

</section>
<!--information section end-->

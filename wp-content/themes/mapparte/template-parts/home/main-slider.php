<!--banner section start-->
<section class="banner-wrapper">
        <div class="home-slider owl-theme owl-carousel">
            <div class="item">
                <img class="d-none d-md-block" src="<?php echo get_template_directory_uri();?>/assets/images/slider-img.jpg" alt="slider" />
                <img class="d-block d-md-none" src="<?php echo get_template_directory_uri();?>/assets/images/slider-img-mbl.jpg" alt="slider" />
            </div>
            <div class="item">
                <img class="d-none d-md-block" src="<?php echo get_template_directory_uri();?>/assets/images/slider-img-1.jpg" alt="slider" />
                <img class="d-block d-md-none" src="<?php echo get_template_directory_uri();?>/assets/images/slider-img-mbl-1.jpg" alt="slider" />
            </div>
            <div class="item">
                <img class="d-none d-md-block" src="<?php echo get_template_directory_uri();?>/assets/images/slider-img-2.jpg" alt="slider" />
                <img class="d-block d-md-none" src="<?php echo get_template_directory_uri();?>/assets/images/slider-img-mbl-2.jpg" alt="slider" />
            </div>
            <div class="item">
                <img class="d-none d-md-block" src="<?php echo get_template_directory_uri();?>/assets/images/slider-img-3.jpg" alt="slider" />
                <img class="d-block d-md-none" src="<?php echo get_template_directory_uri();?>/assets/images/slider-img-mbl-3.jpg" alt="slider" />
            </div>
            <div class="item">
                <img class="d-none d-md-block" src="<?php echo get_template_directory_uri();?>/assets/images/slider-img-4.jpg" alt="slider" />
                <img class="d-block d-md-none" src="<?php echo get_template_directory_uri();?>/assets/images/slider-img-mbl-4.jpg" alt="slider" />
            </div>
        </div>
        <!-- <img class="slider-img" src="<?php echo get_template_directory_uri();?>/assets/images/slider-component.png" alt="slider" /> -->
        <div class="home-banner">
            <diV class="container-fluid">
                <div class="col-md-12 search-content">
                    <h1 class="banner-ttl"><?php echo __("Cerca uno spazio","mapparte"); ?></h1>
                    <p class="banner-desc"><?php echo __("Scopri e prenota spazi per la tua prossima attività creativa","mapparte"); ?></p>
                    <form action="/spaces/" id="search-home" name="ricerca" id="ricerca" class="row">
                        <div class="col-sm-11 row">
                            <div class="mb-3 col-6">
                                <input id="where" name="where" type="text" placeholder="<?php echo __("Dove","mapparte"); ?>..." class="form-control">
                                <input type="hidden" id="city" name="city" />
                            </div>
                            <div class="mb-3  col-6">
	                            <?php \Mapparte\Frontend_Utils::get_taxonomy_select( 'activity', 'activity', 'Cosa...' ); ?>
                            </div>
                        </div>
                        <div class="col-sm-1 action-btn">
                            <a class="search-btn" href="javascript:void(0)" onClick="document.forms['ricerca'].submit();"><img class="search-btn" src="<?php echo get_template_directory_uri();?>/assets/images/search.svg" alt="search"></a>
                        </div>
                    </form>
                </div>
                <div class="space-wrapper">
                    <h1 class="banner-ttl"><?php echo __("Inserisci il tuo spazio","mapparte"); ?></h1>
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="banner-desc"><?php echo __("Diventa un host, condividi il tuo spazio e fai crescere la tua attività.","mapparte"); ?>
                            </p>
                        </div>
                        <div class="col-3 space-btn">
                            <a href="/inserisci-il-tuo-spazio/" style="display:block !important" class="xoo-el-login-tgr" data-redirect="/inserisci-il-tuo-spazio/"><img src="<?php echo get_template_directory_uri();?>/assets/images/sapce-btn.svg" alt="btn">
                                <p><?php echo __("Inizia","mapparte"); ?></p>
                            </a>
                        </div>
                    </div>
                </diV>
            </div>
    </section>
    <!--banner section end-->
   
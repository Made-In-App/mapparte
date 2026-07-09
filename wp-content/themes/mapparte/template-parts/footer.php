<!--footer section start-->
<footer>
    <div class="container-fluid gx-5">
        <div class="row justify-content-center">
            <div class="col-lg-2 col-sm-2 logo-section">
                <img class="logo" src="<?php echo get_template_directory_uri();?>/assets/images/logo.svg" alt="logo" />
                <p>MAPPARTE <?php echo date("Y")?></p>
                <p><?php echo __("Tutti i diritti riservati","mapparte");?></p>
                <p>Powered by <a href="https://www.poetronicart.com/" target="_blank" rel="nofollow">Poetronicart</a></p>
                <p>Designed by <a href="https://www.artbuilder.it/studio/" target="_blank" rel="nofollow">D’Orsi Studio</a></p>
                <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/footer-divider-1.svg" alt="divider" />
            </div>
            <div class="col-lg-2 col-sm-2 contact-section">
                <h6 class="sec-ttl"><?php echo strtoupper(__("Contatti","mapparte"));?></h6>
                <a href="tel:3392443504">+39  339 2443504</a>
                <a href="mailto:info@mapparte.com">info@mapparte.com</a>
                <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/footer-divider-2.svg" alt="divider" />
            </div>
            <div class="col-lg-2 col-sm-2 menu-section">
                <h6 class="sec-ttl"><?php echo __("SCOPRI","mapparte");?></h6>
                <a href="<?php echo get_home_url(); ?>">Home</a>
                <a href="<?php echo get_home_url(); ?>/magazine/">Magazine</a>
                <a href="<?php echo get_home_url(); ?>/team/">Team</a>
                <a href="<?php echo get_home_url(); ?>/contatti/"><?php echo __("Contatti","mapparte");?></a>
                <a href="<?php echo get_home_url(); ?>/posizioni-aperte/"><?php echo __("Posizioni aperte","mapparte");?></a>
                <a href="<?php echo get_home_url(); ?>/come-funziona/">Faq</a>
                <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/footer-divider-3.svg" alt="divider" />
            </div>
            <div class="col-lg-2 col-sm-2 info-section">
                <h6 class="sec-ttl"><?php echo __("INFORMAZIONI","mapparte");?></h6>
                <a href="<?php echo get_home_url(); ?>/cookies-policy/">Cookies policy</a>
                <a href="<?php echo get_home_url(); ?>/privacy-policy/">Privacy policy</a>
                <a href="<?php echo get_home_url(); ?>/termini-e-condizioni-duso/"><?php echo __("Termini e Condizioni d’uso","mapparte");?></a>
                <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/footer-divider-4.svg" alt="divider" />
            </div>
            <div class="col-lg-2 col-sm-2 social-section">
                <ul>
                    <li><a href="https://www.facebook.com/Mapparteam" target="_blank" rel="nofollow"><img src="<?php echo get_template_directory_uri();?>/assets/images/facebook.svg" alt="facebook" /></a></li>
                    <li><a href="https://www.instagram.com/_mapparte_/"><img src="<?php echo get_template_directory_uri();?>/assets/images/instagram.svg" alt="facebook" /></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<!--footer section end-->
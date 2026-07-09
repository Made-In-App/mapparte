<!--spazi section start-->
<section class="spazi-wrapper">
    <div class="container-fluid gx-5">
        <div class="text-center">
            <h2 class="section-ttl text-body"><?php echo __("Gli spazi","mapparte"); ?></h2>
        </div>
        <p class="text-center text-body section-subttl"><?php echo __("Prenota gli spazi per le tue attività creative","mapparte"); ?></p>
        <div class="row justify-content-center mt-5">
			<?php
			$tax_selected = get_field( "gli_spazi", "options" );
			if ( is_array( $tax_selected ) ) {
				foreach ( $tax_selected as $tax ) {
					$term     = get_term( $tax );
					$immagine = get_field( "immagine", $term );
					?>
                    <div class="spazi-tile col-6 col-md-4 col-lg-2">
                        <a href="/spaces/?where=&s_activity=<?php echo $term->term_id; ?>">
                            <img class="m-auto spazi-img" src="<?php echo $immagine; ?>"
                                 alt="<?php echo $term->name; ?>"/>
                            <img class="my-4 divider"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                                 alt="photo"/>
                            <h4 class="spazi-ttl"><?php echo $term->name; ?></h4>
                        </a>
                    </div>
					<?php
				}
			}
			?>
        </div>

    </div>
    <div class="col-7 col-sm-3 col-lg-2 mt-5 space-btn">
        <a class="read-more" href="/lista-spazi/" title="<?php echo __("Vedi tutte le categorie","mapparte"); ?>"><img
                    src="<?php echo get_template_directory_uri(); ?>/assets/images/more.svg">
            <p><?php echo __("Vedi tutte le categorie","mapparte"); ?></p>
        </a>
    </div>
</section>
<!--spazi section end-->
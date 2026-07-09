<?php
/**
 * Template Name: Preferiti
 */
get_header();
?>
<?php get_template_part( 'template-parts/admin/mobile-button' ); ?>
<!--my space section start-->
<section class="booking-wrapper">
        <div class="container-fluid">
            <div class="row">
	            <?php if ( is_user_logged_in() ) : ?>
                <?php get_template_part( 'template-parts/admin/sidebar' );?>
                <div class="col-md-10 booking-table-wrapper">
                    <div class="booking-table-section">
                        <div class="row booking-header justify-content-between">
                            <div class="col-sm-4">
                                <h5 class="booking-ttl"><?php echo __("Preferiti","mapparte");?></h5>
                            </div> 
                        </div>
                        <div class="featured-tiles row">
                        <?php 
                        if (!empty(get_user_favorites())) :
                        $the_query = new WP_Query(['post_type' => 'space', 'post__in' => get_user_favorites()]);
                        if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); 
                            include(locate_template('template-parts/search/card.php', false, false));
                        endwhile; endif;
                        ?>
                        <script>
                        jQuery(".wishlist-btn").click(function() {
                            setTimeout(function(){
                                self.location.reload();
                            },1000)
                            
                        });
                        
                        </script> 
                        <?php
                    else:
                        ?>
                        <div class="row text-center">
                            <h3><?php echo __("Attualmente non hai nessun preferito","mapparte");?></h3>
                        </div>
                        <?php
                        endif;?>
                        
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <!--my space section end-->
<?php
get_footer();

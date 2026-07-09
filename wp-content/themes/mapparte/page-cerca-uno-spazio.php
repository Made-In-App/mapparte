<?php
/**
 * Template Name: Cerca uno spazio
 */
get_header();
get_template_part( 'template-parts/search/panel','',$_REQUEST );
if (is_user_logged_in()){
    get_template_part( 'template-parts/search/featured-logged','',$_REQUEST );
}else{
    get_template_part( 'template-parts/search/featured','',$_REQUEST );
}
get_template_part( 'template-parts/footer' );
get_footer();

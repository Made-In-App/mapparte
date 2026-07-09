<?php
get_header();
$space_data = \Mapparte\Utils::return_space_data( get_the_ID() );

get_template_part( 'template-parts/space/banner', '', $space_data );
get_template_part( 'template-parts/space/breadcrumb', '', $space_data );
get_template_part( 'template-parts/space/detail', '', $space_data );
get_template_part( 'template-parts/footer', '', $space_data );
if ( is_user_logged_in() ) :
	get_template_part( 'template-parts/space/modal', '', $space_data );
endif;
get_footer();
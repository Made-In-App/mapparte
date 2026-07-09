<?php
/**
 * Template Name: Messaggi
 * The template for displaying all messages
 */

get_header();
?>
<?php get_template_part( 'template-parts/admin/mobile-button' ); ?>
    <!--my space section start-->
    <section class="booking-wrapper ">
        <div class="container-fluid">
            <div class="row">
				<?php if ( is_user_logged_in() ) : ?>
					<?php
					get_template_part( 'template-parts/admin/sidebar' );
					if ( isset( $wp_query->query_vars['comment_id'] ) && $wp_query->query_vars['comment_id'] ) {
						get_template_part( 'template-parts/admin/message-details' );
					} else if ( isset( $wp_query->query_vars['mine'] ) && $wp_query->query_vars['mine'] ) {
						get_template_part( 'template-parts/admin/messages-sent' );
                    } else {
						get_template_part( 'template-parts/admin/messages' );
                    }
				endif; ?>
            </div>
        </div>
    </section>
    <!--my space section end-->
<?php
get_footer();
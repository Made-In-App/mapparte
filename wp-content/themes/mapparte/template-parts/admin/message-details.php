<div class="col-md-10 booking-table-wrapper">
	<?php
	if ( isset( $_REQUEST['message'] ) ) {
	    \Mapparte\Messages::send_message( sanitize_text_field( $_REQUEST['message'] ), sanitize_text_field( $_REQUEST['thread'] ), sanitize_text_field( $_REQUEST['comment_post_id'] ) );
    }
	$messages = \Mapparte\Messages::get_messages_details( $wp_query->query_vars['comment_id'] );
	$parent_comment = ( isset ( $messages['parent'] ) ) ? $messages['parent'] : $wp_query->query_vars['comment_id'];
	$comment_post_id = ( isset ( $messages['comment_post_ID'] ) ) ? $messages['comment_post_ID'] : 0;
	?>
    <?php if ( isset( $messages['results'] ) && sizeof( $messages['results'] ) > 0 ) : ?>
    <div class="booking-table-section">
        <div class="row booking-header justify-content-between">
            <div class="col-sm-4">
                <h5 class="booking-ttl"><?php echo __("Messaggio","mapparte");?></h5>
            </div>
        </div>
        <table class="booking-table table">
            <thead class="booking-table-head">
            <tr class="row mx-0 justify-content-center">
                <th class="col-4"><?php echo __("Da","mapparte");?></th>
                <th class="col-3"><?php echo __("Spazio","mapparte");?></th>
                <th class="col-3"><?php echo __("Prenotazione","mapparte");?></th>
                <th class="col-2"><?php echo __("Data","mapparte");?></th>
            </tr>
            </thead>
            <tbody class="booking-table-body">
			<?php

				foreach ( $messages['results'] as $message ) {
					if ( 'space' === $message->post_type ) {
						$space_title = apply_filters( 'the_title', $message->post_title );
						$permalink   = get_home_url() . "/messaggi/$message->comment_ID/";
					} elseif ( 'booking' === $message->post_type ) {
						$details     = get_post_meta( $message->comment_post_ID, '_booking_details', true );
						$space_title = $details['spaceTitle'];
						$permalink   = get_permalink( $message->comment_post_ID );
					}
					?>
                    <tr class="row mx-0 justify-content-center accepted">
                        <td class="col-4"><?php echo esc_html( $message->comment_author ); ?></td>
                        <td class="col-3"><?php echo esc_html( $space_title ); ?></td>
                        <th class="col-3"><?php if ( 'booking' === $message->post_type ) {
                            echo sprintf ( "<a href=\"%s\">%s</a>",  esc_url( get_the_permalink( $message->comment_post_ID ) ), esc_html( $message->comment_post_ID ) );
                        } ?></th>
                        <td class="col-2"><?php echo esc_html( $message->comment_date ); ?></td>
                        <td class="col-12"><?php echo esc_html( $message->comment_content ); ?></td>
                    </tr>
				<?php }
			?>
            </tbody>
        </table>
    </div>
    <div class="contact-wrapper">
        <div class="container">
            <div class="contact-form-wrapper">
                <form method="post" class="contact-form">
                    <div class="form-tile">
                        <label for="message" class="form-label"><?php echo __("Rispondi","mapparte");?></label>
                        <textarea class="form-control" id="message" name="message" rows="8"></textarea>
                    </div>
                    <div class="action-btn text-center">
                        <input type="hidden" id="thread" name="thread" value="<?php echo $parent_comment; ?>">
                        <input type="hidden" id="comment_post_id" name="comment_post_id" value="<?php echo $comment_post_id; ?>">
                        <button type="submit" class="btn btn-outline-secondary"><?php echo __("Invia","mapparte");?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--contact section end-->
    <?php get_template_part( 'template-parts/magazine/pagination' ); ?>
</div>
<?php endif; ?>

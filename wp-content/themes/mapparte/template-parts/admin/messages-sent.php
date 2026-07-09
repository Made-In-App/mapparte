<div class="col-md-10 booking-table-wrapper">
	<?php
	$messages = \Mapparte\Messages::get_messages();
	?>
    <div class="booking-table-section">
        <div class="row booking-header justify-content-between">
            <div class="col-sm-4">
                <h5 class="booking-ttl"><?php echo __("Messaggi inviati","mapparte");?></h5>
            </div>
            <!--div class="col-sm-3 d-flex align-items-center">
                <label for="filter" class="form-label mb-0">Filter</label>
                <select class="form-select" name="filter" id="filter">
                    <option value="">filter 1</option>
                    <option value="">filter 1</option>
                    <option value="">filter 1</option>
                    <option value="">filter 1</option>
                </select>
            </div-->
        </div>
        <div class="table-wrapper">
            <table class="booking-table table">
                <thead class="booking-table-head">
                <tr class="row mx-0 justify-content-center">
                    <th class="col-2"><?php echo __("Destinatario","mapparte");?></th>
                    <th class="col-3"><?php echo __("Messaggio","mapparte");?></th>
                    <th class="col-3"><?php echo __("Spazio","mapparte");?></th>
                    <th class="col-2"><?php echo __("Prenotazione","mapparte");?></th>
                    <th class="col-2"><?php echo __("Data","mapparte");?></th>
                </tr>
                </thead>
                <tbody id="table-messages" class="booking-table-body">
				<?php
				if ( sizeof( $messages ) > 0 ) :
					foreach ( $messages as $message ) {
						if ( 'space' === $message->post_type ) {
							$space_title = apply_filters( 'the_title', $message->post_title );
							$permalink   = get_home_url() . "/messaggi/$message->comment_ID/";
						} elseif ( 'booking' === $message->post_type ) {
							$details     = get_post_meta( $message->comment_post_ID, '_booking_details', true );
							$space_title = $details['spaceTitle'];
							$permalink   = get_permalink( $message->comment_post_ID );
						}
						$dest = get_user_by( 'id', get_comment_meta( $message->comment_ID, 'to', true ) );
						?>
                        <tr class="row mx-0 justify-content-center accepted">
                            <td class="col-2"><a
                                        href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $dest->data->display_name ); ?></a>
                            </td>
                            <td class="col-3"><a
                                        href="<?php echo esc_url( $permalink ); ?>""><?php echo esc_html( \Mapparte\Frontend_Utils::truncate_string( $message->comment_content, 40 ) ); ?>
                                ...</a>
                            </td>
                            <td class="col-3"><?php echo ( isset( $space_title ) ) ? esc_html( $space_title ) : ''; ?></td>
                            <th class="col-2"><?php if ( 'booking' === $message->post_type ) {
		                            echo sprintf ( "<a href=\"%s\">%s</a>",  esc_url( get_the_permalink( $message->comment_post_ID ) ), esc_html( $message->comment_post_ID ) );
	                            } ?></th>
                            <td class="col-2"><?php echo esc_html( \Mapparte\Frontend_Utils::format_date_time( $message->comment_date ) ); ?></td>
                        </tr>
					<?php }
				endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php get_template_part( 'template-parts/magazine/pagination' ); ?>
</div>
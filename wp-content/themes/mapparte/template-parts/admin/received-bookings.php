<div class="col-md-10 booking-table-wrapper">
    <div class="booking-table-section">
        <div class="row booking-header justify-content-between">
            <div class="col-sm-4">
                <h5 class="booking-ttl"><?php echo __("Prenotazioni ricevute","mapparte");?></h5>
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
					<th class="col-2"><?php echo __("Spazio","mapparte");?></th>
					<th class="col-1"><?php echo __("Codice","mapparte");?></th>
					<th class="col-2"><?php echo __("Cliente","mapparte");?></th>
					<th class="col-2">Checkin</th>
					<th class="col-2">Checkout</th>
					<th class="col-1"><?php echo __("Prezzo","mapparte");?></th>
					<th class="col-2"><?php echo __("Stato","mapparte");?></th>
				</tr>
				</thead>
				<tbody class="booking-table-body">
				<?php
				if ( sizeof( $wp_query->posts ) > 0 ) :
					foreach ( $wp_query->posts as $booking ) {
						$details = get_post_meta( $booking->ID, '_booking_details', true );
						$status  = \Mapparte\Frontend_Utils::get_booking_status( $booking, $details );
						$permalink = get_permalink($booking->ID);
						?>
						<tr class="row mx-0 justify-content-center <?php echo esc_html( $status[0] ); ?>">
                            <td class="col-2"><a href="<?php echo $permalink; ?>"><?php echo esc_html( $details['spaceTitle'] ); ?></a></td>
							<td class="col-1"><a href="<?php echo $permalink; ?>"><?php echo esc_html( $booking->ID ); ?></a></td>
							<td class="col-2"><?php echo esc_html( get_the_author_meta( 'user_login', $booking->post_author ) ); ?></td>
							<td class="col-2"><?php echo esc_html( \Mapparte\Frontend_Utils::format_date_time( $details['fromDateTime'] ) ); ?></td>
							<td class="col-2"><?php echo esc_html( \Mapparte\Frontend_Utils::format_date_time( $details['toDateTime'] ) ) ?></td>
							<td class="col-1"><?php echo esc_html( $details['finalPrice'] ); ?> €</td>
							<td class="col-2">
								<p class="status"><?php echo esc_attr( $status[2] ); ?></p>
								<br> <?php echo \Mapparte\Frontend_Utils::format_date_time( $details['date'] ); ?>
							</td>
						</tr>
					<?php }
				endif; ?>
				</tbody>
			</table>
		</div>	
    </div>
</div>
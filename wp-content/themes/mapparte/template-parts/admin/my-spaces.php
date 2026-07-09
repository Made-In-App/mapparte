<?php get_template_part( 'template-parts/admin/mobile-button' ); ?>
    <!--my space section start-->
    <section class="booking-wrapper">
        <div class="container-fluid">
            <div class="row">
				<?php if ( is_user_logged_in() ) : ?>
					<?php get_template_part( 'template-parts/admin/sidebar' ); ?>
                    <div class="col-md-10 booking-table-wrapper">
                        <div class="booking-table-section">
                            <div class="booking-table-section">
                                <div class="row booking-header justify-content-between">
                                    <div class="col-sm-4">
                                        <h5 class="booking-ttl"><?php echo __("I miei spazi","mapparte");?></h5>
                                    </div>
                                    <!--div class="col-sm-3 d-flex align-items-center">
										<label for="filter" class="form-label mb-0">Filter</label>
										<select class="form-select" name="filter" id="filter">
											<option value="">Bozza</option>
											<option value="">In attesa di feedback</option>
											<option value="">Pubblicato</option>
											<option value="">Rifiutato</option>
										</select>
									</div-->
                                </div>
                                <div class="table-wrapper">
                                    <table class="booking-table table">
                                        <thead class="booking-table-head">
                                        <tr class="row mx-0 justify-content-center">
                                            <th class="col-3"><?php echo __("Nome","mapparte");?></th>
                                            <th class="col-1"></th>
                                            <th class="col-1"><?php echo __("Prezzo","mapparte");?></th>
                                            <th class="col-2"><?php echo __("Tipologia","mapparte");?></th>
                                            <th class="col-2"><?php echo __("Sponsorizzazione","mapparte");?></th>
                                            <th class="col-2"><?php echo __("Stato","mapparte");?></th>
                                            <th class="col-1"><?php echo __("Cancella","mapparte");?></th>
                                        </tr>
                                        </thead>
                                        <tbody class="booking-table-body">
										<?php
										if ( sizeof( $wp_query->posts ) > 0 ) :
											foreach ( $wp_query->posts as $space ) {
												$typology = get_the_terms( $space->ID, 'typology' );
												$status   = \Mapparte\Frontend_Utils::get_space_status( $space->post_status );
												$price    = get_post_meta( $space->ID, 'price_hour', true );

												$sponsored_expiry_date = get_post_meta( $space->ID, 'sponsored_expired', true );
												$sponsored_type        = get_post_meta( $space->ID, 'sponsored_type', true );
												$sponsored_text        = ( $sponsored_expiry_date && $sponsored_expiry_date >= date( 'Y-m-d H:i:s' ) ) ? sprintf( '%s attiva', $sponsored_type ) : __( 'Attiva sponsorizzazione', 'mapparte' );
												?>
                                                <tr class="row mx-0 justify-content-center <?php echo esc_attr( $status[0] ); ?>">
                                                    <td class="col-3"><a
                                                                href="<?php echo get_permalink( $space->ID ); ?>">
															<?php if ( $space->post_title ) {
																echo apply_filters( 'the_title', $space->post_title );
															} else {
																echo "N/A";
															} ?>
                                                        </a></td>
                                                    <td class="col-1">
                                                        <a href="<?php echo get_home_url(); ?>/inserisci-il-tuo-spazio/?space_id=<?php echo esc_attr( $space->ID ); ?>">
                                                            <span class="dashicons dashicons-edit"></span>
                                                        </a>
                                                    </td>
                                                    <td class="col-1"><?php if ( $price ) {
															echo sprintf( "%d € / ora", esc_html( $price ) );
														} else {
															echo "N/A";
														} ?> </td>
                                                    <td class="col-2"><?php if ( isset( $typology ) && isset( $typology[0] ) ) {
															echo esc_html( $typology[0]->name );
														} ?></td>
                                                    <td class="col-2">
														<?php if ( 'Pubblicato' === $status[2] ) : ?>
                                                        <a href="<?php echo esc_url( get_home_url() . '/attiva-sponsorizzazione/?space_id=' . (int) $space->ID ); ?>"><?php echo esc_html( $sponsored_text ); ?></a>
														<?php endif; ?>
                                                    </td>
                                                    <td class="col-2">
                                                        <p class="status"><?php echo esc_html( $status[2] ); ?></p>
                                                        <br> <?php echo esc_html( \Mapparte\Frontend_Utils::format_date_time( $space->post_date ) ); ?>
                                                    </td>
                                                    <td class="col-1"><a class="remove-space" href="?space_id=<?php echo esc_attr( $space->ID ); ?>&action=remove"><span class="dashicons dashicons-trash"></span></a></td>
                                                </tr>
											<?php }
										endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php get_template_part( 'template-parts/magazine/pagination' ); ?>
                    </div>
				<?php endif; ?>
            </div>
            
        </div>
    </section>
    <!--my space section end-->

<?php
/**
 * Template Name: Prenotazioni Effettuate
 */
get_header();
?>
<?php get_template_part( 'template-parts/admin/mobile-button' ); ?>
    <!--my space section start-->
    <section class="booking-wrapper">
        <div class="container-fluid">
            <div class="row">
				<?php if ( is_user_logged_in() ) : ?>
					<?php get_template_part( 'template-parts/admin/sidebar' ); ?>
                    <div class="col-md-10 booking-table-wrapper">
                        <div class="booking-table-section">
                            <div class="row booking-header justify-content-between">
                                <div class="col-sm-4">
                                    <h5 class="booking-ttl">Prenotazioni effettuate</h5>
                                </div>
                                <div class="col-sm-3 d-flex align-items-center">
                                    <label for="filter" class="form-label mb-0">Filter</label>
                                    <select class="form-select" name="filter" id="filter">
                                        <option value="">filter 1</option>
                                        <option value="">filter 1</option>
                                        <option value="">filter 1</option>
                                        <option value="">filter 1</option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-wrapper">
                                <table class="booking-table table">
                                    <thead class="booking-table-head">
                                    <tr class="row mx-0 justify-content-center">
                                        <th class="col-2">spazio</th>
                                        <th class="col-2">ID prenotazione</th>
                                        <th class="col-1">Cliente</th>
                                        <th class="col-2">Checkin</th>
                                        <th class="col-2">Checkout</th>
                                        <th class="col-1">Prezzo</th>
                                        <th class="col-2">Stato</th>
                                    </tr>
                                    </thead>
                                    <tbody class="booking-table-body">
                                    <?php
                                    //TODO: Aggiungere paginazione
                                    $my_bookings = get_posts( array(
                                        'post_type'   => 'booking',
                                        'author'      => get_current_user_id(),
                                        'post_status' => 'any',
                                        'numberposts' => 100
                                    ) );
                                    if ( sizeof( $my_bookings ) > 0 ) :
                                        foreach ( $my_bookings as $booking ) {
                                            $status  = \Mapparte\Frontend_Utils::get_space_status( $booking->post_status );
                                            $details = get_post_meta( $booking->ID, '_booking_details', true );
                                            ?>
                                            <tr class="row mx-0 justify-content-center <?php echo esc_html( $status[0] ); ?>">
                                                <td class="col-2"><?php echo esc_html( $details['spaceTitle'] ); ?></td>
                                                <td class="col-2"><?php echo esc_html( $booking->ID ); ?></td>
                                                <td class="col-1"><?php echo esc_html( get_the_author_meta( 'user_login', $booking->post_author ) ); ?></td>
                                                <td class="col-2"><?php echo esc_html( $details['fromDate'] ); ?></td>
                                                <td class="col-2"><?php echo esc_html( $details['toDate'] ); ?></td>
                                                <td class="col-1"><?php echo esc_html( $details['finalPrice'] ); ?> €</td>
                                                <td class="col-2">
                                                    <p class="status">Nuova richiesta</p>
                                                    <br> <?php echo apply_filters( 'the_date', $booking->post_date ); ?>
                                                </td>
                                            </tr>
                                        <?php }
                                    endif; ?>
                                    <!--tr class="row mx-0 justify-content-center new-request">
                                        <td class="col-2">Galleria di Sophie</td>
                                        <td class="col-2">Z065GH8</td>
                                        <td class="col-1">Antonio</td>
                                        <td class="col-2">12 november 2020 <br> Ore 18:00</td>
                                        <td class="col-2">12 november 2020 <br> Ore 23:00</td>
                                        <td class="col-1">800,00 euro</td>
                                        <td class="col-2">
                                            <p class="status">Nuova richiesta</p> <br> 6 november 2020
                                        </td>
                                    </tr>
                                    <tr class="row mx-0 justify-content-center accepted">
                                        <td class="col-2">Galleria di Sophie</td>
                                        <td class="col-2">Z065GH8</td>
                                        <td class="col-1">Antonio</td>
                                        <td class="col-2">12 november 2020 <br> Ore 18:00</td>
                                        <td class="col-2">12 november 2020 <br> Ore 23:00</td>
                                        <td class="col-1">800,00 euro</td>
                                        <td class="col-2">
                                            <p class="status">Accettata</p> <br> 6 november 2020
                                        </td>
                                    </tr>
                                    <tr class="row mx-0 justify-content-center rejected">
                                        <td class="col-2">Galleria di Sophie</td>
                                        <td class="col-2">Z065GH8</td>
                                        <td class="col-1">Antonio</td>
                                        <td class="col-2">12 november 2020 <br> Ore 18:00</td>
                                        <td class="col-2">12 november 2020 <br> Ore 23:00</td>
                                        <td class="col-1">800,00 euro</td>
                                        <td class="col-2">
                                            <p class="status">rifiutata</p> <br> 6 november 2020
                                        </td>
                                    </tr>
                                    <tr class="row mx-0 justify-content-center paid">
                                        <td class="col-2">Galleria di Sophie</td>
                                        <td class="col-2">Z065GH8</td>
                                        <td class="col-1">Antonio</td>
                                        <td class="col-2">12 november 2020 <br> Ore 18:00</td>
                                        <td class="col-2">12 november 2020 <br> Ore 23:00</td>
                                        <td class="col-1">800,00 euro</td>
                                        <td class="col-2">
                                            <p class="status">Pagata</p> <br> 6 november 2020
                                        </td>
                                    </tr>
                                    <tr class="row mx-0 justify-content-center feedback">
                                        <td class="col-2">Galleria di Sophie</td>
                                        <td class="col-2">Z065GH8</td>
                                        <td class="col-1">Antonio</td>
                                        <td class="col-2">12 november 2020 <br> Ore 18:00</td>
                                        <td class="col-2">12 november 2020 <br> Ore 23:00</td>
                                        <td class="col-1">800,00 euro</td>
                                        <td class="col-2">
                                            <p class="status">Dai un feedback</p> <br> 6 november 2020
                                        </td>
                                    </tr-->
                                    </tbody>
                                </table>
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

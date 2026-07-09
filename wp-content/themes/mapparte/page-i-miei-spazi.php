<?php
/**
 * Template Name: i miei spazi
 */
get_header();
//TODO: Aggiungere paginazione
$spaces = get_posts( array(
	'post_type'   => 'space',
	'author'      => get_current_user_id(),
	'post_status' => 'any',
	'numberposts' => 100
) );
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
                            <div class="booking-table-section">
                                <div class="row booking-header justify-content-between">
                                    <div class="col-sm-4">
                                        <h5 class="booking-ttl">I miei spazi</h5>
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
                                            <th class="col-3">nome</th>
                                            <th class="col-1"></th>
                                            <th class="col-2">prezzo</th>
                                            <th class="col-2">tipologia</th>
                                            <th class="col-4">stato</th>
                                        </tr>
                                        </thead>
                                        <tbody class="booking-table-body">
                                        <?php
                                        if ( sizeof( $spaces ) > 0 ) :
                                            foreach ( $spaces as $space ) {
                                                $typology = get_the_terms( $space->ID, 'typology' );
                                                $status   = \Mapparte\Frontend_Utils::get_space_status( $space->post_status );
                                                $price    = get_post_meta( $space->ID, 'price_hour', true );
                                                ?>
                                                <tr class="row mx-0 justify-content-center <?php echo esc_html( $status[0] ); ?>">
                                                    <td class="col-3"><a href="<?php echo get_permalink( $space->ID ); ?>">
                                                            <?php if ( $space->post_title ) {
                                                                echo apply_filters( 'the_title', $space->post_title );
                                                            } else {
                                                                echo "N/A";
                                                            } ?>
                                                        </a></td>
                                                    <td class="col-1">
                                                        <a href="/inserisci-il-tuo-spazio/?space_id=<?php echo esc_html( $space->ID ); ?>">
                                                            <svg class="svg-inline--fa fa-pen fa-w-16" aria-hidden="true"
                                                                focusable="false" data-prefix="fa" data-icon="pen"
                                                                role="img"
                                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                                                data-fa-i2svg="">
                                                                <path fill="currentColor"
                                                                    d="M290.74 93.24l128.02 128.02-277.99 277.99-114.14 12.6C11.35 513.54-1.56 500.62.14 485.34l12.7-114.22 277.9-277.88zm207.2-19.06l-60.11-60.11c-18.75-18.75-49.16-18.75-67.91 0l-56.55 56.55 128.02 128.02 56.55-56.55c18.75-18.76 18.75-49.16 0-67.91z"></path>
                                                            </svg>
                                                        </a>
                                                    </td>
                                                    <td class="col-2"><?php if ( $price ) {
                                                            echo sprintf( "%d € / ora", esc_html( $price ) );
                                                        } else {
                                                            echo "N/A";
                                                        } ?> </td>
                                                    <td class="col-2"><?php if ( isset( $typology ) && isset( $typology[0] ) ) {
                                                            echo esc_html( $typology[0]->name );
                                                        } ?></td>
                                                    <td class="col-4">
                                                        <p class="status"><?php echo esc_html( $status[1] ); ?></p>
                                                        <br> <?php echo apply_filters( 'the_date', $space->post_date ); ?>
                                                    </td>
                                                </tr>
                                            <?php }
                                        endif; ?>
                                        </tbody>
                                    </table>
                                </div>    
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

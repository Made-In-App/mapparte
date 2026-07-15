<?php
/**
 * Template Name: Profilo
 */
global $response;
get_header();
?>
<?php get_template_part( 'template-parts/admin/mobile-button' ); ?>
    <!--my space section start-->
    <section class="booking-wrapper  profile-detail-wrapper">
        <div class="container-fluid">
            <div class="row">
				<?php if ( is_user_logged_in() ) : ?>
					<?php
					$current_user_id = get_current_user_id();
					$current_user    = get_userdata( $current_user_id );
					$user_meta       = get_user_meta( $current_user_id, '', true );

					$avatar = get_field( 'immagine', 'user_' . $current_user_id ) ? wp_get_attachment_image_src( $user_meta['immagine'][0], 'medium' )[0] : get_template_directory_uri() . '/assets/images/user.png';

					?>
					<?php get_template_part( 'template-parts/admin/sidebar' ); ?>
                    <div class="col-md-10 px-0">
                        <div class="profile-details-section">
                            <div class="header-top">
                                <div class="row align-items-center justify-content-between mx-0">
                                    <div class="col-md-6 col-6 header-left">
                                        <p><?php echo __("Profilo","mapparte");?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-xl-3 text-center">
                                    <div class="profile-img">
                                        <img class="user-img" id="user-img"
                                             src="<?php echo esc_url( $avatar ); ?>"
                                             alt="user">
                                        <a href="#" id="image-update"><i class="fa fa-pen"></i></a>
                                    </div>
                                    <ul class="user-review-wrapper">
                                        <li>
                                            <p><?php echo __("Puntualità","mapparte");?></p>
                                            <h6><?php echo isset( $user_meta['puntualita'] ) ? esc_attr( $user_meta['puntualita'][0] ) : '0'; ?>
                                                <img class="icon"
                                                     src="<?php echo get_template_directory_uri(); ?>/assets/images/star-filded.svg"
                                                     alt="star">
                                            </h6>
                                        </li>
                                        <li>
                                            <p><?php echo __("Cura","mapparte");?></p>
                                            <h6><?php echo isset( $user_meta['cura'] ) ? esc_attr( $user_meta['cura'][0] ) : '0'; ?>
                                                <img class="icon"
                                                     src="<?php echo get_template_directory_uri(); ?>/assets/images/star-filded.svg"
                                                     alt="star">
                                            </h6>
                                        </li>
                                        <li>
                                            <p><?php echo __("Rispetto delle dotazioni","mapparte");?></p>
                                            <h6><?php echo isset( $user_meta['rispetto_delle_dotazioni'] ) ? esc_attr( $user_meta['rispetto_delle_dotazioni'][0] ) : '0'; ?>
                                                <img class="icon"
                                                     src="<?php echo get_template_directory_uri(); ?>/assets/images/star-filded.svg"
                                                     alt="star">
                                            </h6>
                                        </li>
                                        <li>
                                            <p><?php echo __("Iscritto dal","mapparte");?></p>
                                            <p class="date"><?php echo esc_html( \Mapparte\Frontend_Utils::format_date( $current_user->data->user_registered ) ); ?></p>
                                        </li>
                                    </ul>
									<?php
									$rating     = get_field( 'rating' );
									$puntualita = get_field( 'puntualita' );
									$cura       = get_field( 'cura' );
									$rispetto   = get_field( 'rispetto_delle_dotazioni' );

									$nonce = wp_create_nonce( 'profile-nonce' );

									?>
                                </div>
                                <div class="col-md-8 col-xl-7">
                                    <form action="#" method="post" class="profile-update-form">
                                        <div class="row align-items-end">
                                            <div class="col-md-7">
												<?php if ( isset( $response['error'] ) && $response['error'] ) {
													echo "<div class=\"form-tile error\"><h6>" . esc_html( $response['error'] ) . "</h6></div>";
												} else {
													echo "<div class=\"form-tile\"><h6>Profilo utente aggiornato con successo.</h6></div>";
                                                } ?>
                                                <div class="form-tile">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" name="first_name"
                                                               id="first_name"
                                                               value="<?php echo isset( $user_meta['first_name'] ) ? esc_attr( $user_meta['first_name'][0] ) : ''; ?>"
                                                               placeholder="<?php echo __("Nome","mapparte");?>">
                                                        <label for="first_name" class="form-label"><?php echo __("Nome","mapparte");?></label>
                                                    </div>
                                                </div>
                                                <div class="form-tile">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" name="last_name"
                                                               id="last_name"
                                                               value="<?php echo isset( $user_meta['last_name'] ) ? esc_attr( $user_meta['last_name'][0] ) : ''; ?>"
                                                               placeholder="<?php echo __("Cognome","mapparte");?>">
                                                        <label for="last_name" class="form-label"><?php echo __("Cognome","mapparte");?></label>
                                                    </div>
                                                </div>
                                                <div class="form-tile">
                                                    <div class="form-floating">
                                                        <input class="form-control" type="date" name="xoo_aff_date_date"
                                                               id="xoo_aff_date_date"
                                                               placeholder="Data"
                                                               value="<?php echo isset( $user_meta['xoo_aff_date_date'] ) ? esc_attr( date( 'Y-m-d', strtotime( $user_meta['xoo_aff_date_date'][0] ) ) ) : ''; ?>">
                                                        <label for="xoo_aff_date_date" class="form-label"><?php echo __("Data di nascita","mapparte");?></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                               
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-md-7">
                                                <div class="form-tile">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control"
                                                               name="xoo_aff_text_residenza" id="xoo_aff_text_residenza"
                                                               value="<?php echo isset( $user_meta['xoo_aff_text_residenza'] ) ? esc_attr( $user_meta['xoo_aff_text_residenza'][0] ) : ''; ?>"
                                                               placeholder="<?php echo __("Indirizzo di residenza","mapparte");?>">
                                                        <label for="xoo_aff_text_residenza"
                                                               class="form-label"><?php echo __("Indirizzo di residenza","mapparte");?></label>
                                                    </div>
                                                </div>
                                                <div class="form-tile">
                                                    <div class="form-floating">
                                                        <input type="email" class="form-control" name="user_email"
                                                               id="user_email"
                                                               value="<?php echo isset( $current_user->data->user_email ) ? esc_attr( $current_user->data->user_email ) : ''; ?>"
                                                               placeholder="Email">
                                                        <label for="user_email" class="form-label">Email</label>
                                                    </div>
                                                </div>
                                                <div class="form-tile">
                                                    <div class="form-floating">
                                                        <input type="tel" class="form-control" id="telefono"
                                                               name="telefono"
                                                               placeholder="<?php echo __("Telefono","mapparte");?>"
                                                               value="<?php echo isset( $user_meta['telefono'] ) ? esc_attr( $user_meta['telefono'][0] ) : '0'; ?>">
                                                        <label for="numero" class="form-label"><?php echo __("Numero di telefono","mapparte");?></label>
                                                    </div>
                                                </div>
                                                <div class="form-tile">
													<?php
													$activity = isset( $user_meta['xoo_aff_select_list_attivita'] ) ? esc_attr( $user_meta['xoo_aff_select_list_attivita'][0] ) : '';
													\Mapparte\Frontend_Utils::get_taxonomy_select_by_slug( 'activity', 'activity', 'Seleziona...', $activity ); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-md-7">
                                                <div class="form-tile">
                                                    <textarea class="form-control" name="description" id="description"
                                                              placeholder="<?php echo __("Presentazione","mapparte");?>"><?php echo isset( $user_meta['description'] ) ? esc_html( $user_meta['description'][0] ) : ''; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <p><?php echo __("Fatti conoscere dalla community: parla delle cose che ti piacciono. Racconta i tuoi hobby, le tue passioni e informazioni che ti fa piacere condividere. Aggiungi una tua foto!","mapparte");?></p>
                                            </div>
                                        </div>
                                        <h5 class="corporate-data-ttl"><?php echo __("dati aziendali","mapparte");?></h5>
                                        <div class="col-md-7">
                                            <div class="form-tile">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="nome_azienda"
                                                           name="nome_azienda"
                                                           placeholder="<?php echo __("Nome dell'azienda","mapparte");?>"
                                                           value="<?php echo isset( $user_meta['nome_azienda'] ) ? esc_attr( $user_meta['nome_azienda'][0] ) : ''; ?>">
                                                    <label for="company-name" class="form-label"><?php echo __("Nome dell'azienda","mapparte");?></label>
                                                </div>
                                            </div>
                                            <div class="form-tile">
                                                <div class="form-floating">
                                                    <input type="tel" class="form-control" id="telefono_azienda"
                                                           name="telefono_azienda"
                                                           placeholder="<?php echo __("Telefono","mapparte");?>"
                                                           value="<?php echo isset( $user_meta['telefono_azienda'] ) ? esc_attr( $user_meta['telefono_azienda'][0] ) : ''; ?>">
                                                    <label for="telefono" class="form-label"><?php echo __("Numero di telefono","mapparte");?></label>
                                                </div>
                                            </div>
                                            <div class="action-btn">
                                                <input type="hidden" id="immagine" name="immagine"
                                                       value="<?php echo isset( $user_meta['immagine'] ) ? esc_attr( $user_meta['immagine'][0] ) : ''; ?>">
                                                <input type="hidden" id="nonce" name="nonce"
                                                       value="<?php echo esc_attr( $nonce ); ?>">
                                                <button type="submit" class="btn btn-secondary"><?php echo __("SALVA","mapparte");?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="profile-details-section">
                            <div class="header-top">
                                <div class="row align-items-center justify-content-between mx-0">
                                    <div class="col-md-6 col-6 header-left">
                                        <p><?php echo __("Account","mapparte");?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-xl-3 text-center">
                                </div>
                                <div class="col-md-8 col-xl-7">
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <div class="form-tile">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="username"
                                                           placeholder="<?php echo __("Nome utente","mapparte");?>" disabled="disabled"
                                                           value="<?php echo isset( $current_user->data->user_login ) ? esc_attr( $current_user->data->user_login ) : ''; ?>">
                                                    <label for="username" class="form-label"><?php echo __("Nome utente","mapparte");?></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <p><?php echo __("Il nome utente non può essere modificato.","mapparte");?></p>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <p><?php echo wp_kses_post( sprintf(
                                                __( 'Per cancellare l’account scrivi un messaggio a %1$s con oggetto “Recesso dal Portale Mapparte.com”', 'mapparte' ),
                                                '<a href="mailto:info@mapparte.com?subject=Recesso%20dal%20Portale%20Mapparte.com">info@mapparte.com</a>'
                                            ) ); ?></p>
                                        </div>
                                    </div>
                                    <h5 class="corporate-data-ttl"><?php echo esc_html__( 'PIANI DI SPONSORIZZAZIONE DEGLI SPAZI', 'mapparte' ); ?></h5>
                                    <div class="col-md-7 plans-wrapper">
										<?php
										$wp_query = new \WP_Query( [
											'post_status' => 'publish',
											'post_type'   => 'space',
											'author'      => $current_user_id,
											'meta_query'  => [
												'relation' => 'AND',
												[
													'key' => 'sponsored_type',
												],
												[
													'key'     => 'sponsored_expired',
													'value'   => date( 'Y-m-d H:i:s' ),
													'compare' => '>',
												],
											],
										] );

										if ( count( $wp_query->posts ) > 0 ) {
											foreach ( $wp_query->posts as $space ) {
												$sponsored_type = get_post_meta( $space->ID, 'sponsored_type', true );
												$sponsored_text = sprintf( 'Piano Attivo <span>%s</span>', esc_html( $sponsored_type ) );
												$space_title    = ( $space->post_title ) ? apply_filters( 'the_title', $space->post_title ) : 'N/A';
												?>
                                                <div class="row align-items-center plan-tile">
                                                    <div class="col-sm-7">
                                                        <h6><?php echo esc_html( $space_title ); ?></h6>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <a class="active-plan"
                                                           href="<?php echo esc_url( home_url( '/attiva-sponsorizzazione/?space_id=' . (int) $space->ID ) ); ?>">
                                                            <p><?php echo wp_kses_post( $sponsored_text ); ?></p>
                                                            <i class="fa fa-chevron-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
												<?php
											}
										}
										?>
                                    </div>
                                    <h5 class="corporate-data-ttl"><?php echo __("cambio password","mapparte");?></h5>

                                    <form action="#" method="post" class="profile-update-form">
                                        <div class="col-md-7">
                                            <div class="form-tile">
                                                <div class="form-floating">
                                                    <input type="password" class="form-control" id="oldpassword"
                                                           name="oldpassword"
                                                           placeholder="<?php echo __("Vecchia Password","mapparte");?>">
                                                    <label for="oldpassword" class="form-label"><?php echo __("Vecchia Password","mapparte");?></label>
                                                </div>
                                            </div>
                                            <div class="form-tile">
                                                <div class="form-floating">
                                                    <input type="password" class="form-control" id="newpassword"
                                                           name="newpassword"
                                                           placeholder="<?php echo __("Nuova Password","mapparte");?>">
                                                    <label for="newpassword" class="form-label"><?php echo __("Nuova Password","mapparte");?></label>
                                                </div>
                                            </div>
                                            <div class="form-tile">
                                                <div class="form-floating">
                                                    <input type="password" class="form-control" id="confirmpassword"
                                                           name="confirmpassword"
                                                           placeholder="<?php echo __("Conferma Password","mapparte");?>">
                                                    <label for="confirmpassword" class="form-label"><?php echo __("Conferma Password","mapparte");?></label>
                                                </div>
                                            </div>
                                            <div class="action-btn">
                                                <input type="hidden" id="nonce" name="nonce"
                                                       value="<?php echo esc_attr( $nonce ); ?>">
                                                <button type="submit" class="btn btn-secondary"><?php echo __("Aggiorna Password","mapparte");?>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
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

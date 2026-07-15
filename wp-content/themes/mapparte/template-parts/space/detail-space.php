<div class="col-md-7 px-0">
	<?php
    if ( is_user_logged_in() ) :
	?>
    <h1 class="detail-ttl"><?php the_title(); ?></h1>
        <span style="font-size: 1.6rem;"><?php the_excerpt(); ?></span>
    <?php else : ?>
        <h1 class="detail-ttl"><?php echo get_the_excerpt(); ?></h1>
    <?php endif; ?>
    <div class="d-flex align-items-center review-wrapper">
        <img class="review" src="<?php echo get_template_directory_uri(); ?>/assets/images/star.svg" alt="star">
        <h5 class="review-count"><?php \Mapparte\Frontend_Utils::get_rating(get_the_ID(),"number"); ?></h5>
        <div class="wishlist-btn">
            <?php \Mapparte\Frontend_Utils::favorite_button( get_the_ID() ); ?>
        </div>
    </div>
    <div class="activities-wrapper">
        <h3 class="activities-title"><?php echo __("Attività","mapparte");?></h3>
        <div class="row">
			<?php \Mapparte\Frontend_Utils::show_activities( $args['activity'] ); ?>
        </div>
    </div>
	<?php \Mapparte\Frontend_Utils::show_frequent_activities( $args['id'] ); ?>
    <div class="space-wrapper">
        <h3 class="space-title"><?php echo __("Lo spazio","mapparte");?></h3>
        <div class="row">
			<?php if ( isset( $args['space_mq'] ) && $args['space_mq'] || isset( $args['rooms'] ) && $args['rooms'] ) : ?>
                <div class="col-md-3 col-sm-4 col-6 space-tile">
                    <img class="space-img" src="<?php echo get_template_directory_uri(); ?>/assets/images/stanze.png"
                         alt="photo">
                    <img class="divider" src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                         alt="divider">
					<?php if ( isset( $args['space_mq'] ) && $args['space_mq'] || isset( $args['rooms'] ) && $args['rooms'] ) : ?>
                    <h5 class="space-ttl">
						<?php endif; ?>
						<?php if ( isset( $args['space_mq'] ) && $args['space_mq'] ) {
							printf( "%s  M<sup>2</sup>", esc_html( $args['space_mq'] ) );
						} ?>
						<?php if ( isset( $args['rooms'] ) && $args['rooms'] ) {
                                if ($args['rooms'] == "1") 
							        printf( "<br/>%s  STANZA", esc_html( $args['rooms'] ) );
                                else
                                    printf( "<br/>%s  STANZE", esc_html( $args['rooms'] ) ); 
						} ?>
						<?php if ( isset( $args['space_mq'] ) && $args['space_mq'] || isset( $args['rooms'] ) && $args['rooms'] ) : ?>
                    </h5>
				<?php endif; ?>
                </div>
			<?php endif; ?>
			<?php if ( isset( $args['max_people'] ) && $args['max_people'] ) : ?>
                <div class="col-md-3 col-sm-4 col-6 space-tile">
                    <img class="space-img" src="<?php echo get_template_directory_uri(); ?>/assets/images/contact.png"
                         alt="photo">
                    <img class="divider" src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                         alt="divider">
                    <h5 class="space-ttl"><?php echo esc_html( $args['max_people'] ); ?></h5>
                    <p class="space-desc"><?php echo __("PERSONE","mapparte");?></p>
                </div>
			<?php endif; ?>
			<?php if ( isset( $args['accessibility'] ) && $args['accessibility'] ) : ?>
                <div class="col-md-3 col-sm-4 col-6 space-tile">
                    <img class="space-img"
                         src="<?php echo get_template_directory_uri(); ?>/assets/images/accessibile.png"
                         alt="photo">
                    <img class="divider" src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                         alt="divider">
                    <h5 class="space-ttl"><?php echo __("ACCESSIBILE<br>AI DISABILI","mapparte");?></h5>
                </div>
			<?php endif; ?>
        </div>
        <div class="space-desc mt-5">
				<?php the_content(); ?>
        </div>
		<?php if ( ! empty( $args['space_url'] ) ) : ?>
            <div class="space-rule-wrapper">
                <h4><?php echo esc_html__( 'Sito o canale social dello spazio', 'mapparte' ); ?></h4>
                <p><a href="<?php echo esc_url( $args['space_url'] ); ?>" target="_blank" rel="noopener noreferrer nofollow"><?php echo esc_html( $args['space_url'] ); ?></a></p>
            </div>
		<?php endif; ?>
        <div class="row">
			<?php if ( empty( $args['hide_availability'] ) ) : ?>
            <div class="col-md-6 timing-wrapper">
                <div class="timing-header">
                    <img class="accordion-icon"
                         src="<?php echo get_template_directory_uri(); ?>/assets/images/timing-icon.png" alt="timing">
                    <img class="divider" src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                         alt="divider">
                    <h4 class="accordion-ttl"><?php echo __("Orari di apertura","mapparte");?></h4>
                </div>
                <div class="row timing-body">
					<?php if ( isset( $args['availability'] ) ) {
						\Mapparte\Frontend_Utils::show_availability( $args['availability'] );
					} ?>
                </div>
            </div>
			<?php endif; ?>
			<?php if ( empty( $args['hide_prices'] ) ) : ?>
            <div class="col-md-6 pricing-wrapper">
                <div class="pricing-header">
                    <img class="accordion-icon"
                         src="<?php echo get_template_directory_uri(); ?>/assets/images/price-icon.png"
                         alt="price-icon">
                    <img class="divider" src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                         alt="divider">
                    <h4 class="accordion-ttl"><?php echo __("Prezzi","mapparte");?></h4>
                </div>
                <div class="row pricing-body">
					<?php if ( isset( $args['min_price_day'] ) && $args['min_price_day'] ) : ?>
                    
                        <p class="col-8"><?php echo sprintf(__( 'A partire da %s € l\'ora', 'mapparte' ),$args["price_hour"]);?></p>
					<?php endif; ?>
					<?php if ( isset( $args['min_hours'] ) && $args['min_hours'] ) : ?>
                        <p class="col-4"><?php echo __("minimo","mapparte");?> <?php echo esc_html( $args['min_hours'] ); ?> <?php echo ($args['min_hours'] == 1) ? __("ora","mapparte") : __("ore","mapparte")?></p>
					<?php endif; ?>
					<?php if ( isset( $args['min_price_day'] ) && $args['min_price_day'] ) : ?>
                        <p class="col-8"><?php echo sprintf(__( 'A partire da %s euro al giorno', 'mapparte' ),$args['min_price_day']);?> </p>
					<?php endif; ?>
                    <!--p class="col-4">8ore</p-->
					<?php if ( isset( $args['price_weekend'] ) && $args['price_weekend'] ) : ?>
                        <p class="col-8"><?php echo esc_html( $args['price_weekend'] ); ?> <?php echo __("euro a weekend","mapparte");?></p>
                        <!--p class="col-4">09.00-18.00</p-->
					<?php endif; ?>
                </div>
            </div>
			<?php endif; ?>
        </div>
        <div class="accordion" id="accordionExample">
            <div class="row">
                <div class="col-md-6 accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                aria-expanded="true" aria-controls="collapseThree">
                            <img class="accordion-icon"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/accessibility.png"
                                 alt="accessibility">
                            <img class="divider"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                                 alt="divider">
                            <h4 class="accordion-ttl"><?php echo __("Accessibilità","mapparte");?> <i class="fa fa-angle-down"
                                                                       aria-hidden="true"></i></h4>
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse"
                         aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
								<?php
								if ( isset( $args['space_access'] ) ) {
									\Mapparte\Frontend_Utils::show_additional_infos( $args['space_access'] );
								}
								?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                aria-expanded="true" aria-controls="collapseFour">
                            <img class="accordion-icon"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/accessories.png"
                                 alt="accessories">
                            <img class="divider"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                                 alt="divider">
                            <h4 class="accordion-ttl"><?php echo __("Accessori","mapparte");?> <i class="fa fa-angle-down"
                                                                   aria-hidden="true"></i></h4>
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse"
                         aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
								<?php if ( isset( $args['accessories'] ) ) {
									\Mapparte\Frontend_Utils::show_additional_infos( $args['accessories'] );
								} ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 accordion-item">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                aria-expanded="true" aria-controls="collapseFive">
                            <img class="accordion-icon"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/equipment.png"
                                 alt="equipment">
                            <img class="divider"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                                 alt="divider">
                            <h4 class="accordion-ttl"><?php echo __("Apparecchiature","mapparte");?> <i class="fa fa-angle-down"
                                                                         aria-hidden="true"></i></h4>
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse"
                         aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
								<?php if ( isset( $args['equipment_av'] ) ) {
									\Mapparte\Frontend_Utils::show_additional_infos( $args['equipment_av'] );
								} ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 accordion-item">
                    <h2 class="accordion-header" id="headingSix">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="true"
                                aria-controls="collapseSix">
                            <img class="accordion-icon"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/features.png"
                                 alt="features">
                            <img class="divider"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                                 alt="divider">
                            <h4 class="accordion-ttl"><?php echo __("Caratteristiche","mapparte");?> <i class="fa fa-angle-down"
                                                                         aria-hidden="true"></i></h4>
                        </button>
                    </h2>
                    <div id="collapseSix" class="accordion-collapse collapse"
                         aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
								<?php if ( isset( $args['features'] ) ) {
									\Mapparte\Frontend_Utils::show_additional_infos( $args['features'] );
								} ?>
                                <h5><?php echo __("Attrezzature","mapparte");?></h5>
								<?php if ( isset( $args['equipment'] ) ) {
									\Mapparte\Frontend_Utils::show_additional_infos( $args['equipment'] );
								} ?>
                                <h5><?php echo __("Aerazione","mapparte");?></h5>
								<?php if ( isset( $args['ventilation'] ) ) {
									\Mapparte\Frontend_Utils::show_additional_infos( $args['ventilation'] );
								} ?>
                                <h5><?php echo __("Illuminazione","mapparte");?></h5>
								<?php if ( isset( $args['light'] ) ) {
									\Mapparte\Frontend_Utils::show_additional_infos( $args['light'] );
								} ?>
                                <h5><?php echo __("Pavimento","mapparte");?></h5>
								<?php if ( isset( $args['floor_type'] ) ) {
									\Mapparte\Frontend_Utils::show_additional_infos( $args['floor_type'] );
								} ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 accordion-item">
                    <h2 class="accordion-header" id="headingSeven">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseSeven"
                                aria-expanded="true" aria-controls="collapseSeven">
                            <img class="accordion-icon"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/setting.png"
                                 alt="setting">
                            <img class="divider"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                                 alt="divider">
                            <h4 class="accordion-ttl"><?php echo __("Servizi","mapparte");?> <i class="fa fa-angle-down"
                                                                 aria-hidden="true"></i></h4>
                        </button>
                    </h2>
                    <div id="collapseSeven" class="accordion-collapse collapse"
                         aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
								<?php if ( isset( $args['services'] ) ) {
									\Mapparte\Frontend_Utils::show_additional_infos( $args['services'] );
								} ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 accordion-item">
                    <h2 class="accordion-header" id="headingEight">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseEight"
                                aria-expanded="true" aria-controls="collapseEight">
                            <img class="accordion-icon"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/outdoor-spaces.png"
                                 alt="outdoor-spaces">
                            <img class="divider"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                                 alt="divider">
                            <h4 class="accordion-ttl"><?php echo __("Spazi esterni","mapparte");?> <i class="fa fa-angle-down"
                                                                       aria-hidden="true"></i></h4>
                        </button>
                    </h2>
                    <div id="collapseEight" class="accordion-collapse collapse"
                         aria-labelledby="headingEight" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
								<?php if ( isset( $args['space_external'] ) ) {
									\Mapparte\Frontend_Utils::show_additional_infos( $args['space_external'] );
								} ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 accordion-item">
                    <h2 class="accordion-header" id="headingNine">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseNine"
                                aria-expanded="true" aria-controls="collapseNine">
                            <img class="accordion-icon"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/parking.png"
                                 alt="parking">
                            <img class="divider"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                                 alt="divider">
                            <h4 class="accordion-ttl"><?php echo __("Parcheggio","mapparte");?> <i class="fa fa-angle-down"
                                                                    aria-hidden="true"></i></h4>
                        </button>
                    </h2>
                    <div id="collapseNine" class="accordion-collapse collapse"
                         aria-labelledby="headingNine" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
								<?php if ( isset( $args['parking'] ) ) {
									\Mapparte\Frontend_Utils::show_additional_infos( $args['parking'] );
								} ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php if ( isset( $args['space_rules'] ) && $args['space_rules'] ) : ?>
            <div class="space-rule-wrapper">
                <h4><?php echo __("Regole dello spazio","mapparte");?></h4>
				<?php \Mapparte\Frontend_Utils::show_additional_infos( $args['space_rules'] ); ?>
            </div>
		<?php endif; ?>
		<?php if ( isset( $args['cancel_policy'] ) && $args['cancel_policy'] ) : ?>
            <div class="space-rule-wrapper">
                <h4><?php echo __("Politiche di cancellazione","mapparte");?></h4>
                <p><?php echo esc_html( $args['cancel_policy'] ); ?></p>
            </div>
		<?php endif; ?>
        <div class="col-sm-11 testimonials">
            <?php
            $author_name = get_the_author_meta( 'display_name', $post->post_author );
            $author_desc = get_the_author_meta( 'description', $post->post_author );
            $author_firstname = get_the_author_meta( 'user_firstname', $post->post_author );
            $author_lastname = get_the_author_meta( 'user_lastname', $post->post_author );

            ?>
	        <?php if ( is_user_logged_in() ) : ?>
            <h4><?php echo __("Dettagli sull'host","mapparte");?> <?php echo esc_html($author_name); ?></h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="row testimonial-tile">
                        <div class="col-sm-4 text-center">
                            <img class="testi-user-img"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/user-icon-2.png"
                                 alt="user"/>
                            <img class="divider w-75 mx-auto mt-3 mb-2"
                                 src="<?php echo get_template_directory_uri(); ?>/assets/images/divider.svg"
                                 alt="divider"/>
                            <p class="testi-user-name"><?php echo esc_html($author_firstname); ?></p>
                            <p class="testi-user-surname"><?php echo esc_html($author_lastname); ?></p>
                        </div>
                        <div class="col-sm-8 text-center text-sm-start mt-3 mt-sm-0">
                            <p class="testimonial-desc"><?php echo esc_html($author_desc); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
	        <?php endif; ?>
            <div class="col-6 col-sm-5 col-lg-4 space-btn">
				<?php if ( ! is_user_logged_in() ) : ?>
                    <a data-redirect="<?php echo get_permalink(); ?>" href="<?php echo get_permalink(); ?>"
                       class="xoo-el-login-tgr">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/btn.png" alt="btn">
                        <p><?php echo __("Per info contatta l’host","mapparte");?></p>
                    </a>
				<?php else : ?>
                    <a class="read-more" data-bs-toggle="modal" data-bs-target="#message-popup" href="#"><img
                                src="<?php echo get_template_directory_uri(); ?>/assets/images/btn.png" alt="btn">
                        <p><?php echo __("Per info contatta l’host","mapparte");?></p>
                    </a>
				<?php endif; ?>
            </div>
        </div>
        <div class="position-wrapper">
			<?php if ( isset( $args['address'] ) ) {
				\Mapparte\Frontend_Utils::show_map( $args['address'] );
			} ?>
        </div>
    </div>
</div>

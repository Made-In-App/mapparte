<!--search section start-->
<?php
$defaults = [
	'where'          => false,
	'city'           => false,
	'lat'            => false,
	'lon'            => false,
	's_activity'     => false,
	's_typology'     => false,
	'fare'           => false,
	'priceRange'     => false,
	'space_mq'       => false,
	'max_people'     => false,
	'accessibility'  => false,
	'space_access'   => false,
	'space_external' => false,
	'features'       => false,
	'floor_type'     => false,
	'rooms'          => false,
];

$args = array_merge( $defaults, $args );
?>
<form method="get" action="/spaces/">
    <section class="search-wrapper">
        <div class="container gx-5">
            <div class="row">
                <div class="col-lg-7">
                    <div class="row search-panel">
                        <div class="col-12 col-sm-4 col-6 pt-3 pt-sm-0">
                            <input class="form-control" type="text" name="where" id="where"
                                   value="<?php echo $args['where'] ?>"
                                   placeholder="<?php echo __("Dove","mapparte"); ?> ">
                            <input type="hidden" id="city" name="city"/>
                        </div>
                        <div class="col-12 col-sm-4 col-7 pt-3 pt-sm-0">
							<?php \Mapparte\Frontend_Utils::get_taxonomy_select( 'activity', 'activity', 'Cosa...', $args['s_activity'] ); ?>
                        </div>
                        <div class="col-10 col-sm-3 col-8 pt-3 pt-sm-0">
                            <span class="form-control more-filter-btn"><?php echo __("Filtri","mapparte"); ?> 
                                <div class="ms-auto more-filter-img">
                                    <i class="fas fa-angle-down"></i>
                                    <i class="fas fa-times"></i>
                                </div>
                            </span>
                        </div>
                        <div class="action-btn col-2 col-sm-1 pt-3 pt-sm-0">
                            <button type="submit" class="btn">
                                <img class="search-btn"
                                     src="<?php echo get_template_directory_uri(); ?>/assets/images/search-icn.png"
                                     alt="search">
                                <img class="search-btn-wht"
                                     src="<?php echo get_template_directory_uri(); ?>/assets/images/search-icn-wht.png"
                                     alt="search">
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 pt-3 pt-lg-0">
                    <div class="row search-panel justify-content-between">
                        <div class="col-sm-5">
                            <button type="button" data-redirect="/spaces/"
                                    class="form-control map xoo-el-login-tgr <?php echo ( is_user_logged_in() ) ? "invisible" : "" ?>">
                                    <?php echo __("Cerca sulla mappa","mapparte"); ?> 
                            </button>
                        </div>
                        <!--
						<div class="col-sm-6 d-flex align-items-center pt-3 pt-sm-0">
							<p class="sort-by-ttl">Ordina per</p>
							<select class="form-select">
								<option selected>distanza</option>
								<option value="1">prezzo</option>
								<option value="2">nome struttura</option>
							</select>
						</div>
						-->
                    </div>
                </div>
                <div class="search-result">
                    <p><?php echo __("TROVATI","mapparte"); ?>  <span><?php echo $GLOBALS['wp_query']->found_posts; ?></span> <?php echo __("RISULTATI","mapparte"); ?> </p>
                </div>
            </div>
            <!-- <span class="more-filter-btn-overly"></span> -->
            <div class="filter-options-wrapper">
                <div class="container gx-5">
                    <div class="row price-wrapper align-items-end">
                        <div class="col-md-4 d-flex align-items-center justify-content-center justify-content-md-start">
                            <p class="sort-by-ttl"><?php echo __("Cerca per tariffa","mapparte"); ?> </p>
                            <select class="form-select" name="fare" id="fare">
                                <option selected value=""><?php echo __("Seleziona una tariffa","mapparte"); ?></option>
                                <option value="1" <?php if ( $args["fare"] == "1" )
									echo "selected='selected'" ?>><?php echo __("oraria infrasettimanale","mapparte"); ?>
                                </option>
                                <option value="2" <?php if ( $args["fare"] == "2" )
									echo "selected='selected'" ?>><?php echo __("oraria weekend","mapparte"); ?>
                                </option>
                                <option value="3" <?php if ( $args["fare"] == "3" )
									echo "selected='selected'" ?>><?php echo __("tariffa giornaliera","mapparte"); ?>
                                </option>
                                <option value="4" <?php if ( $args["fare"] == "4" )
									echo "selected='selected'" ?>><?php echo __("tariffa weekend","mapparte"); ?>
                                </option>
                            </select>
                        </div>
                        <div class="col-md-8 pt-3 pt-md-0">
                            <h6 class="ort-by-ttl"><?php echo __("Prezzo","mapparte"); ?></h6>
                            <input type="text" id="priceRange" name="priceRange"
                                   value="<?php echo $args['priceRange'] ?>"/>
							<?php
							$vettRange = ( $args['priceRange'] ) ? explode( ";", $args['priceRange'] ) : [
								'0',
								'1000'
							];
							?>
                            <script>
                                jQuery(document).ready(function () {
                                    jQuery("#priceRange").ionRangeSlider({
                                        type: "double",
                                        grid: false,
                                        min: 0,
                                        max: 1000,
                                        from: <?php echo $vettRange[0];?>,
                                        to: <?php echo $vettRange[1];?>,
                                        postfix: " €"
                                    });
                                });
                            </script>
                        </div>
                    </div>
                    <div class="row price-wrapper align-items-end">
                        <div class="col-12 col-lg-4 filter-tile">
                            <div class="form-floating input-group">
                                <input type="number" name="space_mq" id="space_mq" max=""
                                       value="<?php echo $args['space_mq'] ?>" class="form-control"
                                       placeholder="<?php echo __("Larghezza","mapparte"); ?>">
                                <label for="floatingInput"><?php echo __("Dimensioni in mq","mapparte"); ?></label>
                                <input type="button" value="+" class="button-plus" data-field="space_mq">
                                <input type="button" value="-" class="button-minus" data-field="space_mq">
                            </div>
                        </div>

                        <div class="col-12 col-lg-4 filter-tile">
                            <div class="form-floating input-group">
                                <input type="number" name="max_people" max="" value="<?php echo $args['max_people'] ?>"
                                       class="form-control" id="floatingInput" placeholder="<?php echo __("Larghezza","mapparte"); ?>">
                                <label for="floatingInput"><?php echo __("Capienza massima persone","mapparte"); ?></label>
                                <input type="button" value="+" class="button-plus" data-field="max_people">
                                <input type="button" value="-" class="button-minus" data-field="max_people">
                            </div>
                        </div>

                        <div class="col-12 col-lg-4 filter-tile">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" <?php echo ( $args['accessibility'] == 1 ) ? "checked='checked'" : "" ?>
                                           type="checkbox" value="1" id="accessibility" name="accessibility">
                                           <?php echo __("Accessibilità per disabili","mapparte"); ?>
                                </label>
                            </div>

                        </div>
                    </div>
                    <div class="filter-tiles">
                        <div class="row">
                            <div class="col-sm-4 col-md-3 col-lg-2 filter-tile">
                                <p class="filter-ttl"><?php echo __("Tipologia","mapparte"); ?></p>
                                <div class="filter-lists">
									<?php \Mapparte\Frontend_Utils::get_taxonomy_checkbox( 'typology', $args['s_typology'] ); ?>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-2 filter-tile">
                                <p class="filter-ttl"><?php echo __("Accessibilità","mapparte"); ?></p>

                                <div class="filter-lists">
									<?php \Mapparte\Frontend_Utils::get_acf_choices_by_field_name( 'space_access', $args['space_access'] ); ?>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-2 filter-tile">
                                <p class="filter-ttl"><?php echo __("Spazio esterno","mapparte"); ?></p>
                                <div class="filter-lists">
									<?php \Mapparte\Frontend_Utils::get_acf_choices_by_field_name( 'space_external', $args['space_external'] ); ?>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-2 filter-tile">
                                <p class="filter-ttl"><?php echo __("Caratteristiche","mapparte"); ?></p>
                                <div class="filter-lists">
									<?php \Mapparte\Frontend_Utils::get_acf_choices_by_field_name( 'features', $args['features'] ); ?>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3 col-lg-2 filter-tile">
                                <p class="filter-ttl"><?php echo __("Pavimento","mapparte"); ?></p>
                                <div class="filter-lists">
									<?php \Mapparte\Frontend_Utils::get_acf_choices_by_field_name( 'floor_type', $args['floor_type'] ); ?>
                                </div>
                            </div>

                            <div class="col-sm-4 col-md-3 col-lg-2 filter-tile">
                                <p class="filter-ttl"><?php echo __("N. sale","mapparte"); ?></p>
                                <div class="filter-lists">
									<?php \Mapparte\Frontend_Utils::get_acf_choices_by_field_name( 'rooms', $args['rooms'] ); ?>
                                </div>
                            </div>


                            <!--div class="col-sm-4 col-md-3 col-lg-2 filter-tile">
								<p class="filter-ttl">Illuminazione</p>
								<div class="filter-lists">

								</div>
							</div-->
                            <div class="d-flex align-items-center justify-content-center submit-btns">
                                <button type="submit" class="btn btn-outline-secondary"><?php echo __("Ripristina","mapparte"); ?></button>
                                <button type="submit" class="btn btn-outline-primary"><?php echo __("applica filtri","mapparte"); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="more-filter-btn-overly">
                    <div class="ms-auto more-filter-img">
                        <i class="fas fa-angle-down"></i>
                        <i class="fas fa-times"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
<!--search section end-->

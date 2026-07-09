<?php
/**
 * Template Name: Spazio
 */
get_header();
?>
 <!--banner section start-->
 <section class="banner-wrapper">
        <div class="detail-logged-slider owl-theme owl-carousel">
            <div class="item">
                <img class="d-none d-md-block" src="<?php echo get_template_directory_uri();?>/assets/images/magazine-detail.png" alt="slider" />
                <img class="d-block d-md-none" src="<?php echo get_template_directory_uri();?>/assets/images/magazine-detail-mbl.jpg" alt="slider" />
            </div>
            <div class="item">
                <img class="d-none d-md-block" src="<?php echo get_template_directory_uri();?>/assets/images/magazine-detail.png" alt="slider" />
                <img class="d-block d-md-none" src="<?php echo get_template_directory_uri();?>/assets/images/magazine-detail-mbl.jpg" alt="slider" />
            </div>
            <div class="item">
                <img class="d-none d-md-block" src="<?php echo get_template_directory_uri();?>/assets/images/magazine-detail.png" alt="slider" />
                <img class="d-block d-md-none" src="<?php echo get_template_directory_uri();?>/assets/images/magazine-detail-mbl.jpg" alt="slider" />
            </div>
        </div>
    </section>
    <!--banner section end-->

    <!--breadcum section start-->
    <section class="breadcum-wrapper">
        <div class="container gx-5">
            <?php get_template_part( 'template-parts/content/social-icons' );?>
            <ul class="breadcum-items d-flex align-items-center">
                <li class="breadcum-item"><a class="breadcum-link" href="index.html">Home</a></li>
                <li class="breadcum-item">&nbsp;/ <a class="breadcum-link" href="#">percorso</a></li>
                <li class="breadcum-item">&nbsp;/ percorso</li>
            </ul>
        </div>
    </section>
    <!--breadcum section end-->

    <!--detail section start-->
    <section class="detail-wrapper">
        <div class="container gx-5">
            <div class="row">
                <div class="col-md-7 px-0">
                    <h1 class="detail-ttl">Galleria di Sophie</h1>
                    <div class="d-flex align-items-center review-wrapper">
                        <img class="review" src="<?php echo get_template_directory_uri();?>/assets/images/star.svg" alt="star">
                        <h5 class="review-count">4.5</h5>
                        <div class="wishlist-btn">
                            <?php echo get_favorites_button(get_the_ID());?>
                        </div>
                    </div>
                    <div class="activities-wrapper">
                        <h3 class="activities-title">Attività</h3>
                        <div class="row">
                            <div class="col activities-tile">
                                <img class="activities-img" src="<?php echo get_template_directory_uri();?>/assets/images/photo.png" alt="photo">
                                <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                <h5 class="activities-ttl">PHOTO</h5>
                                <p class="activities-desc">STUDIO</p>
                            </div>
                            <div class="col activities-tile">
                                <img class="activities-img" src="<?php echo get_template_directory_uri();?>/assets/images/exhibition.png" alt="photo">
                                <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                <h5 class="activities-ttl">Exhibition</h5>
                            </div>
                            <div class="col activities-tile">
                                <img class="activities-img" src="<?php echo get_template_directory_uri();?>/assets/images/film.png" alt="photo">
                                <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                <h5 class="activities-ttl">FILM</h5>
                                <p class="activities-desc">STUDIO</p>
                            </div>
                            <div class="col activities-tile">
                                <img class="activities-img" src="<?php echo get_template_directory_uri();?>/assets/images/danza.png" alt="photo">
                                <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                <h5 class="activities-ttl">DANZA</h5>
                            </div>
                        </div>
                    </div>
                    <div class="activities-wrapper">
                        <h3 class="activities-title">Spesso prenotato per</h3>
                        <div class="row">
                            <div class="col activities-tile">
                                <img class="activities-img" src="<?php echo get_template_directory_uri();?>/assets/images/exhibition.png" alt="photo">
                                <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                <h5 class="activities-ttl">Exhibition</h5>
                            </div>
                        </div>
                    </div>
                    <div class="space-wrapper">
                        <h3 class="space-title">Lo spazio</h3>
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-6 space-tile">
                                <img class="space-img" src="<?php echo get_template_directory_uri();?>/assets/images/stanze.png" alt="photo">
                                <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                <h5 class="space-ttl">288 M<sup>2</sup><br />4 STANZE</h5>
                            </div>
                            <div class="col-md-3 col-sm-4 col-6 space-tile">
                                <img class="space-img" src="<?php echo get_template_directory_uri();?>/assets/images/contact.png" alt="photo">
                                <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                <h5 class="space-ttl">40</h5>
                                <p class="space-desc">PERSONE</p>
                            </div>
                            <div class="col-md-3 col-sm-4 col-6 space-tile">
                                <img class="space-img" src="<?php echo get_template_directory_uri();?>/assets/images/accessibile.png" alt="photo">
                                <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                <h5 class="space-ttl">ACCESSIBILE<br />AI DISABILI</h5>
                            </div>
                        </div>
                        <p class="space-desc mt-5">Il et milles as magnate pre sam quas ducidit etur sequiat ibusda qui
                            atent ipiscimint fuga. Adio omnis con pelent li cilia tianimus, videlit et es verrovit harum
                            quo mil ea dollam nos atem exeribeaquis et lam volorerspero magnim esti ad ma es magnat quo
                            quaspernam accus volore nem et a veris eveliquat voleste mpeliquam, qui dent, corio eost
                            libus, auditius modi coneseque rent.</p>
                        <p class="space-desc">Mus sint ilitate niatet quatur accae nobit earcidem quis sumendio ex
                            excessi tiasitas eos re vendips aessimin cum doluptiorest ipit quuntur?</p>
                        <p class="space-desc mb-5">Aque veles consero vent as qui asperibera qui comnihi llatiat ut
                            optatem odicae labore dus que pratibus quas est volor</p>
                        <div class="row">
                            <div class="col-md-6 timing-wrapper">
                                <div class="timing-header">
                                    <img class="accordion-icon" src="<?php echo get_template_directory_uri();?>/assets/images/timing-icon.png" alt="timing">
                                    <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                    <h4 class="accordion-ttl">Orari di apertura</h4>
                                </div>
                                <div class="row timing-body">
                                    <p class="col-5">Lunedì</p>
                                    <p class="col-7">08.00-18.00</p>
                                    <p class="col-5">Martedì</p>
                                    <p class="col-7">08.00-18.00</p>
                                    <p class="col-5">Mercoledì</p>
                                    <p class="col-7">08.00-18.00</p>
                                    <p class="col-5">Giovedì</p>
                                    <p class="col-7">08.00-18.00</p>
                                    <p class="col-5">Venerdì</p>
                                    <p class="col-7">08.00-18.00</p>
                                    <p class="col-5">Sabato</p>
                                    <p class="col-7">08.00-18.00</p>
                                    <p class="col-5">Domenica</p>
                                    <p class="col-7">08.00-18.00</p>
                                </div>
                            </div>
                            <div class="col-md-6 pricing-wrapper">
                                <div class="pricing-header">
                                    <img class="accordion-icon" src="<?php echo get_template_directory_uri();?>/assets/images/price-icon.png" alt="price-icon">
                                    <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                    <h4 class="accordion-ttl">Prezzi</h4>
                                </div>
                                <div class="row pricing-body">
                                    <p class="col-8">180 euro</p>
                                    <p class="col-4">minimo 3 ore</p>
                                    <p class="col-8">880 euro al giorno</p>
                                    <p class="col-4">8ore</p>
                                    <p class="col-8">1000 euro a weekend</p>
                                    <p class="col-4">09.00-18.00</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion" id="accordionExample">
                            <div class="row">
                                <div class="col-md-6 accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                            aria-expanded="true" aria-controls="collapseThree">
                                            <img class="accordion-icon" src="<?php echo get_template_directory_uri();?>/assets/images/accessibility.png"
                                                alt="accessibility">
                                            <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                            <h4 class="accordion-ttl">Accessibilità <i class="fa fa-angle-down"
                                                    aria-hidden="true"></i> </h4>
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Pavimento</p>
                                                <p class="col-4 text-end">legno</p>
                                                <p class="col-8">Privacy</p>
                                                <p class="col-4 text-end">Condiviso</p>
                                                <p class="col-8">Illuminazione</p>
                                                <p class="col-4 text-end">neon</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
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
                                            <img class="accordion-icon" src="<?php echo get_template_directory_uri();?>/assets/images/accessories.png" alt="accessories">
                                            <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                            <h4 class="accordion-ttl">Accessori <i class="fa fa-angle-down"
                                                    aria-hidden="true"></i> </h4>
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse"
                                        aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Pavimento</p>
                                                <p class="col-4 text-end">legno</p>
                                                <p class="col-8">Privacy</p>
                                                <p class="col-4 text-end">Condiviso</p>
                                                <p class="col-8">Illuminazione</p>
                                                <p class="col-4 text-end">neon</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 accordion-item">
                                    <h2 class="accordion-header" id="headingFive">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                            aria-expanded="true" aria-controls="collapseFive">
                                            <img class="accordion-icon" src="<?php echo get_template_directory_uri();?>/assets/images/equipment.png" alt="equipment">
                                            <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                            <h4 class="accordion-ttl">Apparecchiature <i class="fa fa-angle-down"
                                                    aria-hidden="true"></i> </h4>
                                        </button>
                                    </h2>
                                    <div id="collapseFive" class="accordion-collapse collapse"
                                        aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Pavimento</p>
                                                <p class="col-4 text-end">legno</p>
                                                <p class="col-8">Privacy</p>
                                                <p class="col-4 text-end">Condiviso</p>
                                                <p class="col-8">Illuminazione</p>
                                                <p class="col-4 text-end">neon</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 accordion-item">
                                    <h2 class="accordion-header" id="headingSix">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="true"
                                            aria-controls="collapseSix">
                                            <img class="accordion-icon" src="<?php echo get_template_directory_uri();?>/assets/images/features.png" alt="features">
                                            <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                            <h4 class="accordion-ttl">Caratteristiche <i class="fa fa-angle-down"
                                                    aria-hidden="true"></i> </h4>
                                        </button>
                                    </h2>
                                    <div id="collapseSix" class="accordion-collapse collapse"
                                        aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Pavimento</p>
                                                <p class="col-4 text-end">legno</p>
                                                <p class="col-8">Privacy</p>
                                                <p class="col-4 text-end">Condiviso</p>
                                                <p class="col-8">Illuminazione</p>
                                                <p class="col-4 text-end">neon</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 accordion-item">
                                    <h2 class="accordion-header" id="headingSeven">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseSeven"
                                            aria-expanded="true" aria-controls="collapseSeven">
                                            <img class="accordion-icon" src="<?php echo get_template_directory_uri();?>/assets/images/setting.png" alt="setting">
                                            <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                            <h4 class="accordion-ttl">Servizi <i class="fa fa-angle-down"
                                                    aria-hidden="true"></i> </h4>
                                        </button>
                                    </h2>
                                    <div id="collapseSeven" class="accordion-collapse collapse"
                                        aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Pavimento</p>
                                                <p class="col-4 text-end">legno</p>
                                                <p class="col-8">Privacy</p>
                                                <p class="col-4 text-end">Condiviso</p>
                                                <p class="col-8">Illuminazione</p>
                                                <p class="col-4 text-end">neon</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 accordion-item">
                                    <h2 class="accordion-header" id="headingEight">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseEight"
                                            aria-expanded="true" aria-controls="collapseEight">
                                            <img class="accordion-icon" src="<?php echo get_template_directory_uri();?>/assets/images/outdoor-spaces.png"
                                                alt="outdoor-spaces">
                                            <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                            <h4 class="accordion-ttl">Spazi esterni <i class="fa fa-angle-down"
                                                    aria-hidden="true"></i> </h4>
                                        </button>
                                    </h2>
                                    <div id="collapseEight" class="accordion-collapse collapse"
                                        aria-labelledby="headingEight" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Pavimento</p>
                                                <p class="col-4 text-end">legno</p>
                                                <p class="col-8">Privacy</p>
                                                <p class="col-4 text-end">Condiviso</p>
                                                <p class="col-8">Illuminazione</p>
                                                <p class="col-4 text-end">neon</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 accordion-item">
                                    <h2 class="accordion-header" id="headingNine">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseNine"
                                            aria-expanded="true" aria-controls="collapseNine">
                                            <img class="accordion-icon" src="<?php echo get_template_directory_uri();?>/assets/images/parking.png" alt="parking">
                                            <img class="divider" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg" alt="divider">
                                            <h4 class="accordion-ttl">Parcheggio <i class="fa fa-angle-down"
                                                    aria-hidden="true"></i> </h4>
                                        </button>
                                    </h2>
                                    <div id="collapseNine" class="accordion-collapse collapse"
                                        aria-labelledby="headingNine" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Pavimento</p>
                                                <p class="col-4 text-end">legno</p>
                                                <p class="col-8">Privacy</p>
                                                <p class="col-4 text-end">Condiviso</p>
                                                <p class="col-8">Illuminazione</p>
                                                <p class="col-4 text-end">neon</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                                <p class="col-8">Prese a muro</p>
                                                <p class="col-4 text-end">7</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="space-rule-wrapper">
                            <h4>Regole dello spazio</h4>
                            <p>Il et milles as magnate pre sam quas ducidit etur sequiat ibusda qui atent ipiscimint
                                fuga. Adio omnis con pelent licilia tianimus, videlit et es verrovit harum quo mil ea
                                dollam nos atem exeribeaquis et lam volorerspero magnim esti ad ma es magnat quo qua
                                spernam accus volore nem et a veris eveliquat voleste mpeliquam, qui dent, corio eost
                                libus, auditius modi coneseque rent.</p>
                            <p>Mus sint ilitate niatet quatur accae nobit earcidem quis sumendio ex excessi tiasitas eos
                                re vendips aessimin cum doluptiorest ipit quuntur?</p>
                            <p>Aque veles consero vent as qui asperibera qui comnihi llatiat ut optatem odicae labore
                                dus que pratibus quas est volor</p>
                        </div>
                        <div class="space-rule-wrapper">
                            <h4>COVID-19</h4>
                            <p>Il et milles as magnate pre sam quas ducidit etur sequiat ibusda qui atent ipiscimint
                                fuga. Adio omnis con pelent licilia tianimus, videlit et es verrovit harum quo mil ea
                                dollam nos atem exeribeaquis et lam volorerspero magnim esti ad ma es magnat quo qua
                                spernam accus volore nem et a veris eveliquat voleste mpeliquam, qui dent, corio eost
                                libus, auditius modi coneseque rent.</p>
                        </div>
                        <div class="space-rule-wrapper">
                            <h4>Politiche di cancellazione</h4>
                            <p>Mus sint ilitate niatet quatur accae nobit earcidem quis sumendio ex excessi tiasitas eos
                                re vendips aessimin cum doluptiorest ipit quuntur?</p>
                            <p>Aque veles consero vent as qui asperibera qui comnihi llatiat ut optatem odicae labore
                                dus que pratibus quas est volor</p>
                        </div>
                        <div class="col-sm-11 testimonials">
                            <h4>Dettagli sull'host Sophie</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row testimonial-tile">
                                        <div class="col-sm-4 text-center">
                                            <img class="testi-user-img" src="<?php echo get_template_directory_uri();?>/assets/images/user-icon-2.png" alt="user" />
                                            <img class="divider w-75 mx-auto mt-3 mb-2" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg"
                                                alt="divider" />
                                            <p class="testi-user-name">Nome</p>
                                            <p class="testi-user-surname">Cognome</p>
                                        </div>
                                        <div class="col-sm-8 text-center text-sm-start mt-3 mt-sm-0">
                                            <p class="testimonial-desc">Ellique int. Ullab id es arum anderu mquat. dund
                                                usdanis
                                                restis eaqui ul lutem porpore sin cusam eost quatio. It esciument
                                                assitat
                                                aut ea volupta
                                                commodion porerro mo odipisi ta quibus pra derume.Umqui ullesedit
                                                volestrum
                                                que sit ver
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 d-none">
                                    <div class="row testimonial-tile">
                                        <div class="col-sm-4 text-center">
                                            <img class="testi-user-img" src="<?php echo get_template_directory_uri();?>/assets/images/user-icon-2.png" alt="user" />
                                            <img class="divider w-75 mx-auto mt-3 mb-2" src="<?php echo get_template_directory_uri();?>/assets/images/divider.svg"
                                                alt="divider" />
                                            <p class="testi-user-name">Nome</p>
                                            <p class="testi-user-surname">Cognome</p>
                                        </div>
                                        <div class="col-sm-8 text-center text-sm-start mt-3 mt-sm-0">
                                            <p class="testimonial-desc">Ellique int. Ullab id es arum anderu mquat. dund
                                                usdanis
                                                restis eaqui ul lutem porpore sin cusam eost quatio. It esciument
                                                assitat
                                                aut ea volupta
                                                commodion porerro mo odipisi ta quibus pra derume.Umqui ullesedit
                                                volestrum
                                                que sit ver
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-sm-5 col-lg-4 space-btn">
                                <a class="read-more" href="#"><img src="<?php echo get_template_directory_uri();?>/assets/images/btn.png" alt="btn">
                                    <p>Contatta l’host</p>
                                </a>
                            </div>
                        </div>
                        <div class="position-wrapper">
                            <div class="position-header">
                                <img src="<?php echo get_template_directory_uri();?>/assets/images/position-icon.png" alt="position">
                                <p class="address">Via della Via paese della vai</p>
                            </div>
                            <img class="divider mt-3" src="<?php echo get_template_directory_uri();?>/assets/images/position-divider.png" alt="divider" />
                            <div class="position-body">
                                <h4>Posizione</h4>
                                <img class="position-img" src="<?php echo get_template_directory_uri();?>/assets/images/position.png" alt="">
                                <h4 class="position-ttl">Dettagli sul processo di prenotazione e pagamento</h4>
                                <p>Il et milles as magnate pre sam quas ducidit etur sequiat ibusda qui atent ipiscimint
                                    fuga. Adio omnis con pelent licilia tianimus, videlit et es verrovit harum quo mil
                                    ea dollam nos atem exeribeaquis et lam volorerspero magnim esti ad ma es magnat quo
                                    qua spernam accus volore nem et a veris eveliquat voleste mpeliquam, qui dent, corio
                                    eost libus, auditius modi coneseque rent.</p>
                                <p>Mus sint ilitate niatet quatur accae nobit earcidem quis sumendio ex excessi tiasitas
                                    eos re vendips aessimin cum doluptiorest ipit quuntur?</p>
                                <p>Aque veles consero vent as qui asperibera qui comnihi llatiat ut optatem odicae
                                    labore dus que pratibus quas est volor</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="booking-form-wrapper d-none d-sm-block">
                        <h5 class="booking-form-ttl">A partira da <span>160 € l'ora</span> </h5>
                        <form class="row booking-form">
                            <div class="mb-3 col-12 px-0">
                                <label for="pianificando" class="form-label">Cosa stai pianificando?</label>
                                <input type="text" class="form-control" id="pianificando">
                            </div>
                            <div class="time-slot-wrapper">
                                <div class="mb-3 time-slot-tile px-0">
                                    <label for="date" class="form-label">Data</label>
                                    <input type="date" class="form-control" id="date">
                                </div>
                                <div class="mb-3 time-slot-tile px-0">
                                    <label for="date" class="form-label">Orario inizio</label>
                                    <select class="form-control form-select" aria-label="Default select example">
                                        <option selected>9:00</option>
                                        <option value="1">9:30</option>
                                        <option value="2">10:00</option>
                                        <option value="3">10:30</option>
                                    </select>
                                </div>
                                <div class="mb-3 time-slot-tile d-none px-0">
                                    <label for="date" class="form-label">Data</label>
                                    <input type="date" class="form-control" id="date">
                                </div>
                                <div class="mb-3 time-slot-tile px-0">
                                    <label for="date" class="form-label">Orario fine</label>
                                    <select class="form-control form-select" aria-label="Default select example">
                                        <option selected>21:00</option>
                                        <option value="1">21:30</option>
                                        <option value="2">22:00</option>
                                        <option value="3">22:30</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 pe-0 form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">più giorni</label>
                            </div>
                            <div class="col-sm-8 mb-3 px-0">
                                <label for="floatingInput" class="form-label">Numero ospiti</label>
                                <div class="input-group">
                                    <input type="number" name="quantity" max="" class="form-control" id="floatingInput"
                                        placeholder="30">
                                    <input type="button" value="+" class="button-plus" data-field="quantity">
                                    <input type="button" value="-" class="button-minus" data-field="quantity">
                                </div>
                            </div>
                            <div class="mb-3 col-12 px-0">
                                <label for="coupon" class="form-label">Possiedi un coupon?</label>
                                <input type="text" class="form-control" id="coupon">
                            </div>
                            <div class="col-12 px-0 estimeted-price-wrapper">
                                <h4 class="estimeted-price-ttl">Prezzo stimato</h4>
                                <div class="estimeted-price">
                                    <p>160 € X 12 ore</p>
                                    <h4>1920,00 €</h4>
                                </div>
                            </div>
                            <div class="mb-3 col-12 px-0">
                                <label for="message" class="form-label">invia un messaggio all'host</label>
                                <textarea class="form-control" name="message" id="message"></textarea>
                            </div>
                            <div class="mb-3 col-12 px-0 action-btn">
                                <button type="button" class="btn btn-secondary">invia richiesta prenotazione</button>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#message-popup"
                                    class="btn btn-secondary-outline">Contatta L'host</button>
                            </div>
                        </form>
                    </div>
                    <!--form modal start-->
                    <div class="modal fade" id="form-popup" tabindex="-1" aria-labelledby="form-popupLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="booking-form-wrapper">
                                    <h5 class="booking-form-ttl">A partira da <span>160 € l'ora</span> </h5>
                                    <form class="row booking-form">
                                        <div class="mb-3 col-12 px-0">
                                            <label for="pianificando" class="form-label">Cosa stai pianificando?</label>
                                            <input type="text" class="form-control" id="pianificando">
                                        </div>
                                        <div class="time-slot-wrapper">
                                            <div class="mb-3 time-slot-tile px-0">
                                                <label for="date" class="form-label">Data</label>
                                                <input type="date" class="form-control" id="date">
                                            </div>
                                            <div class="mb-3 time-slot-tile px-0">
                                                <label for="date" class="form-label">Orario inizio</label>
                                                <select class="form-control form-select"
                                                    aria-label="Default select example">
                                                    <option selected>9:00</option>
                                                    <option value="1">9:30</option>
                                                    <option value="2">10:00</option>
                                                    <option value="3">10:30</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 time-slot-tile d-none px-0">
                                                <label for="date" class="form-label">Data</label>
                                                <input type="date" class="form-control" id="date">
                                            </div>
                                            <div class="mb-3 time-slot-tile px-0">
                                                <label for="date" class="form-label">Orario fine</label>
                                                <select class="form-control form-select"
                                                    aria-label="Default select example">
                                                    <option selected>21:00</option>
                                                    <option value="1">21:30</option>
                                                    <option value="2">22:00</option>
                                                    <option value="3">22:30</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 pe-0 form-check">
                                            <input type="checkbox" class="form-check-input" id="exampleCheck2">
                                            <label class="form-check-label" for="exampleCheck2">più giorni</label>
                                        </div>
                                        <div class="col-8 mb-3 px-0">
                                            <label for="floatingInput" class="form-label">Numero ospiti</label>
                                            <div class="input-group">
                                                <input type="number" name="quantity" max="" class="form-control"
                                                    id="floatingInput" placeholder="30">
                                                <input type="button" value="+" class="button-plus"
                                                    data-field="quantity">
                                                <input type="button" value="-" class="button-minus"
                                                    data-field="quantity">
                                            </div>
                                        </div>
                                        <div class="mb-3 col-12 px-0">
                                            <label for="coupon" class="form-label">Possiedi un coupon?</label>
                                            <input type="text" class="form-control" id="coupon">
                                        </div>
                                        <div class="col-12 px-0 estimeted-price-wrapper">
                                            <h4 class="estimeted-price-ttl">Prezzo stimato</h4>
                                            <div class="estimeted-price">
                                                <p>160 € X 12 ore</p>
                                                <h4>1920,00 €</h4>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-12 px-0">
                                            <label for="message" class="form-label">invia un messaggio all'host</label>
                                            <textarea class="form-control" name="message" id="message"></textarea>
                                        </div>
                                        <div class="mb-3 col-12 px-0 action-btn">
                                            <button type="button" class="btn btn-secondary">invia richiesta
                                                prenotazione</button>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#message-popup"
                                                class="btn btn-secondary-outline">Contatta L'host</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <!--form modal end-->
                    <div class="row mx-0 d-sm-none mbl-action-btn">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#form-popup"
                            class="btn btn-secondary col-6">invia
                            richiesta prenotazione</button>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#message-popup"
                            class="btn btn-secondary-outline col-6">Contatta L'host</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--detail section end-->
     <!--message modal start-->
     <div class="modal fade" id="message-popup" tabindex="-1" aria-labelledby="message-popupLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="row">
                    <div class="col-md-5">
                        <div class="modal-header">
                            <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                            <div class="user-icon-wrapper">
                                <img class="user-icon" src="<?php echo get_template_directory_uri();?>/assets/images/msg-user.png" alt="user">
                            </div>
                            <h4 class="user-name">Sophie</h4>
                            <h6 class="user-desc">Registrato da dicembre 2020</h6>
                            <ul class="services-lists">
                                <li class="active"><a href="#">Scrivi un messaggio per</a></li>
                                <li><a href="#">Fornire il maggior numero di dettagli</a></li>
                                <li><a href="#">Organizzare una visita</a></li>
                                <li><a href="#">Chiedere maggiori informazioni</a></li>
                                <li><a href="#">Comunicare il tuo budget</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="modal-body">
                            <h4 class="message-ttl">Messaggio per l'host</h4>
                            <p class="message-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis
                                aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
                                pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                                deserunt mollit anim id est laborum.</p>
                            <form class="message-form">
                                <div class="mb-3 form-tile">
                                    <textarea class="form-control"
                                        placeholder="Scrivi qui li tuo messaggio."></textarea>
                                </div>
                                <div class="d-flex justify-content-end action-btn">
                                    <button type="button" class="btn btn-secondary">Annulla</button>
                                    <button type="button" class="btn btn-outline-primary">invia messaggio</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div> -->
            </div>
        </div>
    </div>
    <!--message modal end-->
<?php
get_footer();

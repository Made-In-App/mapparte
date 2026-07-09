<!--featured section start-->
<section class="featured-wrapper">
        <img src="<?php echo get_template_directory_uri();?>/assets/images/search-bg-half.webp" alt="featured" />
        <div class="container gx-5">
        <?php if ($GLOBALS['wp_query']->post_count == 0 ) {?>
        <div class="row text-center">
            <h3><?php echo __("Non sono stati trovati risultati, riprovare modificando i filtri","mapparte"); ?> </h3>
        </div>
        <?php }?>
        <?php if ($GLOBALS['wp_query']->post_count > 0 ) {?>
            <div class="row featured-map">
                <div class="col-lg-8 order-lg-1 order-2">
                    <div class="featured-tiles row">
                        <?php if( have_posts() ) : while(have_posts()) : the_post();
                         include(locate_template('template-parts/search/card.php', false, false));
                         endwhile; endif; 
                         get_template_part( 'template-parts/magazine/pagination' );
                         ?>
                    </div>
                </div>
                <div class="col-lg-4 order-lg-2 order-1 map-wrapper d-sm-block d-none">
                    <!-- <div class="map-tile">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d3493734.2086074823!2d9.47635343823479!3d44.02114080899458!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sGalleria%20di%20italy!5e0!3m2!1sen!2sin!4v1613985427667!5m2!1sen!2sin"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div> -->
                    <div id="default" style="width:100%; height:100%"></div>
                    <?php if(have_posts()) : while(have_posts()) : the_post(); 
                    include(locate_template('template-parts/search/card-map.php', false, false));
                    endwhile; endif; ?>
                </div>
            </div>
            <?php }?>
        </div>
    </section>
    <!--featured section end-->
    <?php if ($GLOBALS['wp_query']->post_count > 0 ) {?>
    <script>
    window.addEventListener('DOMContentLoaded', (event) => {
            var locations = [
                <?php if( have_posts() ) : while( have_posts() ) : the_post();
	            if ( get_field("address") ) : echo "['title',".get_field("address")["lat"].",".get_field("address")["lng"]."],";
	            endif;
	            endwhile; endif; ?>
                ['title', 0, 0]
            ];

            function initialize() {
                var myOptions = {
                    ///center: new google.maps.LatLng(41.902782, 12.496366),
                    zoom: 12,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                var bounds = new google.maps.LatLngBounds();
                var map = new google.maps.Map(document.getElementById("default"),
                    myOptions);
                    /*
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                        map.setCenter(initialLocation);
                    });
                }
                */
                var markers = setMarkers(map, locations,bounds);
            
                map.fitBounds(bounds);
                map.panToBounds(bounds); 
            }
            function setMarkers(map, locations, bounds) {
                var  marker, i;
                var markers = [];
                
                for (i = 0; i < locations.length-1; i++) {

                    var loan = locations[i][0]
                    var lat = locations[i][1]
                    var long = locations[i][2]
                    var maptitle = document.getElementsByClassName("map-title")[i].innerHTML;

                    
                    latlngset = new google.maps.LatLng(lat, long);
                    bounds.extend(latlngset);
                    var marker = new google.maps.Marker({
                        map: map, title: maptitle, position: latlngset
                    });
                    markers.push(marker);
                    map.setCenter(marker.getPosition())
                    var content = document.getElementsByClassName("map-content")[i].innerHTML;
                    var infowindow = new google.maps.InfoWindow()
                    google.maps.event.addListener(marker, 'click', (function (marker, content, infowindow) {
                        return function () {
                            infowindow.setContent(content);
                            infowindow.open(map, marker);
                        };
                    })(marker, content, infowindow));
                }
                return markers;
            }
            initialize();
    });
    </script>
     <?php }?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="<?php echo get_template_directory_uri();?>/assets/js/owl.carousel.min.js"></script>
    <script src="<?php echo get_template_directory_uri();?>/assets/js/custom.js"></script>
    <?php wp_footer(); ?>
    <script>
        (function($) {
            $(document).ready( function() {
                initialize();
                setTimeout(() => {
                    jQuery(".pac-container").appendTo(".search-panel");
                }, (1000));
            });
        })(jQuery);

        function initialize() {
            var options = {
                types: ['(cities)']
                //componentRestrictions: {country: "it"}
            };

            var input = document.getElementById('where');
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            document.getElementById('city').value = place.name;
            //document.getElementById('cityLat').value = place.geometry.location.lat();
            //document.getElementById('cityLng').value = place.geometry.location.lng();
            //alert("This function is working!");
            //alert(place.name);
           // alert(place.address_components[0].long_name);

        });
        }
    </script>
</body>

</html>

jQuery('.drawer-btn').click(function () {
    jQuery('.booking-menu-wrapper').toggleClass('active');
});

jQuery('a.menu-link').click(function () {
    jQuery('.booking-menu-wrapper').removeClass('active');
});

//home page banner
jQuery('.home-slider').owlCarousel({
    loop: true,
    margin: 10,
    nav: false,
    dots: false,
    autoplay: true,
    autoplayTimeout: 3000,
    autoplaySpeed: 1000,
    items: 1
})

jQuery('.featured-slider').owlCarousel({
    loop: true,
    margin: 15,
    nav: false,
    dots: false,
    autoplay: true,
    autoplayTimeout: 3000,
    autoplaySpeed: 1000,
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 3,
        },
        1000: {
            items: 4,
        },
        1200: {
            items: 5,
        }
    }
})

jQuery('.testimonial-tiles').owlCarousel({
    loop: true,
    margin: 15,
    nav: false,
    dots: false,
    autoplay: true,
    autoplayTimeout: 3000,
    autoplaySpeed: 1000,
    responsive: {
        0: {
            items: 1,
        },
        1000: {
            items: 2,
        }
    }
})

jQuery('.featured-img-slider').owlCarousel({
    loop: true,
    margin: 0,
    nav: true,
    dots: false,
    autoplay: false,
    autoplayTimeout: 3000,
    autoplaySpeed: 1000,
    navText: ["<img src='/wp-content/themes/mapparte/assets/images/slider-wrrow.svg'>", "<img src='/wp-content/themes/mapparte/assets/images/slider-wrrow.svg'>"],
    items: 1
})

jQuery('.detail-logged-slider').owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    navText: ["<img src='/wp-content/themes/mapparte/assets/images/slider-arrow.svg'>", "<img src='/wp-content/themes/mapparte/assets/images/slider-arrow.svg'>"],
    dots: true,
    autoplay: false,
    autoplayTimeout: 3000,
    autoplaySpeed: 1000,
    items: 1
})

//magazine detail page banner
jQuery('.magazine-slider').slick({
    centerMode: true,
    centerPadding: '20rem',
    slidesToShow: 2,
    arrows: true,
    dots: true,
    responsive: [
        {
            breakpoint: 991,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '15rem',
                slidesToShow: 1
            }
        },
        {
            breakpoint: 576,
            settings: {
                centerPadding: '8rem',
                slidesToShow: 1
            }
        }
    ]
});

// // header height
// jQuery(window).on('load', function () {
//     var hheight = jQuery("header").height();
//     jQuery('.banner-wrapper').css({ 'padding-top': hheight });
// });

//on scroll add class
// jQuery(window).scroll(function () {
//     var scroll = jQuery(window).scrollTop();

//     if (scroll >= 500) {
//         jQuery(".header-wrapper").addClass("darkHeader");
//     } else {
//         jQuery(".header-wrapper").removeClass("darkHeader");
//     }
// });

jQuery(".navbar-toggler").click(function () {
    jQuery('header').toggleClass("active");
});


jQuery(window).on('load', function () {
    jQuery('.more-filter-btn, .more-filter-btn-overly').click(function () {
        jQuery('.filter-options-wrapper').toggleClass('active');
        jQuery('.more-filter-btn').toggleClass('active');
    });

    var viewPortWidth = jQuery(window).width();
    if (viewPortWidth > 767) {
        jQuery(window).scroll(function () {
            var scroll = jQuery(window).scrollTop();
            if (scroll >= 100) {
                jQuery(".filter-options-wrapper.active").removeClass("active");
                jQuery(".more-filter-btn.active").removeClass("active");
            }
        });
    }
    ;
});

//input type num
function incrementValue(e) {
    e.preventDefault();
    var fieldName = jQuery(e.target).data('field');
    var parent = jQuery(e.target).closest('div');
    var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

    if (!isNaN(currentVal)) {
        parent.find('input[name=' + fieldName + ']').val(currentVal + 1);
    } else {
        parent.find('input[name=' + fieldName + ']').val(0);
    }
}

function decrementValue(e) {
    e.preventDefault();
    var fieldName = jQuery(e.target).data('field');
    var parent = jQuery(e.target).closest('div');
    var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

    if (!isNaN(currentVal) && currentVal > 0) {
        parent.find('input[name=' + fieldName + ']').val(currentVal - 1);
    } else {
        parent.find('input[name=' + fieldName + ']').val(0);
    }
}

jQuery('.input-group').on('click', '.button-plus', function (e) {
    incrementValue(e);
});

jQuery('.input-group').on('click', '.button-minus', function (e) {
    decrementValue(e);
});


//booking time checked
jQuery('#exampleCheck1, #exampleCheck2').click(function () {
    jQuery(".time-slot-wrapper").toggleClass('active');
});

//manage profile elment
jQuery('.logged-popup').hide();

jQuery(document).on('click', function (e) {
    if (jQuery(e.target).closest('.logged').length) {
        jQuery(".logged-popup").toggle();
    } else if (!jQuery(e.target).closest('.logged').length) {
        jQuery('.logged-popup').hide();
    }
});

jQuery(document).ready(function ($) {
    jQuery(".remove-space").click(function (e) {
        e.preventDefault();
        if (confirm("Sei sicuro di voler eliminare lo spazio?")) {
            if (confirm("Una volta rimosso lo spazio non sarà più accessibile.\nVuoi ancora eliminare lo spazio?")) {
               window.location.href=jQuery(this).attr('href');
            }
        }
    });
})
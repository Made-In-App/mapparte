<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Mapparte">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>Mapparte</title>
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.png">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/main.css">
 
	<?php if ( get_field( 'google_maps_api', 'option' ) ) : ?>
        <script type="text/javascript"
                src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr( get_field( 'google_maps_api', 'option' ) ); ?>&libraries=places&language=it"></script>
	<?php endif ?>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/map.js"></script>
	<?php wp_head(); ?>
	<?php if ( isset( $_REQUEST['redirect'] ) ) { ?>
        <script>
            jQuery(document).ready(function ($) {
                setTimeout(function () {
                    $('#nav-login').click();
                }, 1000);
            });
        </script>
	<?php } ?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-174558216-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-174558216-1');
</script>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<!--header section start-->
<header class="fixed-top">
    <img class="w-100 menu-bg" src="<?php echo get_template_directory_uri(); ?>/assets/images/menu-bg.svg" alt="bg"/>
    <div class="container-fluid gx-5">
        <div class="row justify-content-between align-items-center header-wrapper">
            <div class="header-left col-12 col-lg-3 d-flex align-items-center">
                <div class="logo">
                    <a href="/">
                        <img class="logo-icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.svg"
                             alt="logo"/>
                        <img class="logo-name"
                             src="<?php echo get_template_directory_uri(); ?>/assets/images/site-name.svg" alt="logo"/>
                    </a>
                </div>
                <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="col-12 col-lg-9 text-end menu">
                <nav class="navbar navbar-expand-lg">

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
						<?php
						$theme_location = is_user_logged_in() ? 'logged' : 'not-logged';
						wp_nav_menu(
							[
								'theme_location' => $theme_location,
								'container'      => '',
								'menu_class'     => 'navbar-nav ms-auto mb-2 mb-lg-0',
								'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
								'add_li_class'   => 'nav-item'
							]
						);
						?>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>



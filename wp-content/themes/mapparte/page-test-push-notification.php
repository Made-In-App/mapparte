<?php
/**
 * Template Name:  Test push notification
 */
get_header();
$response = "";
if ($_GET["sendType"] == 1)
    $response = \Mapparte\Push_Notification::sendMessage("Notifica di prova",["popup_message" => "Accedi al sito web per ....."]);

if ($_GET["sendType"] == 2)
    $response =  \Mapparte\Push_Notification::sendMessage("Notifica di apertura dettaglio spazio",["id" => 699]);

while ( have_posts() ) :
	the_post();
?>
<section class="magazine-detail-wrapper">
        <img class="background-img" src="<?php echo get_template_directory_uri();?>/assets/images/detail-bg.png" alt="magazine">
        <div class="container gx-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h1 class="magazine-detail-ttl"><?php the_title()?></h1>
                </div>
                <div class="col-md-6">
                   
                 
                </div>
            </div>
        </div>
        <div class="container gx-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 col-12 magazine-detail-desc">
                   <h2><a href="/test-push-notification/?sendType=1">INVIA PUSH CON POPUP</a></h2>
                   <h2><a href="/test-push-notification/?sendType=2">INVIA PUSH CON APERTURA DETTAGLIO SPAZIO</a></h2>

                   <pre><?php print_r($response);?></pre>
                </div>
            </div>
        </div>
    </section>
    <?php
endwhile; // End of the loop.?>
<?
get_template_part( 'template-parts/footer' );
get_footer();
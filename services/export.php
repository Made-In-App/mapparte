<?php
ob_start();
error_reporting(E_ERROR | E_PARSE);
require '../wp-load.php';
// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
require_once( ABSPATH . 'wp-admin/includes/image.php' );
define('WP_USE_THEMES', true);
ini_set('memory_limit', '-1');
ob_clean();
$args = ['post_type' => 'space','post_status' => 'publish','posts_per_page' => -1];
$spaces_file = "ID spazio|numer of room|full_name|full_address|phone_number|phone_number_2|email|website|open_days|type|management_type|social|descrizione|valori_is|";
$galleries_file = "Nome file immagine|Code|ID spazio|name|url";
$countImages = 1;
// The Query
$the_query = new WP_Query( $args );
 
// The Loop
if ( $the_query->have_posts() ) {
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        $space_data = \Mapparte\Utils::return_space_data( get_the_ID() );

        $spaces_file .= "\n".$space_data["id"]."|".$space_data["rooms"]."|".$space_data["title"]["rendered"]."|".$space_data["address"]["address"]."|||".get_the_author_meta( 'email', $space_data["author"] )."|".$space_data["link"]."||spazio|||".str_replace("\n"," ",strip_tags($space_data["excerpt"]["rendered"]))."||";
       
        $images = $space_data["photos"];
        if( $images ):
            foreach( $images as $image ): 
               
                $title_img = (empty($image["description"])) ? $image["caption"] : $image["description"];
                $title_img = (!empty($title_img)) ? $title_img : $space_data["title"]["rendered"];
                ftp_upload("poetronicart.ajaris.it","mapparte","mapparte2020!","immagini/", $image["sizes"]["featured-image-desktop"], $countImages ."_" . basename($image["sizes"]["featured-image-desktop"]));
                $galleries_file .= "\n".$countImages ."_" . basename($image["sizes"]["featured-image-desktop"])."|".$countImages."|".$space_data["id"]."|".str_replace("\n"," ",strip_tags($title_img))."|". $countImages ."_" . basename($image["sizes"]["featured-image-desktop"]);
                $countImages++;
            endforeach;
        endif; 
    }
} 
/* Restore original Post Data */
wp_reset_postdata();

ftp_file_upload("poetronicart.ajaris.it","mapparte","mapparte2020!","spazi.txt",$spaces_file);
ftp_file_upload("poetronicart.ajaris.it","mapparte","mapparte2020!","gallerie.txt",$galleries_file);
pre($spaces_file);
pre($galleries_file);


function ftp_upload($server, $user, $pass, $dir, $source, $dest = false)
{

        if (!$dest)  $dest = basename($source);
        $conn_id = ftp_connect($server);
        if ($conn_id) {
            $login_result = ftp_login($conn_id, $user, $pass);
            $dd = '';
            $d = explode("/", $dir);
            for ($i = 0; $i < count($d) - 1; $i++) {
                $dd .= $d[$i] . "/";
                @ftp_mkdir($conn_id, $dd);
            }
            @ftp_put($conn_id, $dir . $dest, $source, FTP_BINARY);
            ftp_quit($conn_id);
        }
    
}

function ftp_file_upload($ftp_server, $ftp_user, $ftp_pass,$file_name,$content)
{
    // set up basic connection
    $conn_id = ftp_connect($ftp_server);
    $login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);
    ftp_chdir($conn_id, 'dati');
    //Uploading files...
    //to be uploaded
    $fp = fopen('php://temp', 'r+');
    fwrite($fp, $content);
    rewind($fp);       
    ftp_fput($conn_id,$file_name, $fp, FTP_ASCII);
    // close the connection
    ftp_close($conn_id);
    fclose($fp);
}
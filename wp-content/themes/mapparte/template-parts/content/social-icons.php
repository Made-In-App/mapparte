<?php 
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>
<ul class="social-icons">
    <li><a href="http://www.facebook.com/sharer.php?u=<?php echo $actual_link;?>"><img src="<?php echo get_template_directory_uri();?>/assets/images/facebook-b.png" alt="facebook"></a></li>
    <li><a href="http://twitter.com/share?url=<?php echo $actual_link;?>"><img src="<?php echo get_template_directory_uri();?>/assets/images/twitter-b.png" alt="twitter"></a></li>
    <li><a href="https://www.instagram.com/sharer.php?u=<?php echo $actual_link;?>"><img src="<?php echo get_template_directory_uri();?>/assets/images/instagram-b.png" alt="instagram"></a></li>
</ul>
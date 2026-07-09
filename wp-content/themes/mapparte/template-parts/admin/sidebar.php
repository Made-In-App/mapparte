<?php
global $post, $wp_query;
?>
<!-- Inserire controllo solo pagine backend -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/cms.css">
<div class="col-md-2 booking-menu-wrapper">
    <ul>
        <li class="menu-item <?php echo ( isset( $wp_query->query_vars['post_type'] ) && 'space' === $wp_query->query_vars['post_type'] && isset( $wp_query->query_vars['mine'] ) && '1' === $wp_query->query_vars['mine'] ) ? "active" : "" ?>">
            <a class="menu-link" href="<? echo get_home_url(); ?>/my-spaces/"><?php echo __("I miei spazi","mapparte");?></a>
        </li>
        <li class="menu-item">
            <a class="menu-link" href="#"><?php echo __("Prenotazioni","mapparte");?></a>
            <ul>
                <li class="menu-item <?php echo ( !$wp_query->query_vars['p'] && isset( $wp_query->query_vars['post_type'] ) && 'booking' === $wp_query->query_vars['post_type'] && ( !isset( $wp_query->query_vars['mine'] ) ) ) ? "active" : ""; ?>">
                    <a class="menu-link" href="<? echo get_home_url(); ?>/bookings/"><?php echo __("Ricevute","mapparte");?> <!--span class="count">1</span--> </a>
                </li>
                <li class="menu-item <?php echo ( isset( $wp_query->query_vars['post_type'] ) && 'booking' === $wp_query->query_vars['post_type'] && isset( $wp_query->query_vars['mine'] ) && '1' === $wp_query->query_vars['mine'] ) ? "active" : "" ?>">
                    <a class="menu-link" href="<? echo get_home_url(); ?>/my-bookings/"><?php echo __("Effettuate","mapparte");?> <!--span class="count">1</span--> </a>
                </li>
            </ul>
        </li>
        </li>
        <li class="menu-item">
            <a class="menu-link" href="#"><?php echo __("Messaggi","mapparte");?></a>
            <ul>
                <li class="menu-item <?php echo ( $post->post_name == "messaggi" && !isset( $wp_query->query_vars['mine'] ) && !isset( $wp_query->query_vars['comment_id'] ) ) ? "active" : "" ?>">
                    <a class="menu-link" href="<? echo get_home_url(); ?>/messaggi/"><?php echo __("Ricevuti","mapparte");?> <!--span class="count">1</span--> </a>
                </li>
                <li class="menu-item <?php echo ( $post->post_name == "messaggi" && isset( $wp_query->query_vars['mine'] ) && '1' === $wp_query->query_vars['mine'] ) ? "active" : "" ?>">
                    <a class="menu-link" href="<? echo get_home_url(); ?>/messaggi/inviati"><?php echo __("Inviati","mapparte");?> <!--span class="count">1</span--> </a>
                </li>
            </ul>
        </li>
        <li class="menu-item <?php echo ( $post->post_name == "profilo" ) ? "active" : "" ?>">
            <a class="menu-link" href="<? echo get_home_url(); ?>/profilo/"><?php echo __("Profilo e account","mapparte");?></a>
        </li>
        <li class="menu-item <?php echo ( $post->post_name == "preferiti" ) ? "active" : "" ?>">
            <a class="menu-link" href="<? echo get_home_url(); ?>/preferiti/"><?php echo __("Preferiti","mapparte");?></a>
        </li>
    </ul>
</div>
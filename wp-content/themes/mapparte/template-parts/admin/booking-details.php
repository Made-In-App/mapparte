<?php

$details = get_post_meta( $post->ID, '_booking_details', true );
$dir     = ( get_current_user_id() === (int) $post->post_author ) ? 'guest' : 'host';
$back    = ( get_current_user_id() === (int) $post->post_author ) ? '/my-bookings/' : 'bookings';
?>
<div class="col-md-10 booking-details-section">
    <div class="header-top">
        <div class="row align-items-center justify-content-between mx-0">
            <div class="col-md-6 col-6 header-left">
                <a href="<?php echo $back ?>">
                    <!--i class="fas fa-long-arrow-alt-left me-2"></i-->
                    <?php echo __("INDIETRO","mapparte");?>
                </a>
            </div>
        </div>
    </div>
    <div class="row mx-0">
		<?php
		get_template_part( "template-parts/admin/booking-steps/$dir/$post->post_status", '', $details );

		?>
    </div>
</div>

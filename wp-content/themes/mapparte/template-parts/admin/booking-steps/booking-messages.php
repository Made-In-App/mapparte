<?php
$thread    = \Mapparte\Messages::get_comment_thread_by_post_id( $post->ID );
$thread_id = isset( $thread[0] ) ? $thread[0]->comment_ID : 0;

if ( $thread_id ) {
	$messages = \Mapparte\Messages::get_messages_details( $thread_id );
}

?>
<h6 class="booking-subttl"><br/>MESSAGGI</h6>
<?php if ( isset( $messages['results'] ) && sizeof( $messages['results'] ) > 0 ) : ?>
	<?php foreach ( $messages['results'] as $message ) { ?>
        <div class="col-4">
            <h6 class="booking-detail-ttl"><?php echo esc_html( $message->comment_author ); ?></h6>
        </div>
        <div class="col-8">
            <p class="booking-detail-content"><?php echo esc_html( $message->comment_date ); ?></p>
            <p class="booking-detail-content"><?php echo esc_html( $message->comment_content ); ?></p>
        </div>
	<?php } ?>
    <div class="col-4">
    </div>
    <div class="col-8">
        <a href="<? echo get_home_url(); ?>/messaggi/<?php echo $thread_id; ?>/" class="btn btn-outline-primary">
        <?php echo __("INVIA UN MESSAGGIO","mapparte");?>
        </a>
    </div>
<?php endif; ?>

<?php
$author_name = get_the_author_meta( 'display_name', $post->post_author );
$author_registered = get_the_author_meta( 'user_registered', $post->post_author );

?>
<!--message modal start-->
<div class="modal fade" id="message-popup" tabindex="-1" aria-labelledby="message-popupLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="row">
				<div class="col-md-5">
					<div class="modal-header">
						<!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
						<div class="user-icon-wrapper">
							<img class="user-icon" src="<?php echo get_template_directory_uri();?>/assets/images/msg-user.png" alt="user">
						</div>
						<h4 class="user-name"><?php echo esc_html($author_name); ?></h4>
						<h6 class="user-desc"><?php echo __("Registrato da","mapparte");?> <?php echo esc_html( \Mapparte\Frontend_Utils::format_date( $author_registered ) ); ?></h6>
						<ul class="services-lists">
							<li class="active"><a href="#"><?php echo __("Scrivi un messaggio per","mapparte");?></a></li>
							<li><a href="#"><?php echo __("Fornire il maggior numero di dettagli","mapparte");?></a></li>
							<li><a href="#"><?php echo __("Organizzare una visita","mapparte");?></a></li>
							<li><a href="#"><?php echo __("Chiedere maggiori informazioni","mapparte");?></a></li>
							<li><a href="#"><?php echo __("Comunicare il tuo budget","mapparte");?></a></li>
						</ul>
					</div>
				</div>
				<div class="col-md-7">
					<div class="modal-body">
						<h4 class="message-ttl"><?php echo __("Messaggio per l'host","mapparte");?></h4>
						<p class="message-desc"><?php echo __("Se hai bisogno di conoscere ulteriori dettagli scrivi qui il tuo messaggio per l’Host.","mapparte");?></p>
						<form class="message-form">
							<div class="mb-3 form-tile">
                                    <textarea id="message" class="form-control"
                                              placeholder="<?php echo __("Scrivi qui li tuo messaggio.","mapparte");?>"></textarea>
							</div>
							<div class="d-flex justify-content-end action-btn">
								<button type="button" class="btn btn-secondary"><?php echo __("Annulla","mapparte");?></button>
								<button type="button" class="btn btn-outline-primary"><?php echo __("invia messaggio","mapparte");?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--message modal end-->
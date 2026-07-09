<?php
acf_form_head();
?>
<div class="acf-fields acf-form-fields -top inactive">
    <p class="booking-detail-content"><?php echo __("Puntualità","mapparte");?></p>
	<?php
	\Mapparte\Frontend_Utils::get_rating_single( 'puntualita', "user_" . $args['userId'] );
	?>
</div>
<div class="acf-fields acf-form-fields -top">
    <p class="booking-detail-content"><?php echo __("Cura","mapparte");?></p>
	<?php
	\Mapparte\Frontend_Utils::get_rating_single( 'cura', "user_" . $args['userId'] );
	?>
</div>
<div class="acf-fields acf-form-fields -top">
    <p class="booking-detail-content"><?php echo __("Rispetto delle dotazioni","mapparte");?></p>
	<?php
	\Mapparte\Frontend_Utils::get_rating_single( 'rispetto_delle_dotazioni', "user_" . $args['userId'] );
	?>
</div>
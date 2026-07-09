<?php
acf_form_head();
?>
<div class="acf-fields acf-form-fields -top inactive">
    <p class="booking-detail-content"><?php echo __("Pulizia","mapparte");?></p>
	<?php
	\Mapparte\Frontend_Utils::get_rating_single( 'pulizia', $args['spaceId'] );
	?>
</div>
<div class="acf-fields acf-form-fields -top">
    <p class="booking-detail-content"><?php echo __("Aderenza alla descrizione","mapparte");?></p>
	<?php
	\Mapparte\Frontend_Utils::get_rating_single( 'aderenza', $args['spaceId'] );
	?>
</div>
<div class="acf-fields acf-form-fields -top">
    <p class="booking-detail-content"><?php echo __("Professionalità","mapparte");?></p>
	<?php
	\Mapparte\Frontend_Utils::get_rating_single( 'professionalita', $args['spaceId'] );
	?>
</div>


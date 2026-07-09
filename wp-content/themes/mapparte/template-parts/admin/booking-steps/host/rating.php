<form action="#" method="post">
	<?php
	acf_form_head();
	$options = array(
		'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
		'fields'       => array( 'puntualita' ),
		'form'         => false, // set this to false to prevent the <form> tag from being created
		'html_before_fields' => '<p class="booking-detail-content">Puntualità</p>', // html inside form before fields
		'html_after_fields' => '', // html inside form after fields
	);
	acf_form( $options );
	$options = array(
		'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
		'fields'       => array( 'cura' ),
		'form'         => false, // set this to false to prevent the <form> tag from being created
		'html_before_fields' => '<p class="booking-detail-content">Cura</p>', // html inside form before fields
		'html_after_fields' => '', // html inside form after fields
	);
	acf_form( $options );
	$options = array(
		'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
		'fields'       => array( 'rispetto_delle_dotazioni' ),
		'form'         => false, // set this to false to prevent the <form> tag from being created
		'html_before_fields' => '<p class="booking-detail-content">Rispetto delle dotazioni</p>', // html inside form before fields
		'html_after_fields' => '', // html inside form after fields
	);
	acf_form( $options );
	?>
	<button type="submit" class="btn btn-secondary"><?php echo __("Invia","mapparte");?></button>
</form>
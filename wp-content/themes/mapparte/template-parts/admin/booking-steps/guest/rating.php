<form action="#" method="post">
	<?php
	acf_form_head();
	$options = array(
		'field_groups'       => array(),
		// this will find the field groups for this post (post ID's of the acf post objects)
		'fields'             => array( 'pulizia' ),
		'form'               => false,
		// set this to false to prevent the <form> tag from being created
		'html_before_fields' => '<p class="booking-detail-content">Pulizia</p>',
		// html inside form before fields
		'html_after_fields'  => '',
		// html inside form after fields
	);
	acf_form( $options );
	$options = array(
		'field_groups'       => array(),
		// this will find the field groups for this post (post ID's of the acf post objects)
		'fields'             => array( 'aderenza' ),
		'form'               => false,
		// set this to false to prevent the <form> tag from being created
		'html_before_fields' => '<p class="booking-detail-content">Aderenza alla descrizione</p>',
		// html inside form before fields
		'html_after_fields'  => '',
		// html inside form after fields
	);
	acf_form( $options );
	$options = array(
		'field_groups'       => array(),
		// this will find the field groups for this post (post ID's of the acf post objects)
		'fields'             => array( 'professionalita' ),
		'form'               => false,
		// set this to false to prevent the <form> tag from being created
		'html_before_fields' => '<p class="booking-detail-content">Professionalità</p>',
		// html inside form before fields
		'html_after_fields'  => '',
		// html inside form after fields
	);
	acf_form( $options );
	?>
	<button type="submit" class="btn btn-secondary"><?php echo __("Invia","mapparte");?></button>
</form>

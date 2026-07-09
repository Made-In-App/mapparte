<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $step_name;
$step_name = __( 'Politica di cancellazione ed Ente gestore', 'mapparte' );
get_template_part( 'template-parts/edit-space/steps/10' );

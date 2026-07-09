<div class="xoo-tabs">
	<?php

	$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'email';

	echo '<h2 class="nav-tab-wrapper">';
	foreach ( $tabs as $tab_key => $tab_caption ) {
		$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
		echo '<a class="nav-tab ' . $active . '" href="?page=xoo-uv&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
	}
	echo '</h2>';

	if( $current_tab === 'advanced' ){
		$option_name = 'premium';
	}
	else{
		$option_name = 'xoo-uv-'.$current_tab.'-options';
	}

	?>
</div>

<?php
	
	if( $current_tab === 'fields' ) {
		xoo_aff_admin()->display_settings();
		return;
	}

?>


<div class="xoo-container">
	<div class="xoo-main">

		<?php if( $option_name === 'premium' ): ?>

			<?php include(plugin_dir_path(__FILE__).'xoo-uv-premium-info.php'); ?>

		<?php else: ?>
			
			<form method="post" action="options.php">
				<?php
				settings_fields( $option_name ); // Display Settings

				do_settings_sections( $option_name ); // Display Sections

				submit_button( 'Save Settings' );	// Display Save Button
				?>			
				<?php if($current_tab === 'email'): ?>
					<a target="_blank" href="<?php echo admin_url().'?xoo_el_preview_email=true'; ?>" class="button button-primary">Preview Email</a>
			</form>
				<?php endif; ?>

		<?php endif; ?>

	</div>

	<div class="xoo-sidebar">
		<?php include XOO_UV_PATH.'/admin/templates/xoo-uv-sidebar.php'; ?>
	</div>
</div>


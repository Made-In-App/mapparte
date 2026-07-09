<!DOCTYPE html>
<html>
<head>

	<title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
  	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
  	<meta name="viewport" content="width=device-width">
  	
  	<?php do_action( 'xoo_uv_email_head', $emailObj ); ?>

</head>

<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">		
	<!-- Main Container -->
	<table cellpadding="0" border="0" cellspacing="0" width="100%">
		<tr>
			<td align="center" bgcolor="#f0f0f0" style="color: #000000;" valign="top">

				<!-- 600px Inner Container -->
				<table cellpadding="2" cellspacing="0" width="600" class="xoo-wl-table-full" bgcolor="#ffffff" style="border: 1px solid #f0f0f0;" style="box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1); border-radius: 3px;">

					<!-- Site Logo -->
					<?php if( xoo_uv_helper()->get_email_option( 'm-header-img' ) ): ?>
					<tr>
						<td align="center" style="padding: 0 0 0 0">
						<img height="auto" width="auto" border="0" alt="<?php echo get_bloginfo( 'name', 'display' ); ?>" src="<?php echo xoo_uv_helper()->get_email_option( 'm-header-img' ); ?>" style="display: block"/>
						</td>
					</tr>
					<?php endif; ?>

					<tr>
						<td style="font-size: 17px; padding: 0">

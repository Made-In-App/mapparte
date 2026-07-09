<?php do_action( 'xoo_uv_email_header', $emailObj ); ?>

<table cellpadding="0" border="0" cellspacing="0" width="100%">
	<td style="padding: 20px 20px 0 20px">
		<table cellpadding="0" border="0" cellspacing="0">
			<tr>
				<td>
					<?php echo $bodytxt; ?>
				</td>
			</tr>
			<tr>
				<td style="padding: 25px 0;">
					<?php echo $verify_button; ?>
				</td>
			</tr>
		</table>
	</td>
</table>

<table cellpadding="0" border="0" cellspacing="0" width="100%">
	<tr>
		<td style="padding: 20px; color:<?php echo $footer_txtcolor; ?>" bgcolor="<?php echo $footer_bgcolor; ?>">
			<?php echo $footertxt; ?>
		</td>
	</tr>
</table>

<?php do_action( 'xoo_uv_email_footer', $emailObj ); ?>
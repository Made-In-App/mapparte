<?php
/**
 * Responsible for displaying button
 *
 * This template can be overridden by copying it to yourtheme/templates/social-login-woocommerce/xoo-sl-button.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://docs.xootix.com/easy-login-woocommerce/
 * @version 1.1
 */


$settings = xoo_sl_helper()->get_general_option();

?>

<div class="xoo-sl-container">

	<?php if( $heading ): ?>
		<div class="xoo-sl-loginvia">
			<span><?php echo $settings['gl-txt-heading'] ?></span>
		</div>
	<?php endif; ?>

	<div class="xoo-sl-btns-container">

		<?php foreach( $buttons as $button ): ?>

			<?php if( $button['enable'] !== "yes" ) continue; ?>

			<div class="xoo-sl-social-btn <?php echo $button['class'] ?>">

				<span class="xoo-sl-btn-icon <?php echo $button['icon'] ?>"></span>

				<span class="xoo-sl-btn-txt"><?php echo $button[ 'text' ] ?></span>

			</div>

		<?php endforeach; ?>

	</div>
	
</div>
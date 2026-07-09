<?php
/**
 * Responsible for settings styling
 *
 * This template can be overridden by copying it to yourtheme/templates/social-login-woocommerce/inline-style.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://docs.xootix.com/easy-login-woocommerce/
 * @version 1.1
 */


$settings 	= xoo_sl_helper()->get_general_option();

$btnWidth 	= esc_html( $settings['gl-btn-width'] );
$btnHeight 	= esc_html( $settings['gl-btn-height'] );
$btnRow 	= esc_html( $settings['gl-btn-rows'] );
$btnFSize 	= esc_html( $settings['gl-btn-fsize'] );
$btnRadius 	= esc_html( $settings['gl-btn-borad'] );

$fbBGColor 	= esc_html( $settings['gl-fb-bgcolor'] );
$fbTxtColor = esc_html( $settings['gl-fb-txtcolor'] );

$goBGColor 	= esc_html( $settings['gl-goo-bgcolor'] );
$goTxtColor = esc_html( $settings['gl-goo-txtcolor'] );

?>

.xoo-sl-facebook-btn{
	background-color: <?php echo $fbBGColor ?>;
	color: <?php echo $fbTxtColor ?>;
}

.xoo-sl-google-btn{
	background-color: <?php echo $goBGColor ?>;
	color: <?php echo $goTxtColor ?>;
}

.xoo-sl-social-btn{
	font-size: <?php echo $btnFSize ?>px;
	max-width: <?php echo $btnWidth ?>px;
	height: <?php echo $btnHeight ?>px;
	line-height: <?php echo $btnHeight ?>px;
	border-radius: <?php echo $btnRadius ?>px;
}

<?php if( $btnRow === 'same' ): ?>

	.xoo-sl-btns-container{
		display: flex;
		align-items: center;
		justify-content: center;
		flex-wrap: wrap;
	}

<?php else: ?>

	.xoo-sl-btns-container{
		display: block;
	}

	.xoo-sl-social-btn{
		margin: 10px auto;
	}

<?php endif; ?>

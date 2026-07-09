<div class="xoo-uv-verified-success">
	<span><?php  _e("Your email has been successfully verified.","user-verification-woocommerce"); ?></span>
	<?php $login_url = class_exists('woocommerce') ?  wc_get_page_permalink( 'myaccount' ) : wp_login_url(); ?>
	<span><a <?php echo function_exists('xoo_el') ? 'class="xoo-el-login-tgr"' : 'href="'.apply_filters( 'xoo_uv_verified_success_login_url', $login_url ).'"'; ?>><?php _e('Please Login','user-vericiation-woocommerce'); ?></a>
</div>

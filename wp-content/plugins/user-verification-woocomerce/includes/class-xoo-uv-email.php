<?php

class Xoo_Uv_Email{

	protected static $_instance = null;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct(){
		$this->hooks();
	}

	public function hooks(){
		add_action( 'xoo_uv_email_head', array( $this, 'default_inline_style' ) );
		add_action( 'xoo_uv_email_header', array( $this, 'email_header' ) );
		add_action( 'xoo_uv_email_footer', array( $this, 'email_footer' ) );
		add_filter( 'woocommerce_email_classes', array( $this, 'register_email_for_wc' ) );
	}


	public function email_header( $emailObj ){
		xoo_uv_helper()->get_template( 'global/xoo-uv-email-header.php', array( 'emailObj' => $emailObj ) );
	}

	public function email_footer(){
		xoo_uv_helper()->get_template( 'global/xoo-uv-email-footer.php' );
	}

	public function default_inline_style(){
		xoo_uv_helper()->get_template( 'global/xoo-uv-email-style.php' );
	}

	public function register_email_for_wc( $emails ){
		$emails[ 'xoo_uv_verification' ] = include XOO_UV_PATH.'/includes/class-xoo-uv-wc-email.php';
		return $emails;
	}

	public function send( $user_id ){

		$user_data 	= get_userdata( $user_id );

		if( !$user_data ||  xoo_uv_is_user_active( $user_id ) ) return false;

		$email_args = $this->get_email_args( $user_id );


		if( class_exists( 'woocommerce' ) && xoo_uv_helper()->get_email_option('sy-email-temp') === 'woocommerce' ){ //Use woocommerce to send email
			WC_Emails::instance()->emails['xoo_uv_verification']->trigger( $user_id, $email_args );
		}
		else{ //Use custom design

			$email_settings = xoo_uv_helper()->get_email_option();
			$from_name 		= esc_attr( $email_settings['m-send-name'] );
			$from_email 	= esc_attr( $email_settings['m-send-email'] );
			$subject 		= esc_attr( $email_settings['m-subject-txt'] );

			$headers = array(
				"From: {$from_name} <$from_email>",
				"Content-Type: text/html; charset=UTF-8"
			);
			
			$to = esc_attr( $user_data->user_email );

			$body = xoo_uv_helper()->get_template( "xoo-uv-verify-email.php", $email_args, '', true );
			 
			wp_mail( $to, $subject, $body, $headers );

		}

		

		$sent_count = (int) get_user_meta( $user_id, 'xoo-uv-sent-email-count', true );

		update_user_meta( $user_id, 'xoo-uv-sent-email-count', ++$sent_count);

	}


	//Get email template
	public function get_email_args( $user_id ){

		$user_data = get_userdata( $user_id );

		if( !$user_data ) return;

		$email_settings = xoo_uv_helper()->get_email_option();
		$bodytxt 		= esc_attr( $email_settings['m-body-txt'] );
		$footertxt 		= esc_attr( $email_settings['m-footer-txt'] );

		$placeholders = array(
			'[br]'			=> '<br>',
			'[b]'			=> '<b>',
			'[/b]'			=> '</b>',
			'[username]' 	=> esc_attr( $user_data->user_login ),
			'[name]'		=> esc_attr( $user_data->user_nicename )
		);

		foreach ( $placeholders as $placeholder => $placeholder_value ) {
			$bodytxt = str_replace( $placeholder , $placeholder_value , $bodytxt );
			$footertxt = str_replace( $placeholder , $placeholder_value , $footertxt );
		}

		$verify_link = $this->generate_verify_link_for_user( $user_id );

		if( !$verify_link ){
			return new WP_Error( 'xoo-uv-failed-verify-link', __('Failed to generate verify link','user-verification-woocommerce') );
		}

		$args = array(
			'website_title'			=> esc_attr( get_bloginfo() ),
			'header_image'			=> esc_attr( $email_settings['m-header-img'] ),
			'bodytxt'				=> $bodytxt,
			'footertxt'				=> $footertxt,
			'verifybtn_link' 		=> $verify_link,
			'footer_txtcolor'		=> esc_attr( $email_settings['s-footer-txtcolor'] ),
			'footer_bgcolor'		=> esc_attr( $email_settings['s-footer-bgcolor'] ),
			'verify_btntxt'			=> esc_attr( $email_settings['m-verifybtn-txt'] ),
			'verify_button' 		=> $this->get_email_button_markup( esc_attr( $email_settings['m-verifybtn-txt'] ), $verify_link ),
			'emailObj'				=> $this
		);

		return $args;
	}


	//Generate hash
	private function generate_hash( $user_email ){
		return md5( $user_email.time() );
	}


	//Generate verify link
	protected function generate_verify_link_for_user( $user_id ){

		$user_data = get_userdata( $user_id );

		if( !$user_data ) return;

		$hash = get_user_meta( $user_id, 'xoo-uv-email-hash', true);

		if( !$hash ){
			$hash = $this->generate_hash( $user_data->user_email );
			add_user_meta( $user_id, 'xoo-uv-email-hash', $hash );
		}	


		$base_url = xoo_uv_helper()->get_general_option( 'm-success-page' );
		$base_url = !$base_url ? get_site_url() : $base_url;

		if( !$user_data->user_email ){
			return;
		}

		$hash_part = '?user='.esc_attr( $user_data->ID ).'&hash='.$hash;

		return apply_filters( 'xoo_uv_verify_link', $base_url.$hash_part, $user_id, $hash_part );
	}



	public function get_email_button_markup( $text, $url, $args = array() ){

		$email_settings = xoo_uv_helper()->get_email_option();

		$defaults = array(
			'text' 			=>  $text,
			'url' 			=> $url,
			'txtColor' 		=> esc_attr( $email_settings['s-verify-btn-txtcolor'] ),
			'bgColor' 		=> esc_attr( $email_settings['s-verify-btn-bgcolor'] ),
			'vpadding' 		=> esc_attr( $email_settings['s-verify-btn-vpad'] ).'px',
			'hpadding' 		=> esc_attr( $email_settings['s-verify-btn-hpad'] ).'px',
			'fontWeight' 	=> 'bold',
			'fontFamily' 	=> 'sans-serif, Tahoma',
			'borderRadius' 	=> '3px',
			'fontSize' 		=> '14px',
			'border'		=> '1px solid #ffffff'
		);

		$args = apply_filters( 'xoo_uv_button_args', wp_parse_args( $args, $defaults ) );

		extract($args);

		$borderV 		= $vpadding.' solid '.$bgColor;
		$borderH 		= $hpadding.' solid '.$bgColor;
		ob_start();
		?>

		<a href="<?php echo $url; ?>" style="
		border-radius: <?php echo $borderRadius ?>;
		color: <?php echo $txtColor ?>;
		text-decoration:none;
		background-color: <?php echo $bgColor; ?>;
		border-top: <?php echo $borderV ?>;
		border-bottom: <?php echo $borderV ?>;
		border-left: <?php echo $borderH ?>;
		border-right: <?php echo $borderH ?>;
		display:inline-block;
		font-size: <?php echo $fontSize ?>;
		font-family: <?php echo $fontFamily ?>;
		font-weight: <?php echo $fontWeight ?>;"><?php echo $text; ?></a>
		
		<?php
		return ob_get_clean();
	}


	public function preview( $user_id ){

		$email_args = $this->get_email_args( $user_id );

		if( class_exists( 'woocommerce' ) && xoo_uv_helper()->get_email_option('sy-email-temp') === 'woocommerce' ){
			$wc_email = WC_Emails::instance()->emails['xoo_uv_verification'];
			$wc_email->content_html_args = array_merge( $wc_email->content_html_args, $email_args );
			echo $wc_email->style_inline( $wc_email->get_content() );
		}
		else{
			xoo_uv_helper()->get_template( "xoo-uv-verify-email.php", $email_args );
		}
	}


}

function xoo_uv_email(){
	return Xoo_Uv_Email::get_instance();
}
xoo_uv_email();
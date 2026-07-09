<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Xoo_Uv_Users_Table{


	protected static $_instance = null;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	public function __construct(){
		add_filter( 'manage_users_columns', array( $this, 'add_columns' ) );
		add_filter( 'manage_users_custom_column', array( $this, 'columns_output' ), 10, 3 );
		add_action( 'personal_options', array( $this, 'edit_profile_page' ) );
		add_action( 'personal_options_update', array( $this, 'save_customer_meta_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_customer_meta_fields' ) );
		add_action( 'admin_footer', array( $this, 'inline_scripts' ) );
		add_action( 'wp_ajax_xoo_uv_resend_email', array( $this, 'resend_email' ) );
		add_action( 'wp_ajax_nopriv_xoo_uv_resend_email', array( $this, 'resend_email' ) );
	}

	public function add_columns( $columns ){
		$columns['xoo_uv_active'] = 'Active';
	    return $columns;
	}


	public function columns_output( $val, $column_name, $user_id ){
		if( $column_name === "xoo_uv_active" ){
			$is_active = xoo_uv_is_user_active( $user_id ); 
			if( $is_active ){
				return '<span class="dashicons dashicons-yes"></span>';
			}
			else{
				return '<span class="dashicons dashicons-no-alt"></span>';
			}
		}

		return $val;
	}


	public function edit_profile_page( $user ){

		$field_id = 'xoo-uv-active';
		$db_value = get_user_meta( $user->ID, $field_id,true);
		$db_value = $db_value ? $db_value : 0;

		?>
		<tr>
			<th><?php  _e('Account Status','user-verification-woocommerce'); ?></th>
			<td>
				<select name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>">
					<option value="yes" <?php selected( $db_value, "yes" ); ?>><?php  _e('Active','user-verification-woocommerce'); ?></option>
					<option value="no" <?php selected( $db_value, "no" ); ?>><?php  _e('Verification Pending','user-verification-woocommerce'); ?></option>
				</select>
				<button class="xoo-uv-sev button" data-user_id="<?php echo $user->ID; ?>"><?php  _e('Resend Email','user-verification-woocommerce') ;?></button>
			</td>
		</tr>
		<?php
	}


	/**
	 * Save Address Fields on edit user pages.
	 *
	 * @param int $user_id User ID of the user being saved
	 */
	public function save_customer_meta_fields( $user_id ) {

		$field_id = 'xoo-uv-active';
		update_user_meta( $user_id, $field_id, isset( $_POST[ $field_id ] ) ? sanitize_text_field( $_POST[ $field_id ] ) : '' );

	}

	public function resend_email(){
		$user_id = (int) $_POST['user_id'];
		xoo_uv_email()->send( $user_id );
	}

	public function inline_scripts(){
		?>
		<style type="text/css">
			td.xoo_uv_active span.dashicons.dashicons-no-alt {
			    font-size: 16px;
			    vertical-align: middle;
			    height: 16px;
			    color: #da3958;
			}

			td.xoo_uv_active span.dashicons.dashicons-yes {
		        font-size: 20px;
		        vertical-align: middle;
		        color: #4fb94f;
		    }
		</style>

		<script type="text/javascript">
			jQuery(document).ready(function($){

				var $resendBtn = $('.xoo-uv-sev');

				//Hide resend email button
				if( $('#xoo-uv-active').val() === "yes" ){
					$resendBtn.hide();
				}

				//Resend Email
				$resendBtn.on('click',function(e){
					e.preventDefault();
					$(this).prop('disabled', true);
					var user_id = $(this).data('user_id');
					$.ajax({
						url: "<?php echo admin_url().'admin-ajax.php'; ?>",
						type: 'POST',
						data: {
							action: 'xoo_uv_resend_email',
							user_id: user_id
						},
						success: function(response){
							$resendBtn.prop('disabled', false);
							console.log(response);
						}
					})
				})
			})
		</script>

		<?php
	}
	
}

function xoo_uv_users_table(){
	return Xoo_Uv_Users_Table::get_instance();
}
xoo_uv_users_table();

?>

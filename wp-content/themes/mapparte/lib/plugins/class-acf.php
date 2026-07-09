<?php

namespace Mapparte;

/**
 * Class ACF
 *
 * @package Mapparte
 */
class ACF {

	public function __construct() {
		add_filter( 'acf/fields/google_map/api', [ $this, 'my_acf_google_map_api' ] );
		add_filter( 'acf/pre_submit_form', [ $this, 'pre_submit_form' ], 10, 1 );
		add_action( 'acf/input/admin_footer', [ $this, 'acf_30_step_minute' ] );
		add_action( 'acf/pre_update_value', [ $this, 'update_lat_lon' ], 10, 4 );
	}

	public function pre_submit_form( $form ) {
		if ( is_singular( 'booking' ) ) {
			return false;
		}
		return $form;
	}

	public function my_acf_google_map_api( $api ) {
		$api['key'] = get_field( 'google_maps_api', 'option' );
		return $api;
	}

	public static function get_acf_sub_field_key_by_field_name( $sub_field_name, array $field ) {

		$sub_fields = acf_get_fields( $field );

		foreach ( $sub_fields as $sub_field ) {
			if ( $sub_field['name'] === $sub_field_name ) {
				return $sub_field['key'];
			}
		}

		return '';
	}

	function acf_30_step_minute() {

		?>
        <script type="text/javascript">
            (function ($) {

                acf.add_filter('time_picker_args', function (args, field) {
                    args.timeFormat = 'HH:mm'
                    args.altTimeFormat = 'HH:mm'
                    args.stepMinute = 30

                    return args;
                });

            })(jQuery);
        </script>
		<?php

	}

	function update_lat_lon( $status, $new_value, $post_id, $field ) {

		$prev_values = get_fields( $post_id );

		$prev_value = isset( $prev_values['address'] ) ? $prev_values['address'] : '';

		if ( isset( $field['name'] ) && $field['name'] === 'address' ) {

			$new_value = json_decode( stripslashes( $new_value ), true );

			if ( is_array( $new_value ) && ( ! ( $new_value === $prev_value ) || ! $prev_value ) ) {
				if ( ! empty( $new_value['lat'] ) ) {
					update_post_meta( $post_id, 'lat', $new_value['lat'] );
				}
				if ( ! empty( $new_value['lng'] ) ) {
					update_post_meta( $post_id, 'lon', $new_value['lng'] );
				}
			}

		}
	}
}

new ACF();



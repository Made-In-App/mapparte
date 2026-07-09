<?php

namespace Mapparte;

/**
 * Class Voucher
 *
 * @package Mapparte
 */
class Voucher {

	/**
	 * Voucher constructor.
	 *
	 */
	public function __construct() {
		add_action( 'acf/save_post', [ $this, 'set_default_expiry_date' ], 11 );
	}

	/**
	 * Register the voucher post_type
	 */
	static public function get_voucher( $voucherCode = '', $price ) {
		$voucher = [];

		$voucher_ID   = '';
		$voucherValue = 0.00;
		$voucher['voucherUsed'] = 0;

		if ( $voucherCode ) {
			$query = new \WP_Query( [
				's'           => $voucherCode,
				'exact'       => true,
				'post_status' => 'publish',
				'post_type'   => 'voucher',
				'meta_query'  => [
					'relation' => 'AND',
					[
						'key'     => 'user',
						'value'   => get_current_user_id(),
						'compare' => '=',
					],
					[
						'key'     => 'expiry_date',
						'value'   => date( 'Y-m-d H:i:s' ),
						'compare' => '>',
					],
				],
			] );

			if ( $query->posts ) {
				$voucher_found = $query->posts[0];
				$voucher['voucherUsed'] = (int) get_post_meta( $voucher_found->ID, 'used', true );
				if ( $voucher['voucherUsed'] === 0 ) {
					$voucher_ID    = $voucher_found->ID;
					$price         = ( $price > 100 ) ? 100 : $price;
					$discount      = get_post_meta( $voucher_found->ID, 'voucher_discount', true );
					$voucherValue  = ( $price * $discount ) / 100;
				}
			}
		}
		$voucher['voucherID']    = $voucher_ID;
		$voucher['voucherCode']  = $voucherCode;
		$voucher['voucherValue'] = \Mapparte\Utils::format_price( $voucherValue );

		return $voucher;
	}

	/**
	 * Handle the logic behind voucher expire date
	 *
	 * @param $post_id
	 */
	function set_default_expiry_date( $post_id ) {

		if ( 'voucher' !== get_post_type( $post_id ) ) {
			return;
		}
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}
		if ( 'auto-draft' === $post_id ) {
			return;
		}
		if ( 'publish' === $_POST['post_status'] && $_POST['post_status'] != $_POST['original_post_status'] ) {

			$expiry_date = get_post_meta( $post_id, 'expiry_date', true );

			if ( ! $expiry_date ) {
				$expiry_date_key = acf_get_field( 'expiry_date' )['key'];
				update_post_meta( $post_id, 'expiry_date', date( 'Y-m-d H:i:s', strtotime( '+2 months' ) ) );
				update_post_meta( $post_id, '_expiry_date', $expiry_date_key );
			}
		}
	}
}

new Voucher();
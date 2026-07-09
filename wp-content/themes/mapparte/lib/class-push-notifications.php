<?php

namespace Mapparte;

/**
 * Class Push_Notification
 *
 * @package Mapparte
 */
class Push_Notification {


	static function sendMessage( $content, $params = null, $player_ids = null ) {

		$content = [ "en" => $content ];

		$fields = [
			'app_id'   => "9f20ea2c-89fb-436b-976f-b801cb34b284",
			'contents' => $content
		];
		if ( $player_ids ) {
			$fields['include_player_ids'] = $player_ids;
		} else {
			$fields['included_segments'] = [ 'Active Users' ];
		}

		if ( $params ) {
			$fields['data'] = $params;
		}

		if ( is_array( $player_ids ) ) {
			foreach ( $player_ids as $player_id ) {
				$fields['include_player_ids'] = [ $player_id ];
				$ch                           = curl_init();
				curl_setopt( $ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications" );
				curl_setopt( $ch, CURLOPT_HTTPHEADER, [
					'Content-Type: application/json; charset=utf-8',
					'Authorization: Basic NmE5ZGJlZmYtOGM0Yi00ZWNiLTgyY2MtN2VhMGUzMjVkYmM0'
				] );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch, CURLOPT_HEADER, false );
				curl_setopt( $ch, CURLOPT_POST, true );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
				curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

				$response = curl_exec( $ch );
				curl_close( $ch );
			}
		} else {
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications" );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json; charset=utf-8',
				'Authorization: Basic NmE5ZGJlZmYtOGM0Yi00ZWNiLTgyY2MtN2VhMGUzMjVkYmM0'
			] );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_HEADER, false );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

			$response = curl_exec( $ch );
			curl_close( $ch );
		}

		return $response;
	}

}


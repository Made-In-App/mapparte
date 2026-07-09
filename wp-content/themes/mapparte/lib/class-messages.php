<?php

namespace Mapparte;

/**
 * Class Messages
 *
 * @package Mapparte
 */
class Messages {

	public function __construct() {
		add_action( 'user_register', [ $this, 'create_hidden_post_for_messages' ] );
		add_action( 'wp_insert_comment', [ $this, 'add_comments_meta_for_messages' ], 10, 2 );
		add_filter( 'rest_endpoints', [ $this, 'remove_comments_from_rest_api' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );
		add_action( 'wp_ajax_send_message', [ $this, 'ajax_send_message' ] );
		add_action( 'wp_ajax_nopriv_send_message', [ $this, 'must_login' ] );
	}

	/**
	 * Create an hidden post on user creation to handle direct messages
	 *
	 * @param $user_id
	 */
	function create_hidden_post_for_messages( $user_id ) {
		if ( is_int( $user_id ) ) {
			$args = [
				'post_title'  => $user_id,
				'post_status' => 'private',
				'post_type'   => 'hidden_post',
				'post_author' => $user_id,
			];
			wp_insert_post( $args );
		}
	}

	/**
	 * Add from/to comments meta
	 *
	 * @param $id
	 * @param $comment
	 */
	function add_comments_meta_for_messages( $id, $comment ) {
		// Disapprova commenti per sicurezza
		$args['comment_ID']       = $id;
		$args['comment_approved'] = 0;
		wp_update_comment( $args );

		add_comment_meta( $id, 'from', get_current_user_id(), true ); // Salva il mittente su un comment meta

		if ( $comment->comment_parent != 0 ) { // Se e' un reply del thread originale definisci il destinatario.
			$from_to   = [];
			$from_to[] = (int) get_comment_meta( $comment->comment_parent, 'to', true );
			$from_to[] = (int) get_comment_meta( $comment->comment_parent, 'from', true );

			$get_to = array_values( array_diff( $from_to, [ get_current_user_id() ] ) );

			$to = ( isset( $get_to[0] ) ) ? $get_to[0] : get_current_user_id();

			$to_notify    = get_the_author_meta( 'email', $to );

			$subject = __("Mapparte - Nuovo messaggio.",'mapparte');

			$message = sprintf( __("Hai ricevuto un nuovo messaggio:<br><br>%s",'mapparte'),
				esc_html( $comment->comment_content ),
				);

			$call_to_action = sprintf(__("Rispondi al messaggio",'mapparte') );

			$call_to_action_url = sprintf( "%s/messaggi/%d/",
				esc_url( get_home_url() ),
				$id
			);

			$footer = __("Il team Mapparte!",'mapparte');

			$args = [
				'h1'                 => false,
				'body'               => $message,
				'call_to_action'     => $call_to_action,
				'call_to_action_url' => $call_to_action_url,
				'footer'             => $footer,
			];

			\Mapparte\Email_Notification::send_email( $to_notify, $subject, $args );

			$notifiche_prenotazione = get_the_author_meta( 'mostra_notifiche', $to );

			if ( $notifiche_prenotazione ) {

				$one_signal             = get_the_author_meta( '_one_signal_tokens', $to );
				$one_signal_tokens = json_decode( $one_signal, true );
				$one_signal_ids = ( is_array( $one_signal_tokens ) ) ? array_keys( $one_signal_tokens ) : false;
				$message_notification = __("Hai ricevuto un nuovo messaggio. Accedi al sito per visualizzarlo.",'mapparte');

				if ( $one_signal_ids ) {
					\Mapparte\Push_Notification::sendMessage( $subject, [
						"popup_message" => $message_notification,
						"popup_link"    => $call_to_action_url
					], $one_signal_ids );
				}

			}

		} else { // Se e' il primo commento del thread usa il post_author come destinatario
			$post_type = get_post_type( $comment->comment_post_ID );
			if ( $post_type === 'booking' ) {
				$details = get_post_meta( $comment->comment_post_ID, '_booking_details', true );
				$to      = ( (int) get_post_field( 'post_author', $details['spaceId'] ) === get_current_user_id() ) ? $details['userId'] : get_post_field( 'post_author', $details['spaceId'] ) ;
			} else {
				$to = get_post_field( 'post_author', $comment->comment_post_ID );

				$space_title = get_post_field( 'post_title', $comment->comment_post_ID );
				$to_email    = get_the_author_meta( 'email', $to );
				$from_name   = get_the_author_meta( 'nicename', get_current_user_id() );

				$subject = __("Mapparte -  richiesta di informazioni all’host",'mapparte');

				$message = sprintf( __("%s hai ricevuto un nuovo messaggio da  %s:<br><br>%s",'mapparte'),
					esc_html( $space_title ),
					$from_name,
					esc_html( $comment->comment_content ),
				);

				$call_to_action = sprintf(__("Rispondi al messaggio",'mapparte') );

				$call_to_action_url = sprintf( "%s/messaggi/%d/",
					esc_url( get_home_url() ),
					$id
				);

				$footer = __("Il team Mapparte!",'mapparte');

				$args = [
					'h1'                 => false,
					'body'               => $message,
					'call_to_action'     => $call_to_action,
					'call_to_action_url' => $call_to_action_url,
					'footer'             => $footer,
				];

				\Mapparte\Email_Notification::send_email( $to_email, $subject, $args );

			}
		}
		add_comment_meta( $id, 'to', $to, true );
	}

	/**
	 * Get all messages available for a specific user
	 *
	 * @return array|object|null
	 */
	public static function get_messages() {
		global $wpdb, $wp_query;

		$query = "select DISTINCT c.comment_ID,c.comment_author,c.comment_content,c.comment_date,c.comment_post_ID,p.post_title,p.post_type from ".$wpdb->prefix."comments as c ";
		$query .= "INNER JOIN ".$wpdb->prefix."commentmeta as cm ON c.comment_ID = cm.comment_id AND ";
		if ( isset( $wp_query->query_vars['mine'] ) && $wp_query->query_vars['mine'] ) {
			$query .= "cm.meta_key=\"from\" AND cm.meta_value=\"" . get_current_user_id() . "\" ";
		} else {
			$query .= "cm.meta_key=\"to\" AND cm.meta_value=\"" . get_current_user_id() . "\" ";
		}
		$query .= "LEFT JOIN ".$wpdb->prefix."posts as p ON p.ID = c.comment_post_ID ORDER BY c.comment_date DESC";

		return $wpdb->get_results( $query );
	}

	/**
	 * Get thread ID for a specific post id
	 *
	 * @return array|object|null
	 */
	public static function get_comment_thread_by_post_id( $post_id ) {
		global $wpdb;

		$query = "select comment_ID from ".$wpdb->prefix."comments ";
		$query .= "WHERE comment_parent=0 AND comment_post_ID=" . $post_id;

		return $wpdb->get_results( $query );
	}

	/**
	 * Get message details for a specific post id
	 *
	 * @return array|object|null
	 */
	public static function get_comments_by_post_id( $post_id ) {
		global $wpdb;

		$query = "select * from ".$wpdb->prefix."comments ";
		$query .= "WHERE comment_post_ID=" . $post_id;

		echo $query;

		return $wpdb->get_results( $query );
	}


	/**
	 * Get details for a message thread
	 *
	 * @param int $comment_id
	 *
	 * @return array|bool|object|null
	 */
	public static function get_messages_details( $comment_id = 0 ) {
		global $wpdb;

		if ( ! $comment_id ) {
			return false;
		}

		$results = [];

		$query_1            = "select DISTINCT c.*,p.post_title,p.post_type from ".$wpdb->prefix."comments as c INNER JOIN ".$wpdb->prefix."commentmeta as cm ON c.comment_ID = cm.comment_id ";
		$query_1            .= "AND ( ( cm.meta_key=\"to\" AND cm.meta_value=\"" . get_current_user_id() . "\" ) OR (cm.meta_key=\"from\" AND cm.meta_value=\"" . get_current_user_id() . "\" ) ) ";
		$query_1            .= "INNER JOIN ".$wpdb->prefix."posts as p ON p.ID = c.comment_post_ID AND ( c.comment_ID=" . $comment_id . " OR c.comment_parent=" . $comment_id . ") ORDER BY c.comment_date ASC";
		$results['results'] = $wpdb->get_results( $query_1 );

		// Se e un reply prendi chiedi tutti i messaggi del thread
		if ( isset( $results['results'][0] ) && $results['results'][0]->comment_parent != '0' ) {
			$results['parent']  = $results['results'][0]->comment_parent;
			$query_2            = "select DISTINCT c.*,p.post_title,p.post_type from ".$wpdb->prefix."comments as c INNER JOIN ".$wpdb->prefix."commentmeta as cm ON c.comment_ID = cm.comment_id ";
			$query_2            .= "AND ( ( cm.meta_key=\"to\" AND cm.meta_value=\"" . get_current_user_id() . "\" ) OR (cm.meta_key=\"from\" AND cm.meta_value=\"" . get_current_user_id() . "\" ) ) ";
			$query_2            .= "INNER JOIN ".$wpdb->prefix."posts as p ON p.ID = c.comment_post_ID AND ( c.comment_ID=" . $results['parent'] . " OR c.comment_parent=" . $results['parent'] . ") ORDER BY c.comment_date ASC";
			$results['results'] = $wpdb->get_results( $query_2 );
		}

		if ( isset( $results['results'] ) && isset( $results['results'][0]->comment_post_ID ) ) {
			$results['comment_post_ID'] = $results['results'][0]->comment_post_ID;
		}

		return $results;
	}

	/**
	 * Send message handler
	 *
	 * @param $message
	 * @param int $thread
	 * @param $comment_post_id
	 *
	 * @return bool|false|int
	 */
	public static function send_message( $message, $thread = 0, $comment_post_id ) {

		if ( get_post_status ( $comment_post_id ) ) {
			global $current_user, $user_ID;
			if ( ! is_user_logged_in() ) {
				return false;
			};
			$time = current_time( 'mysql', $gmt = 0 );

			// Set comment data
			$data = array(
				'comment_post_ID'      => (int) $comment_post_id,
				'comment_author'       => esc_sql( $current_user->display_name ),
				'comment_author_email' => esc_sql( $current_user->user_email ),
				'comment_author_url'   => esc_sql( $current_user->user_url ),
				'comment_content'      => wp_kses( $message, array(
					'a'          => array(
						'href'  => array(),
						'title' => array()
					),
					'b'          => array(),
					'i'          => array(),
					'strong'     => array(),
					'em'         => array(),
					'u'          => array(),
					'del'        => array(),
					'blockquote' => array(),
					'sub'        => array(),
					'sup'        => array()
				) ),
				'user_id'              => (int) $user_ID,
				'comment_author_IP'    => esc_sql( $_SERVER['REMOTE_ADDR'] ),
				'comment_agent'        => esc_sql( $_SERVER['HTTP_USER_AGENT'] ),
				'comment_date'         => $time,
				'comment_date_gmt'     => $time,
				'comment_parent'       => $thread,
				'comment_approved'     => 0
			);

			return wp_insert_comment( $data );
		} else {
			return false;
		}

	}

	/**
	 * Hide comments endpoints form the REST API
	 *
	 * @param $endpoints
	 *
	 * @return mixed
	 */
	function remove_comments_from_rest_api( $endpoints ) {
		foreach ( $endpoints as $key => $index ) {
			if ( strpos( $key, '/wp/v2/comments' ) !== false ) {
				unset( $endpoints[ $key ] );
			}
		}

		return $endpoints;
	}

	/**
	 * enqueue scripts
	 */
	public function enqueue_script() {
		if ( is_singular( 'space' ) ) {
			wp_enqueue_script( 'message-script', get_template_directory_uri() . '/assets/js/message-script.js', array( 'jquery' ) );
			wp_localize_script( 'message-script', 'messages', array(
					'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
					'nonce'         => wp_create_nonce( 'messages-nonce' ),
					'spaceId'       => get_the_ID(),
					'currentUserId' => get_current_user_id(),
				)
			);
		}
	}

	/**
	 * Handler for the send message ajax call
	 */
	function ajax_send_message() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], "messages-nonce" ) ) {
			exit( "No naughty business please." );
		}
		self::send_message( sanitize_text_field( $_REQUEST['message'] ), 0, sanitize_text_field( $_REQUEST['spaceId'] ) );

		die( 0 );
	}

	function must_login() {
		echo "You must log in to vote";
		die();
	}
}

new Messages();
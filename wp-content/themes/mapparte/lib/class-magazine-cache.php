<?php

namespace Mapparte;

/**
 * Keeps Aruba's anonymous page cache in sync with Magazine content.
 */
class Magazine_Cache {

	const LAST_RESULT_OPTION = 'mapparte_magazine_cache_last_purge';
	const RETRY_HOOK         = 'mapparte_magazine_cache_retry';
	const MAX_ATTEMPTS       = 3;

	private $purge_queued = false;
	private $queued_reason = '';
	private $queued_post_id = 0;

	public function __construct() {
		add_action( 'save_post_post', [ $this, 'queue_post_purge' ], 100, 3 );
		add_action( 'trashed_post', [ $this, 'queue_post_status_purge' ], 100, 1 );
		add_action( 'untrashed_post', [ $this, 'queue_post_status_purge' ], 100, 1 );
		add_action( 'before_delete_post', [ $this, 'queue_deleted_post_purge' ], 100, 2 );
		add_action( 'acf/save_post', [ $this, 'queue_options_purge' ], 100, 1 );
		add_action( self::RETRY_HOOK, [ $this, 'retry_purge' ], 10, 3 );
		add_action( 'template_redirect', [ $this, 'disable_magazine_page_cache' ], 0 );
	}

	public function queue_post_purge( $post_id, $post, $update ) {
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}

		$this->queue_purge( $update ? 'post_updated' : 'post_created', $post_id );
	}

	public function queue_post_status_purge( $post_id ) {
		if ( 'post' === get_post_type( $post_id ) ) {
			$this->queue_purge( 'post_status_changed', $post_id );
		}
	}

	public function queue_deleted_post_purge( $post_id, $post = null ) {
		if ( ! $post instanceof \WP_Post ) {
			$post = get_post( $post_id );
		}

		if ( $post instanceof \WP_Post && 'post' === $post->post_type ) {
			$this->queue_purge( 'post_deleted', $post_id );
		}
	}

	public function queue_options_purge( $post_id ) {
		if ( 'options' === $post_id || 'option' === $post_id ) {
			$this->queue_purge( 'magazine_options_updated', 0 );
		}
	}

	private function queue_purge( $reason, $post_id ) {
		$this->queued_reason  = $reason;
		$this->queued_post_id = (int) $post_id;

		if ( $this->purge_queued ) {
			return;
		}

		$this->purge_queued = true;
		add_action( 'shutdown', [ $this, 'run_queued_purge' ], PHP_INT_MAX );
	}

	public function run_queued_purge() {
		if ( ! $this->purge_queued ) {
			return;
		}

		$this->purge_queued = false;
		$this->purge_all( $this->queued_reason, $this->queued_post_id, 1 );
	}

	public function retry_purge( $reason, $post_id, $attempt ) {
		$this->purge_all( (string) $reason, (int) $post_id, (int) $attempt );
	}

	/**
	 * Purge the complete Aruba HiSpeed Cache through its local proxy endpoint.
	 */
	public function purge_all( $reason = 'manual', $post_id = 0, $attempt = 1 ) {
		$host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );
		$response = wp_remote_get(
			'http://127.0.0.1:8889/purge/',
			[
				'timeout' => 5,
				'headers' => [ 'Host' => $host ],
			]
		);

		$error = is_wp_error( $response ) ? $response->get_error_message() : '';
		$code  = is_wp_error( $response ) ? 0 : (int) wp_remote_retrieve_response_code( $response );
		$ok    = ! is_wp_error( $response ) && $code >= 200 && $code < 400;

		update_option(
			self::LAST_RESULT_OPTION,
			[
				'success' => $ok,
				'time'    => current_time( 'mysql' ),
				'reason'  => sanitize_key( $reason ),
				'post_id' => (int) $post_id,
				'attempt' => (int) $attempt,
				'code'    => $code,
				'error'   => sanitize_text_field( $error ),
			],
			false
		);

		if ( $ok ) {
			return true;
		}

		if ( $attempt < self::MAX_ATTEMPTS ) {
			wp_schedule_single_event(
				time() + ( 60 * $attempt ),
				self::RETRY_HOOK,
				[ (string) $reason, (int) $post_id, (int) $attempt + 1 ]
			);
		}

		error_log( sprintf( 'Mapparte Magazine cache purge failed (attempt %d, HTTP %d): %s', $attempt, $code, $error ) );
		return false;
	}

	/**
	 * Magazine pages must never depend on a potentially stale anonymous cache.
	 */
	public function disable_magazine_page_cache() {
		if ( is_admin() || ! $this->is_magazine_request() ) {
			return;
		}

		nocache_headers();
		header( 'Cache-Control: no-cache, no-store, must-revalidate, max-age=0', true );
		header( 'X-Mapparte-Magazine-Cache: bypass', true );
	}

	private function is_magazine_request() {
		if ( is_home() || is_singular( 'post' ) || is_category() || is_tag() ) {
			return true;
		}

		$post_type = get_query_var( 'post_type' );
		return is_search() && ( 'post' === $post_type || ( is_array( $post_type ) && in_array( 'post', $post_type, true ) ) );
	}
}

new Magazine_Cache();

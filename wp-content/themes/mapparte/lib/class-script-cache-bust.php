<?php
/**
 * Cache-busting per script selezionati: aggiunge ?cb=<filemtime> così ogni modifica al file
 * forza il browser a scaricare la nuova versione (indipendente da ?ver= del plugin).
 *
 * @package Mapparte
 */

namespace Mapparte;

class Script_Cache_Bust {

	public function __construct() {
		add_filter( 'script_loader_src', [ $this, 'bust_handles' ], 20, 2 );
	}

	/**
	 * Handle => path assoluto sul filesystem.
	 *
	 * @return array<string, string>
	 */
	private function handle_paths() {
		return [
			'xoo-sl-js'         => WP_PLUGIN_DIR . '/social-login-woocommerce/assets/js/xoo-sl-js.js',
			'xoo-sl-google-sdk' => WP_PLUGIN_DIR . '/social-login-woocommerce/assets/js/google/google-sdk.js',
			'xoo-sl-fb-sdk'     => WP_PLUGIN_DIR . '/social-login-woocommerce/assets/js/facebook/facebook-sdk.js',
		];
	}

	/**
	 * @param string|false $src    URL dello script.
	 * @param string       $handle Handle registrato.
	 * @return string|false
	 */
	public function bust_handles( $src, $handle ) {
		if ( ! $src || ! is_string( $handle ) ) {
			return $src;
		}

		$paths = $this->handle_paths();
		if ( empty( $paths[ $handle ] ) || ! is_readable( $paths[ $handle ] ) ) {
			return $src;
		}

		$mtime = (int) filemtime( $paths[ $handle ] );
		if ( $mtime <= 0 ) {
			return $src;
		}

		$src = remove_query_arg( 'ver', $src );
		return add_query_arg( 'cb', (string) $mtime, $src );
	}
}

new Script_Cache_Bust();

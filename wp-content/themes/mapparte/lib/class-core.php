<?php

namespace Mapparte;

/**
 * Class Core
 *
 * @package Mapparte
 */
class Core {

	public function __construct() {
		add_action( 'init', [ $this, 'setAutoPages' ] );
		add_action( 'init', [ $this, 'registerMenu' ] );
		add_action( 'init', [ $this, 'createTables' ] );
		add_action( 'init', [ $this, 'setACFCustoPage' ] );
		add_action( 'init', [ $this, 'addThemeSupport' ] );
		add_action( 'after_setup_theme', [ $this, 'addCustomImageSizes' ] );
		add_action( 'after_setup_theme', [ $this, 'remove_admin_bar' ] );
		add_filter( 'request', [ $this, 'allow_preview_post_status_in_request' ], 1 );
		add_filter( 'the_posts', [ $this, 'inject_preview_space_if_empty' ], 10, 2 );
		add_action( 'pre_get_posts', [ $this, 'my_custom_query_blog' ] );
		add_action( 'pre_get_posts', [ $this, 'my_filter_space_search' ], 12 );
		add_action( 'wp_dashboard_setup', [ $this, 'remove_dashboard_widgets' ] );
		add_action('after_setup_theme', [ $this, 'mapparte_setup']);
 
	}

	/**
	 * Imposta post_status nella request per permettere l'anteprima (draft/pending) con preview_id.
	 * Necessario perché con WP/PHP più recenti la query di default esclude i draft.
	 */
	public function allow_preview_post_status_in_request( $query_vars ) {
		if ( is_admin() || ! is_user_logged_in() ) {
			return $query_vars;
		}
		$p = isset( $query_vars['p'] ) ? (int) $query_vars['p'] : ( isset( $_GET['p'] ) ? (int) $_GET['p'] : 0 );
		$preview_id = isset( $_GET['preview_id'] ) ? (int) $_GET['preview_id'] : 0;
		if ( $preview_id > 0 && $p <= 0 ) {
			$p = $preview_id;
			$query_vars['p'] = $preview_id;
		}
		if ( ( $preview_id > 0 || isset( $query_vars['preview_id'] ) ) && $p > 0 ) {
			$ptype = isset( $query_vars['post_type'] ) ? $query_vars['post_type'] : ( isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : '' );
			// Booking usa stati custom, non solo publish/draft: senza 'any' la preview dà 404.
			if ( 'booking' === $ptype ) {
				$query_vars['post_status'] = 'any';
			} else {
				$query_vars['post_status'] = array( 'publish', 'draft', 'pending', 'private' );
			}
		}
		return $query_vars;
	}

	/**
	 * Se la main query restituisce 0 post in anteprima spazio (preview_id + p), inietta il post.
	 * Risolve 404/capability quando la query non restituisce il draft.
	 */
	public function inject_preview_space_if_empty( $posts, $query ) {
		if ( ! $query->is_main_query() || ! empty( $posts ) ) {
			return $posts;
		}
		$preview_id = isset( $_GET['preview_id'] ) ? (int) $_GET['preview_id'] : 0;
		$p          = isset( $_GET['p'] ) ? (int) $_GET['p'] : 0;
		$id         = $preview_id ? $preview_id : $p;
		if ( $id <= 0 || ! is_user_logged_in() ) {
			return $posts;
		}
		$post = get_post( $id );
		if ( ! $post ) {
			return $posts;
		}

		if ( 'space' === $post->post_type ) {
			if ( ! current_user_can( 'edit_post', $post->ID ) ) {
				return $posts;
			}
			$query->is_404            = false;
			$query->is_singular       = true;
			$query->is_single         = true;
			$query->queried_object    = $post;
			$query->queried_object_id = (int) $post->ID;
			$query->query_vars['post_type'] = 'space';
			return array( $post );
		}

		if ( 'booking' === $post->post_type ) {
			$details  = get_post_meta( $post->ID, '_booking_details', true );
			$space_id = isset( $details['spaceId'] ) ? (int) $details['spaceId'] : 0;
			$uid      = get_current_user_id();
			$can      = ( (int) $post->post_author === $uid )
				|| ( $space_id && (int) get_post_field( 'post_author', $space_id ) === $uid )
				|| current_user_can( 'manage_options' );
			if ( ! $can ) {
				return $posts;
			}
			$query->is_404            = false;
			$query->is_singular       = true;
			$query->is_single         = true;
			$query->queried_object    = $post;
			$query->queried_object_id = (int) $post->ID;
			$query->query_vars['post_type'] = 'booking';
			return array( $post );
		}

		return $posts;
	}

		
	/**
	 * Load translations for mapparte
	 */
	function mapparte_setup(){
		load_theme_textdomain('mapparte', get_template_directory() . '/languages');
	}

	/**
	 * Init functions
	 */

	function my_filter_space_search( $query ) {

		global $wpdb;

		if ( defined( 'DOING_CRON' ) ) {
			return $query;
		}

		if ( ! is_admin() && isset( $query->query_vars['post_type'] ) && 'space' === $query->query_vars['post_type'] && ! isset( $query->query_vars['mine'] ) || ( self::is_rest_api_request() && isset( $query->query_vars['post_type'] ) && 'space' === $query->query_vars['post_type'] ) ) {

			// Singolo spazio o anteprima: non applicare filtri archivio e permettere draft in preview
			if ( $query->is_main_query() && ( $query->get( 'p' ) || $query->get( 'page_id' ) || $query->get( 'name' ) || isset( $_GET['preview_id'] ) ) ) {
				if ( isset( $_GET['preview_id'] ) && is_user_logged_in() ) {
					$query->set( 'post_status', array( 'publish', 'draft', 'pending', 'private' ) );
				}
				return $query;
			}

			if ( isset( $_GET["lat"] ) && isset( $_GET["lon"] ) ) {

				$tablePrefix     = $wpdb->prefix;
				$query_proximity = $wpdb->prepare( "SELECT " . $tablePrefix . "posts.ID," . $tablePrefix . "posts.post_title,g.meta_value lat,h.meta_value lon,
  ( 3959 * acos( cos( radians('%s') ) * cos( radians( g.meta_value ) ) * cos( radians( h.meta_value ) - radians(%s) ) + sin( radians('%s') ) * sin( radians( g.meta_value ) ) ) ) AS distance
  FROM " . $tablePrefix . "posts
  LEFT JOIN " . $tablePrefix . "postmeta g ON " . $tablePrefix . "posts.ID = g.post_ID AND g.meta_key='lat'
  LEFT JOIN " . $tablePrefix . "postmeta h ON " . $tablePrefix . "posts.ID = h.post_ID AND h.meta_key='lon'
  WHERE " . $tablePrefix . "posts.post_type = 'space' AND " . $tablePrefix . "posts.post_status = 'publish'
  HAVING distance < '%s'
  ORDER BY distance",
					$_GET["lat"],
					$_GET["lon"],
					$_GET["lat"],
					50 );

				$spaces_near_me = $wpdb->get_results( $query_proximity, ARRAY_A );

				$spaces_ids     = array_map( function ( $ar ) {
					return $ar['ID'];
				}, $spaces_near_me );

				if ( isset( $spaces_ids ) && sizeof( $spaces_ids ) ) {
					$query->set( 'post__in', $spaces_ids );
				}
			}

			// TAXONOMY QUERIES
			if ( isset( $_GET["s_activity"] ) && ! empty( $_GET["s_activity"] ) ||
			     isset( $_GET["s_typology"] ) && ! empty( $_GET["s_typology"] ) ) {

				$tax_query = [
					'relation' => 'AND'
				];

				if ( isset( $_GET["s_activity"] ) && ! empty( $_GET["s_activity"] ) ) {
					$tax_query[] = [
						'taxonomy' => 'activity',
						'field'    => 'id',
						'terms'    => [ $_GET["s_activity"] ]
					];
				}

				if ( isset( $_GET["s_typology"] ) && ! empty( $_GET["s_typology"] ) ) {
					$tax_query[] = [
						'taxonomy' => 'typology',
						'field'    => 'id',
						'terms'    => $_GET["s_typology"]
					];
				}

				$query->set( 'tax_query', $tax_query );

			}

			$meta_obj = [
				'relation' => 'AND'
			];

			//META QUERIES
			$vettRequest = [
				"space_mq",
				"max_people",
				"accessibility",
				"space_access",
				"space_external",
				"features",
				"floor_type",
				"rooms"
			];
			foreach ( $vettRequest as $req ) {
				if ( ! empty( $_GET[ $req ] ) ) {
					if ( is_array( $_GET[ $req ] ) ) {
						$meta_obj_child = [];
						for ( $i = 0; $i < count( $_GET[ $req ] ); $i ++ ) {
							$meta_obj_child[] = [
								'key'     => $req,
								'value'   => $wpdb->esc_like( $_GET[ $req ][ $i ] ),
								'compare' => 'LIKE'
							];

						}
						$meta_obj[] = [ 'relation' => 'OR', $meta_obj_child ];
					} else {
						if ( $req == "space_mq" || $req == "max_people" ) {
							$meta_obj[] = [
								'key'     => $req,
								'value'   => $_GET[ $req ],
								'type'    => 'numeric',
								'compare' => '>='
							];
						} else {
							$meta_obj[] = [
								'key'     => $req,
								'value'   => $_GET[ $req ],
								'compare' => '='
							];
						}
					}
				}
			}

			// GEOLOCALIZZATION QUERY
			if ( isset( $_GET["city"] ) && ! empty( $_GET["city"] ) ) {
				$meta_obj[] = [
					'key'     => 'address',
					'value'   => $wpdb->esc_like( $_GET["city"] ),
					'compare' => 'LIKE'
				];
			}

			// TARIFFA QUERY
			if ( isset( $_GET["fare"] ) && ! empty( $_GET["fare"] ) ) {
				// TARIFFA QUERY
				if ( $_GET["fare"] == "1" ) { //oraria infrasettimanale
					$meta_obj[] = [
						'key'     => 'price_hour',
						'value'   => explode( ";", $_GET["priceRange"] ),
						'type'    => 'numeric',
						'compare' => 'BETWEEN',

					];
				}
				if ( $_GET["fare"] == "2" ) { //prezzo orario weekend
					$meta_obj[] = [
						'key'     => 'price_hour_weekend',
						'value'   => explode( ";", $_GET["priceRange"] ),
						'type'    => 'numeric',
						'compare' => 'BETWEEN',

					];
				}
				if ( $_GET["fare"] == "3" ) { //tariffa giornaliera
					$meta_obj[] = [
						'key'     => 'min_price_day',
						'value'   => explode( ";", $_GET["priceRange"] ),
						'type'    => 'numeric',
						'compare' => 'BETWEEN',
					];
				}
				if ( $_GET["fare"] == "4" ) { //tariffa weekend
					$meta_obj[] = [
						'key'     => 'price_weekend',
						'value'   => explode( ";", $_GET["priceRange"] ),
						'type'    => 'numeric',
						'compare' => 'BETWEEN',

					];
				}
			}

			if ( ! empty ( $meta_obj ) ) {
				$query->set( 'meta_query', $meta_obj );
			}


			if ( isset( $spaces_ids ) && sizeof( $spaces_ids ) ) {
				$query->set( 'orderby', 'post__in' );
			} else {
				// Logica precedente (puo' escludere spazi senza sponsored_expired):
				// $query->set( 'meta_key', 'sponsored_expired' );
				// $query->set( 'orderby', 'meta_value' );
				// $query->set( 'meta_type', 'DATE' );
				// Nuova logica: ordina per data senza filtrare gli spazi senza meta.
				$query->set( 'orderby', 'date' );
				$query->set( 'order', 'DESC' );
			}
		}
	}

	public function my_custom_query_blog( $query ) {

		if ( ! is_admin() && $query->is_main_query() && $query->is_home() ) {
			$ids         = [];
			$in_evidenza = get_field( "in_evidenza", "option" );
			if ( ! empty( $in_evidenza ) ) {
				$ids[] = $in_evidenza->ID;
			}
			$in_evidenza_altri = get_field( "in_evidenza_altri", "option" );
			if ( ! empty( $in_evidenza_altri ) ) {
				foreach ( $in_evidenza_altri as $post ) {
					$ids[] = $post->ID;
				}
			}
			$breaking_news = get_field( "breaking_news", "option" );
			if ( ! empty( $breaking_news ) ) {
				foreach ( $breaking_news as $post ) {
					$ids[] = $post->ID;
				}
			}
			$query->set( 'post__not_in', $ids );
			$query->set( 'post_type', [ 'post' ] );
		}
		if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
			$query->set( 'post_type', [ 'post' ] );
		}
	}

	public function addThemeSupport() {
		add_theme_support( 'post-thumbnails' );

		update_option( 'thumbnail_crop', 1 );
		update_option( 'thumbnail_size_w', 219 );
		update_option( 'thumbnail_size_h', 147 );
		update_option( 'medium_crop', 1 );
		update_option( 'medium_size_w', 364 );
		update_option( 'medium_size_h', 161 );
		update_option( 'large_crop', 1 );
		update_option( 'large_size_w', 1080 );
		update_option( 'large_size_h', 478 );

		add_image_size( 'large_mobile', 575, 400, true );
		add_image_size( 'square', 240, 240, true );
		add_image_size( 'gallery', 420, 185, true );

		remove_filter( 'the_excerpt', 'wpautop' );
		/*
				Home slider:
				1900 x 990 desktop
				4414 x 290 mobile

				Cards location (sia in home che nella ricerca):
				370 x 210

				Card magazine big
				430 x 290

				Card slider single:
				710 x 315 desktop
				245 x 170 mobile

				Featured image single post:
				1260 x 560 desktop
				355 x 250 mobile

		*/


		add_post_type_support( 'page', 'excerpt' );

	}

	public function setAutoPages() {
		$createPages = [
			"HOME PAGE",
			"MESSAGGI",
			"PROFILO",
			"PREFERITI",
			"INSERISCI IL TUO SPAZIO",
			"ATTIVA SPONSORIZZAZIONE",
			"DETTAGLIO SPONSORIZZAZIONE",
			"MAGAZINE",
			"COME FUNZIONA",
			"SCARICA L'APP",
			"SPAZIO",
			"CONTATTI",
			"POSIZIONI APERTE",
			"TEAM",
			"Termini e condizioni d’uso",
			"Cookies policy",
			"checkout",
			"sponsorizzazioni",
			"Test Push notification"
		];
		// Create Homepage if not exist
		foreach ( $createPages as $createPage ) {
			if ( get_page_by_title( $createPage ) == null ) {
				wp_insert_post( [
					'post_title'  => $createPage,
					'post_status' => 'publish',
					'post_author' => 1,
					'post_type'   => 'page'
				] );
			}
		}
	}

	public function setACFCustoPage() {
		if ( function_exists( 'acf_add_options_page' ) ) {

			acf_add_options_page( array(
				'page_title' => 'Configurazione sito',
				'menu_title' => 'Configurazione sito',
				'menu_slug'  => 'site-settings',
				'capability' => 'edit_posts',
				'redirect'   => false
			) );


		}
	}

	public function registerMenu() {
		register_nav_menus( [
				'logged'     => 'Logged Menu',
				'not-logged' => 'Not Logged Menu',
				'footer-1'   => 'Footer Menu 1',
				'footer-2'   => 'Footer Menu 2',
			]
		);
	}

	public function remove_admin_bar() {
		if ( ! current_user_can( 'manage_options' ) ) {
			show_admin_bar( false );
		}
	}

	public static function createTables() {
		global $wpdb;
		$charset_collate = '';
		if ( ! empty( $wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
		try {
			$sql = self::getSqlScript( 'create.sql' );
			if ( $sql ) {
				$sql = str_replace( 'prefix_', $wpdb->prefix, $sql );
				$sql = str_replace( 'charset_collate_placeholder', $charset_collate, $sql );
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
			}
		} catch ( Exception $e ) {
			update_option( 'mapparte_db_create_error', $e->getMessage() );
		}
	}

	private static function getSqlScript( $file_name ) {

		$path    = dirname( __FILE__ ) . '/sql/' . $file_name;
		$content = '';

		$myfile = fopen( $path, "rb" ) or die( "Unable to open file!" );

		if ( $handle = fopen( $path, 'rb' ) ) {
			$len = filesize( $path );

			if ( $len > 0 ) {
				$content = fread( $handle, $len );
			}
			fclose( $handle );
		}

		return trim( $content );
	}

	public function addCustomImageSizes() {
		add_image_size( 'home-slider-desktop', 1900, 990, array( 'center', 'center' ) );
		add_image_size( 'home-slider-mobile', 414, 290, array( 'center', 'center' ) );
		add_image_size( 'cards-location', 370, 210, array( 'center', 'center' ) );
		add_image_size( 'card-magazine-big', 430, 290, array( 'center', 'center' ) );
		add_image_size( 'card-magazine-list', 240, 240, array( 'center', 'center' ) );
		add_image_size( 'slider-single-desktop', 710, 315, array( 'center', 'center' ) );
		add_image_size( 'slider-single-mobile', 245, 170, array( 'center', 'center' ) );
		add_image_size( 'featured-image-desktop', 1260, 560, array( 'center', 'center' ) );
		add_image_size( 'featured-image-mobile', 355, 250, array( 'center', 'center' ) );
		add_image_size( 'squared-small', 55, 55, array( 'center', 'center' ) );
	}

	/** Remove some elements from the Dashboard */
	function remove_dashboard_widgets() {
		global $wp_meta_boxes;

		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] );
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts'] );
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] );
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'] );
		unset( $wp_meta_boxes['dashboard']['normal']['core']['wpglobus_dashboard_news'] );
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );

	}

	function is_rest_api_request() {
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			// Probably a CLI request
			return false;
		}

		$rest_prefix         = trailingslashit( rest_get_url_prefix() );
		$is_rest_api_request = strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) !== false;

		return apply_filters( 'is_rest_api_request', $is_rest_api_request );
	}

}

new Core();
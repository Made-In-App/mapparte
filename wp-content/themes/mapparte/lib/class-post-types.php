<?php

namespace Mapparte;

/**
 * Class Post_Types
 *
 * @package Mapparte
 */
class Post_Types {

	public function __construct() {
		add_action( 'init', [ $this, 'register_space_post_type' ] );
		add_action( 'init', [ $this, 'register_booking_post_type' ] );
		add_action( 'init', [ $this, 'register_voucher_post_type' ] );
		add_action( 'init', [ $this, 'register_service_post_type' ] );
		add_action( 'init', [ $this, 'register_testimonial_post_type' ] );
		add_action( 'init', [ $this, 'register_hidden_post_type' ] );
	}

	/**
	 * Register the space post_type
	 */
	public function register_space_post_type() {

		$singular_name = __( 'Spazio' );
		$plural_name   = __( 'Spazi' );
		$labels        = [
			'name'               => $singular_name,
			'singular_name'      => $singular_name,
			'menu_name'          => $plural_name,
			'name_admin_bar'     => $plural_name,
			'add_new'            => 'Aggiungi ' . $singular_name,
			'add_new_item'       => 'Aggiungi ' . $singular_name,
			'new_item'           => 'Nuovo ' . $singular_name,
			'edit_item'          => 'Modifica ' . $singular_name,
			'view_item'          => 'Vedi ' . $singular_name,
			'all_items'          => 'Tutti gli ' . $plural_name,
			'search_items'       => 'Cerca ' . $singular_name,
			'parent_item_colon'  => 'Parent ' . $plural_name . ':',
			'not_found'          => '0 ' . strtolower( $plural_name ) . ' trovati.',
			'not_found_in_trash' => '0 ' . strtolower( $plural_name ) . ' trovati nel cestino.',
		];

		$args = [
			'labels'             => $labels,
			'description'        => $singular_name,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'show_in_rest'       => true,
			'rewrite'            => [ 'slug' => 'spaces' ],
			'capability_type'    => 'post',
			'capabilities'       => array(
				'edit_post'            => 'edit_posts',
				'read_post'            => 'edit_posts',
				'delete_post'          => 'edit_posts',
				'edit_posts'           => 'edit_posts',
				'edit_published_posts' => 'edit_posts',
				'edit_others_posts'    => 'manage_options',
				'delete_posts'         => 'manage_options',
				'publish_posts'        => 'manage_options',
				'read_private_posts'   => 'edit_posts'
			),
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-location-alt',
			'supports'           => [ 'title', 'editor', 'excerpt', 'comments', 'thumbnail', 'author' ],
		];

		register_post_type( 'space', $args );
	}

	/**
	 * Register the testimonial post_type
	 */
	public function register_testimonial_post_type() {

		$singular_name = __( 'Testimonial' );
		$plural_name   = __( 'Testimonial' );
		$labels        = [
			'name'               => $singular_name,
			'singular_name'      => $singular_name,
			'menu_name'          => $plural_name,
			'name_admin_bar'     => $plural_name,
			'add_new'            => 'Aggiungi ' . $singular_name,
			'add_new_item'       => 'Aggiungi ' . $singular_name,
			'new_item'           => 'Nuovo ' . $singular_name,
			'edit_item'          => 'Modifica ' . $singular_name,
			'view_item'          => 'Vedi ' . $singular_name,
			'all_items'          => 'Tutti i ' . $plural_name,
			'search_items'       => 'Cerca ' . $singular_name,
			'parent_item_colon'  => 'Parent ' . $plural_name . ':',
			'not_found'          => '0 ' . strtolower( $plural_name ) . ' trovati.',
			'not_found_in_trash' => '0 ' . strtolower( $plural_name ) . ' trovati nel cestino.',
		];

		$args = [
			'labels'             => $labels,
			'description'        => $singular_name,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'show_in_rest'       => true,
			'rewrite'            => [ 'slug' => 'testimonial' ],
			'capability_type'    => 'post',
			'capabilities'       => array(
				'edit_post'          => 'manage_options',
				'read_post'          => 'manage_options',
				'delete_post'        => 'manage_options',
				'edit_posts'         => 'manage_options',
				'edit_others_posts'  => 'manage_options',
				'delete_posts'       => 'manage_options',
				'publish_posts'      => 'manage_options',
				'read_private_posts' => 'manage_options'
			),
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-businessman',
			'supports'           => [ 'title', 'editor', 'excerpt', 'thumbnail', 'author' ],
		];

		register_post_type( 'testimonial', $args );
	}

	/**
	 * Register the servizi post_type
	 */
	public function register_service_post_type() {

		$singular_name = __( 'Servizio' );
		$plural_name   = __( 'Servizi' );
		$labels        = [
			'name'               => $singular_name,
			'singular_name'      => $singular_name,
			'menu_name'          => $plural_name,
			'name_admin_bar'     => $plural_name,
			'add_new'            => 'Aggiungi ' . $singular_name,
			'add_new_item'       => 'Aggiungi ' . $singular_name,
			'new_item'           => 'Nuovo ' . $singular_name,
			'edit_item'          => 'Modifica ' . $singular_name,
			'view_item'          => 'Vedi ' . $singular_name,
			'all_items'          => 'Tutti i ' . $plural_name,
			'search_items'       => 'Cerca ' . $singular_name,
			'parent_item_colon'  => 'Parent ' . $plural_name . ':',
			'not_found'          => '0 ' . strtolower( $plural_name ) . ' trovati.',
			'not_found_in_trash' => '0 ' . strtolower( $plural_name ) . ' trovati nel cestino.',
		];

		$args = [
			'labels'             => $labels,
			'description'        => $singular_name,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'show_in_rest'       => true,
			'rewrite'            => [ 'slug' => 'service' ],
			'capability_type'    => 'post',
			'capabilities'       => array(
				'edit_post'          => 'manage_options',
				'read_post'          => 'manage_options',
				'delete_post'        => 'manage_options',
				'edit_posts'         => 'manage_options',
				'edit_others_posts'  => 'manage_options',
				'delete_posts'       => 'manage_options',
				'publish_posts'      => 'manage_options',
				'read_private_posts' => 'manage_options'
			),
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-megaphone',
			'supports'           => [ 'title', 'editor', 'excerpt', 'thumbnail', 'author' ],
		];

		register_post_type( 'service', $args );
	}

	/**
	 * Register the booking post_type
	 */
	public function register_booking_post_type() {

		$singular_name = __( 'Booking' );
		$plural_name   = __( 'Booking' );
		$labels        = [
			'name'               => $singular_name,
			'singular_name'      => $singular_name,
			'menu_name'          => $plural_name,
			'name_admin_bar'     => $plural_name,
			'add_new'            => 'Aggiungi ' . $singular_name,
			'add_new_item'       => 'Aggiungi ' . $singular_name,
			'new_item'           => 'Nuovo ' . $singular_name,
			'edit_item'          => 'Modifica ' . $singular_name,
			'view_item'          => 'Vedi ' . $singular_name,
			'all_items'          => 'Tutti i ' . $plural_name,
			'search_items'       => 'Cerca ' . $singular_name,
			'parent_item_colon'  => 'Parent ' . $plural_name . ':',
			'not_found'          => '0 ' . strtolower( $plural_name ) . ' trovati.',
			'not_found_in_trash' => '0 ' . strtolower( $plural_name ) . ' trovati nel cestino.',
		];

		$args = [
			'labels'             => $labels,
			'description'        => $singular_name,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'show_in_rest'       => false,
			'rewrite'            => [ 'slug' => 'bookings' ],
			'capability_type'    => 'post',
			'capabilities'       => array(
				'edit_post'            => 'edit_posts',
				'read_post'            => 'edit_posts',
				'delete_post'          => 'manage_options',
				'edit_posts'           => 'manage_options',
				'edit_published_posts' => 'manage_options',
				'edit_others_posts'    => 'manage_options',
				'delete_posts'         => 'manage_options',
				'publish_posts'        => 'manage_options',
				'read_private_posts'   => 'edit_posts'
			),
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-tickets-alt',
			'supports'           => [ 'title', 'editor', 'comments', 'author', 'custom-fields' ],
		];

		register_post_type( 'booking', $args );
	}

	/**
	 * Register the voucher post_type
	 */
	public function register_voucher_post_type() {

		$singular_name = __( 'Voucher' );
		$plural_name   = __( 'Voucher' );
		$labels        = [
			'name'               => $singular_name,
			'singular_name'      => $singular_name,
			'menu_name'          => $plural_name,
			'name_admin_bar'     => $plural_name,
			'add_new'            => 'Aggiungi ' . $singular_name,
			'add_new_item'       => 'Aggiungi ' . $singular_name,
			'new_item'           => 'Nuovo ' . $singular_name,
			'edit_item'          => 'Modifica ' . $singular_name,
			'view_item'          => 'Vedi ' . $singular_name,
			'all_items'          => 'Tutti i ' . $plural_name,
			'search_items'       => 'Cerca ' . $singular_name,
			'parent_item_colon'  => 'Parent ' . $plural_name . ':',
			'not_found'          => '0 ' . strtolower( $plural_name ) . ' trovati.',
			'not_found_in_trash' => '0 ' . strtolower( $plural_name ) . ' trovati nel cestino.',
		];

		$args = [
			'labels'             => $labels,
			'description'        => $singular_name,
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'show_in_rest'       => false,
			'rewrite'            => [ 'slug' => 'voucher' ],
			'capability_type'    => 'post',
			'capabilities'       => array(
				'edit_post'          => 'manage_options',
				'read_post'          => 'manage_options',
				'delete_post'        => 'manage_options',
				'edit_posts'         => 'manage_options',
				'edit_others_posts'  => 'manage_options',
				'delete_posts'       => 'manage_options',
				'publish_posts'      => 'manage_options',
				'read_private_posts' => 'manage_options'
			),
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-superhero-alt',
			'supports'           => [ 'title', 'author' ],
		];

		register_post_type( 'voucher', $args );
	}

	/**
	 * Register the hidden post to handle generic messages not linked to spaces
	 */
	public function register_hidden_post_type() {

		$singular_name = __( 'Post nascosto' );
		$plural_name   = __( 'Post nascosti' );
		$labels        = [
			'name'               => $singular_name,
			'singular_name'      => $singular_name,
			'menu_name'          => $plural_name,
			'name_admin_bar'     => $plural_name,
			'add_new'            => 'Aggiungi ' . $singular_name,
			'add_new_item'       => 'Aggiungi ' . $singular_name,
			'new_item'           => 'Nuovo ' . $singular_name,
			'edit_item'          => 'Modifica ' . $singular_name,
			'view_item'          => 'Vedi ' . $singular_name,
			'all_items'          => 'Tutti gli ' . $plural_name,
			'search_items'       => 'Cerca ' . $singular_name,
			'parent_item_colon'  => 'Parent ' . $plural_name . ':',
			'not_found'          => '0 ' . strtolower( $plural_name ) . ' trovati.',
			'not_found_in_trash' => '0 ' . strtolower( $plural_name ) . ' trovati nel cestino.',
		];

		$args = [
			'labels'             => $labels,
			'description'        => $singular_name,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'show_in_rest'       => false,
			'rewrite'            => [ 'slug' => 'hidden_posts' ],
			'capability_type'    => 'post',
			'capabilities'       => array(
				'edit_post'            => 'manage_options',
				'read_post'            => 'manage_options',
				'delete_post'          => 'manage_options',
				'edit_posts'           => 'manage_options',
				'edit_published_posts' => 'manage_options',
				'edit_others_posts'    => 'manage_options',
				'delete_posts'         => 'manage_options',
				'publish_posts'        => 'manage_options',
				'read_private_posts'   => 'edit_posts'
			),
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-hidden',
			'supports'           => [ 'title', 'comments', 'author' ],
		];

		register_post_type( 'hidden_post', $args );
	}
}

new Post_Types();

<?php

namespace Mapparte;

/**
 * Class Taxonomies
 *
 * @package Mapparte
 */
class Taxonomies{

	public function __construct() {
		add_action( 'init', [ $this, 'register_taxonomies' ] );
	}


	/**
	 * Register the custom taxonomies
	 */
	public function register_taxonomies() {

		$labels = array(
			'name' => _x( 'Typologies', 'taxonomy general name' ),
			'singular_name' => _x( 'Typologies', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Typologies' ),
			'popular_items' => __( 'Popular Typologies' ),
			'all_items' => __( 'All Typologies' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Edit Typology' ),
			'update_item' => __( 'Update Typology' ),
			'add_new_item' => __( 'Add New Typology' ),
			'new_item_name' => __( 'New Typology Name' ),
			'separate_items_with_commas' => __( 'Separate typologies with commas' ),
			'add_or_remove_items' => __( 'Add or remove typologies' ),
			'choose_from_most_used' => __( 'Choose from the most used typologies' ),
			'menu_name' => __( 'Typologies' ),
		);

		register_taxonomy('typology','space',array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_in_rest' => true,
			'show_admin_column' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array( 'slug' => 'typology' ),
		));

		$labels = array(
			'name' => _x( 'Activities', 'taxonomy general name' ),
			'singular_name' => _x( 'Activity', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Activities' ),
			'popular_items' => __( 'Popular Activities' ),
			'all_items' => __( 'All Activities' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Edit Activity' ),
			'update_item' => __( 'Update Activity' ),
			'add_new_item' => __( 'Add New Activity' ),
			'new_item_name' => __( 'New Activity Name' ),
			'separate_items_with_commas' => __( 'Separate activities with commas' ),
			'add_or_remove_items' => __( 'Add or remove activities' ),
			'choose_from_most_used' => __( 'Choose from the most used activities' ),
			'menu_name' => __( 'Activities' ),
		);

		register_taxonomy('activity','space',array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_in_rest' => true,
			'show_admin_column' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array( 'slug' => 'activity' ),
		));

	}

}

new Taxonomies();

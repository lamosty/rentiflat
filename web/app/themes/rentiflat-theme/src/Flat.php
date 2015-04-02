<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

class Flat {

	public function init() {
		$this->register_flat_post_type();
	}

	private function register_flat_post_type() {
		$labels = [
			'name'               => _x( 'Flats', 'post type general name', RentiFlat::TEXT_DOMAIN ),
			'singular_name'      => _x( 'Flat', 'post type singular name', RentiFlat::TEXT_DOMAIN ),
			'menu_name'          => _x( 'Flats', 'admin menu', RentiFlat::TEXT_DOMAIN ),
			'name_admin_bar'     => _x( 'Flat', 'add new on admin bar', RentiFlat::TEXT_DOMAIN ),
			'add_new'            => _x( 'Add New', 'book', RentiFlat::TEXT_DOMAIN ),
			'add_new_item'       => __( 'Add New Flat', RentiFlat::TEXT_DOMAIN ),
			'new_item'           => __( 'New Flat', RentiFlat::TEXT_DOMAIN ),
			'edit_item'          => __( 'Edit Flat', RentiFlat::TEXT_DOMAIN ),
			'view_item'          => __( 'View Flat', RentiFlat::TEXT_DOMAIN ),
			'all_items'          => __( 'All Flats', RentiFlat::TEXT_DOMAIN ),
			'search_items'       => __( 'Search Flats', RentiFlat::TEXT_DOMAIN ),
			'not_found'          => __( 'No flats found.', RentiFlat::TEXT_DOMAIN ),
			'not_found_in_trash' => __( 'No flats found in Trash.', RentiFlat::TEXT_DOMAIN )
		];

		$flat_post_type_args = [
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'flat' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			'delete_with_user' => true
		];

		register_post_type( 'flat', $flat_post_type_args );
	}

}
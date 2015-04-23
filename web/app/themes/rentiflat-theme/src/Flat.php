<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

class Flat {

	public static $post_type_id = 'rentiflat_flat';
	public static $bids_meta_box_id = 'rentiflat_bids_meta_box';

	public function init() {
		$this->add_wp_actions();

		$this->register_flat_post_type();
	}

	private function add_wp_actions() {

		// Add bids meta box on flat offer admin edit screen
		add_action( 'add_meta_boxes_' . self::$post_type_id, function () {

			add_meta_box(
				self::$bids_meta_box_id,
				_x( 'Bids', 'bids meta box', RentiFlat::TEXT_DOMAIN ),
				[ $this, 'add_bids_meta_box' ],
				null,
				'advanced',
				'core'
			);

		} );
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
			'capability_type'    => [ 'rentiflat_flat', 'rentiflat_flats' ],
			'map_meta_cap'       => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail'),
			'delete_with_user'   => true
		];

		register_post_type( self::$post_type_id, $flat_post_type_args );
	}

	public function add_bids_meta_box( $post ) {

	}

}
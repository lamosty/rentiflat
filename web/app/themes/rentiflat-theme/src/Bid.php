<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

class Bid {

	public static $post_type_id = 'rentiflat_bid';

	public function init() {
		$this->add_wp_actions();

		$this->register_bid_post_type();
	}

	private function add_wp_actions() {
	}

	private function register_bid_post_type() {
		$labels = [
			'name'               => _x( 'Bids', 'rentiflat bid general name', RentiFlat::TEXT_DOMAIN ),
			'singular_name'      => _x( 'Bid', 'rentiflat bid singular name', RentiFlat::TEXT_DOMAIN ),
			'menu_name'          => _x( 'Bids', 'admin menu', RentiFlat::TEXT_DOMAIN ),
			'name_admin_bar'     => _x( 'Bid', 'add new on admin bar', RentiFlat::TEXT_DOMAIN ),
			'add_new'            => _x( 'Add New', 'rentiflat bid', RentiFlat::TEXT_DOMAIN ),
			'add_new_item'       => __( 'Add New Bid', RentiFlat::TEXT_DOMAIN ),
			'new_item'           => __( 'New Bid', RentiFlat::TEXT_DOMAIN ),
			'edit_item'          => __( 'Edit Bid', RentiFlat::TEXT_DOMAIN ),
			'view_item'          => __( 'View Bid', RentiFlat::TEXT_DOMAIN ),
			'all_items'          => __( 'All Bids', RentiFlat::TEXT_DOMAIN ),
			'search_items'       => __( 'Search Bids', RentiFlat::TEXT_DOMAIN ),
			'not_found'          => __( 'No bids found.', RentiFlat::TEXT_DOMAIN ),
			'not_found_in_trash' => __( 'No bids found in Trash.', RentiFlat::TEXT_DOMAIN )
		];

		$flat_post_type_args = [
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => false,
			'capability_type'    => [ 'rentiflat_bid', 'rentiflat_bids' ],
			'map_meta_cap'       => true,
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			'delete_with_user'   => true
		];

		register_post_type( self::$post_type_id, $flat_post_type_args );
	}

}
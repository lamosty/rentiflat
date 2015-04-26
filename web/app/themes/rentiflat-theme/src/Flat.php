<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

use Lamosty\RentiFlat\Utils\Admin_Helper;

class Flat {

	public static $post_type_id = 'rentiflat_flat';
	public static $bids_meta_box_id = 'rentiflat_bids_meta_box';

	public function init() {
		$this->add_wp_actions();
		$this->add_wp_filters();

	}

	private function add_wp_actions() {
		add_action( 'init', [ $this, 'register_flat_post_type' ] );
		add_action( 'init', [ $this, 'register_num_of_rooms_taxonomy' ] );


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

	private function add_wp_filters() {
		add_filter( 'wp_editor_settings', [ $this, 'modify_wp_editor_settings' ] );
		add_filter( 'mce_buttons', [ $this, 'modify_tinymce_buttons' ] );
	}

	public function register_flat_post_type() {
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
			'supports'           => array( 'title', 'editor', 'thumbnail' ),
			'delete_with_user'   => true
		];

		register_post_type( self::$post_type_id, $flat_post_type_args );
	}

	public function register_num_of_rooms_taxonomy() {

	}

	public function add_bids_meta_box( $post ) {
		$bids_query = new \WP_Query( [
			'post_type'   => Bid::$post_type_id,
			'post_parent' => $post->ID

		] );

		$bids = $bids_query->posts;

		var_dump( $bids );
	}

	public function modify_tinymce_buttons( $buttons ) {
		if ( Admin_Helper::is_screen( 'post', self::$post_type_id ) ) {
			$new_buttons = [
				'bold',
				'italic',
				'bullist',
				'numlist'
			];

			return $new_buttons;
		}

		return $buttons;
	}

	public function modify_wp_editor_settings( $settings ) {
		if ( Admin_Helper::is_screen( 'post', self::$post_type_id ) ) {
			$new_settings = [
				'textarea_rows' => 8,
				'tinymce'       => [
					'wp_autoresize_on' => false,
					'resize'           => false,
					'statusbar'        => false
				],
				'editor_height' => '',
				'quicktags'     => [
					'buttons' => 'strong,em,ul,ol,li'
				]
			];

			$settings = array_merge( $settings, $new_settings );
		}

		return $settings;
	}

}
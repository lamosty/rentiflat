<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

use Lamosty\RentiFlat\Utils\Admin_Helper;

class Flat {

	public static $post_type_id = 'rentiflat_flat';
	public static $flat_types_taxonomy_id = 'rentiflat_flat_types';
	public static $bids_meta_box_id = 'rentiflat_bids_meta_box';

	public static $country_taxonomy_id = 'rentiflat_country';
	public static $city_taxonomy_id = 'rentiflat_city';


	public function init() {
		$this->add_wp_actions();
		$this->add_wp_filters();

	}

	private function add_wp_actions() {
		add_action( 'init', [ $this, '_wp_init' ] );
		add_action( 'after_switch_theme', [ $this, 'insert_terms' ] );
		add_action( 'add_meta_boxes_' . self::$post_type_id, [ $this, 'add_flat_meta_data' ] );
	}

	private function add_wp_filters() {
		add_filter( 'wp_editor_settings', [ $this, 'modify_wp_editor_settings' ] );
		add_filter( 'mce_buttons', [ $this, 'modify_tinymce_buttons' ] );
	}

	public function _wp_init() {
		$this->register_flat_post_type();
		$this->register_flat_types_taxonomy();
		$this->register_location_taxonomies();


	}

	public function insert_terms() {
		$flat_type_terms = [
			[
				'title'    => 'Studio',
				'taxonomy' => self::$flat_types_taxonomy_id,
				'slug'     => 'studio'
			],
			[
				'title'    => '1 room',
				'taxonomy' => self::$flat_types_taxonomy_id,
				'slug'     => '1-room'
			],
			[
				'title'    => '2 rooms',
				'taxonomy' => self::$flat_types_taxonomy_id,
				'slug'     => '2-rooms'
			],
			[
				'title'    => '3 rooms',
				'taxonomy' => self::$flat_types_taxonomy_id,
				'slug'     => '3-rooms'
			],
			[
				'title'    => '4 rooms',
				'taxonomy' => self::$flat_types_taxonomy_id,
				'slug'     => '4-rooms'
			]
		];

		$terms = $flat_type_terms;

		foreach ( $terms as $term ) {
			wp_insert_term(
				$term['title'],
				$term['taxonomy'],
				[
					'slug' => $term['slug']
				]
			);
		}

	}

	public function add_flat_meta_data( \WP_Post $flat ) {
		$flat_meta_data = [
			'num_of_persons',
			'area_m_squared',
			'new_building',
			'price_per_month',
			'elevator',
			'balcony',
			'cellar',
			'floor_num'
		];

		foreach ( $flat_meta_data as $meta ) {
			add_post_meta( $flat->ID, $meta, '', true );
		}
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
			'rewrite'            => [ 'slug' => 'flat' ],
			'capability_type'    => [ 'rentiflat_flat', 'rentiflat_flats' ],
			'map_meta_cap'       => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
			'delete_with_user'   => true,
			'taxonomies'         => [
				self::$flat_types_taxonomy_id,
				self::$country_taxonomy_id,
				self::$city_taxonomy_id
			]
		];

		register_post_type( self::$post_type_id, $flat_post_type_args );
	}

	private function register_flat_types_taxonomy() {
		$labels = [
			'name'         => _x( 'Type', 'taxonomy general name' ),
			'search_items' => __( 'Filter by flat types' ),
			'all_items'    => __( 'All flat types' ),
			'edit_item'    => __( 'Edit flat type' ),
			'update_item'  => __( 'Update flat type' ),
			'add_new_item' => __( 'Add new flat type' ),
			'menu_name'    => __( 'Types' )
		];

		$args = [
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'bedrooms' ),
		];

		register_taxonomy( self::$flat_types_taxonomy_id, self::$post_type_id, $args );
	}

	private function register_location_taxonomies() {
		// Country
		register_taxonomy( self::$country_taxonomy_id, self::$post_type_id, [
			'labels'            => [
				'name'      => _x( 'Country', 'taxonomy general name' ),
				'menu_name' => __( 'Countries' )
			],
			'query_var'         => false,
			'show_ui'           => true,
			'show_in_menu'      => false,
			'show_admin_column' => true
		] );

		// City
		register_taxonomy( self::$city_taxonomy_id, self::$post_type_id, [
			'labels'            => [
				'name'      => _x( 'City', 'taxonomy general name' ),
				'menu_name' => __( 'Cities' )
			],
			'query_var'         => false,
			'show_ui'           => true,
			'show_in_menu'      => false,
			'show_admin_column' => true
		] );
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

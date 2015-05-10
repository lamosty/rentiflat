<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

use Lamosty\RentiFlat\Utils\Admin_Helper;
use \Ivory\HttpAdapter\CurlHttpAdapter;
use \Geocoder\Provider\GoogleMaps;

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

		add_action( 'rentiflat_flat_page', [ $this, 'add_flat_page_js_variables' ], 10, 2 );
		add_action( 'rentiflat_flat_page', [ $this, 'flat_bids_to_js' ], 10, 2 );
		add_action( 'rentiflat_flat_page', [ $this, 'enqueue_gmaps_js_api' ] );

		add_action( 'publish_' . self::$post_type_id, [ $this, 'address_to_latlng' ] );
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
			'floor_num',
			'street_name'
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
			'capabilities'      => [
				'assign_terms' => 'edit_rentiflat_flats'
			]
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
			'show_admin_column' => true,
			'capabilities'      => [
				'assign_terms' => 'edit_rentiflat_flats'
			]
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
			'show_admin_column' => true,
			'capabilities'      => [
				'assign_terms' => 'edit_rentiflat_flats'
			]
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

	public function add_flat_page_js_variables( $flat_page_id, $flat_owner ) {
		$user = wp_get_current_user();

		// Does the user have any previous bid on this flat?
		$user_bids_query = new \WP_Query( [
			'post_parent' => $flat_page_id,
			'post_type'   => Bid::$post_type_id,
			'author'      => $user->ID
		] );

		$user_bid_id   = null;
		$bid_admin_url = '';

		if ( $user_bids_query->have_posts() ) {
			$user_bid_id   = $user_bids_query->posts[0]->ID;
			$bid_admin_url = admin_url( 'post.php?post=' . $user_bid_id . '&action=edit' );
		}

		wp_localize_script( 'rentiflat-main-js', 'RentiFlatTenantData', [
			'tenant_bid_id'          => $user_bid_id,
			'tenant_fullname'        => User::get_full_name( $user ),
			'tenant_profile_picture' => User::get_profile_picture( $user ),
			'tenant_email'           => $user->user_email,
			'tenant_fb_url'          => User::get_fb_url( $user ),
			'flat_owner_name'        => User::get_full_name( $flat_owner ),
			'flat_price_per_month'   => get_post_meta( $flat_page_id, 'price_per_month', true ),
			'flat_page_id'           => $flat_page_id,
			'flat_page_title'        => get_the_title( $flat_page_id ),
			'api_url'                => esc_url_raw( get_json_url() ),
			'nonce'                  => wp_create_nonce( 'wp_json' ),
			'bid_admin_url'          => $bid_admin_url
		] );

	}

	/**
	 * Collect bids for a specific flat page.
	 *
	 * TODO: load it asynchronously
	 *
	 * @param integer $flat_page_id ID of the flat page (post)
	 */
	public function flat_bids_to_js( $flat_page_id, $flat_owner ) {
		$bids_from_db = get_posts( [
			'post_parent' => $flat_page_id,
			'post_type'   => Bid::$post_type_id
		] );

		$bids = [ ];

		foreach ( $bids_from_db as $bid ) {
			$bid_author = get_userdata( $bid->post_author );

			$candidate_name   = $bid_author->user_firstname;
			$candidate_email  = 'hidden';
			$candidate_fb_url = 'hidden';

			// Flat or bid owners viewing their flat offer. Show all bids info.
			if ( ( get_current_user_id() == $flat_owner->ID ) || ( get_current_user_id() == $bid_author->ID ) ) {
				$candidate_name   = User::get_full_name( $bid_author );
				$candidate_email  = $bid->tenant_email;
				$candidate_fb_url = User::get_fb_url( $bid_author );
			}

			$bids[] = [
				'candidate_name'    => $candidate_name,
				'candidate_email'   => $candidate_email,
				'candidate_picture' => User::get_profile_picture( $bid_author ),
				'candidate_fb_url'  => $candidate_fb_url,
				'date'              => $bid->post_date_gmt,
				'price_per_month'   => $bid->flat_price_per_month
			];
		}

		wp_localize_script( 'rentiflat-main-js', 'RentiFlatBids', $bids );
	}

	public function enqueue_gmaps_js_api() {
		wp_enqueue_script( 'rentiflat-gmaps-js-api' );
	}

	public static function get_flat_address( $flat_page_id ) {
		$country = get_the_terms( $flat_page_id, self::$country_taxonomy_id )[0]->name;
		$city    = get_the_terms( $flat_page_id, self::$city_taxonomy_id )[0]->name;
		$street  = get_post_meta( $flat_page_id, 'street_name', true );

		return $street . ', ' . $city . ', ' . $country;
	}

	public function address_to_latlng( $flat_page_id ) {
		$flat_address_in_string = self::get_flat_address( $flat_page_id );

		$curl = new CurlHttpAdapter();

		if ( WP_ENV == 'production' ) {
			$geocoder = new GoogleMaps( $curl, null, null, true, Theme::GMAPS_JS_API_KEY );
		} else {
			$geocoder = new GoogleMaps( $curl );
		}

		$result           = $geocoder->geocode( $flat_address_in_string );
		$geocoded_address = $result->first();

		$previous_lat = get_post_meta( $flat_page_id, 'lat', true );

		if ( empty( $previous_lat ) ) {
			add_post_meta( $flat_page_id, 'lat', $geocoded_address->getLatitude(), true );
			add_post_meta( $flat_page_id, 'lng', $geocoded_address->getLongitude(), true );
		} else {
			update_post_meta( $flat_page_id, 'lat', $geocoded_address->getLatitude() );
			update_post_meta( $flat_page_id, 'lng', $geocoded_address->getLongitude() );
		}
	}
}

<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

use Lamosty\RentiFlat\Utils\Template_Helper;

final class Theme {
	/**
	 * Name of the theme (prefix).
	 *
	 * @const NAME
	 */
	const THEME_NAME = 'rentiflat';

	const FLAT_THUMBNAIL_SIZE = 'rentiflat_flat_thumbnail';
	const FLAT_FIND_FLATS_SIZE = 'rentiflat_find_flats';

	private $gmaps_js_api_key;

	private $theme_options = [ ];

	public function init() {
		$this->gmaps_js_api_key = getenv('GMAPS_JS_API_KEY');

		$this->add_wp_actions();
		$this->add_wp_filters();
	}

	private function add_wp_actions() {
		add_action( 'after_switch_theme', [ $this, 'after_switch_theme' ] );
		add_action( 'after_setup_theme', [ $this, '_wp_after_setup_theme' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
	}

	public function _wp_after_setup_theme() {
		$this->setup_theme();
		$this->add_flat_photos_sizes();
	}

	private function add_wp_filters() {
		add_filter( 'template_include', __NAMESPACE__ . '\Utils\Theme_Wrapper::wrap', 99 );

	}

	/**
	 * Fired after theme is activated
	 */
	public function after_switch_theme() {
		// To add rewrite rules for flats and other pages
		flush_rewrite_rules();
	}

	private function add_flat_photos_sizes() {
		// Flat featured photo (the large one on flat page)
		set_post_thumbnail_size( 690, 460 );

		// Thumbnail photo
		add_image_size( self::FLAT_THUMBNAIL_SIZE, 128, 80 );

		// Find Flats thumbnail size
		add_image_size( self::FLAT_FIND_FLATS_SIZE, 195, 130, true );
	}

	private function setup_theme() {
		register_nav_menus( [
			'primary_navigation' => __( 'Primary Navigation', RentiFlat::TEXT_DOMAIN )
		] );

		add_theme_support( 'post-thumbnails' );
	}

	public function enqueue_scripts() {
		// Deregister WordPress jQuery and register our own
		wp_deregister_script( 'jquery' );

		wp_register_script( 'rentiflat-libraries-js',
			Template_Helper::asset_path( 'scripts/libraries.js' ), [ ], null, true );

		$gmaps_js_api_url = 'https://maps.googleapis.com/maps/api/js?sensor=false';

		if ( WP_ENV == 'production' ) {
			$gmaps_js_api_url .= '&key=' . $this->gmaps_js_api_key;
		}
		wp_register_script( 'rentiflat-gmaps-js-api', $gmaps_js_api_url, [ ], null, true );

		wp_enqueue_script( 'rentiflat-main-js', Template_Helper::asset_path( 'scripts/main.js' ),
			[ 'rentiflat-libraries-js' ], null, true );
	}

	public function enqueue_styles() {
		wp_register_style( 'rentiflat-libraries-css', Template_Helper::asset_path( 'styles/libraries.css' ) );


		wp_enqueue_style( 'rentiflat-main-css', Template_Helper::asset_path( 'styles/main.css' ),
			[ 'rentiflat-libraries-css' ], null );
	}

}
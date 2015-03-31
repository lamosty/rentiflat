<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

use Lamosty\RentiFlat\Utils;

final class RentiFlat {
	const VERSION = '1.0.0';
	const TEXT_DOMAIN = 'rentiflat-theme';
	const DIST_DIR = '/dist/';

	public function init() {
		$this->add_wp_actions();

		$this->add_wp_filters();
	}

	private function add_wp_actions() {
		add_action( 'after_setup_theme', array( $this, 'setup_theme' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	private function add_wp_filters() {
		add_filter('template_include', __NAMESPACE__ . '\Utils\Theme_Wrapper::wrap', 99);
	}

	public function setup_theme() {
		register_nav_menus( [
			'primary_navigation' => __( 'Primary Navigation', self::TEXT_DOMAIN )
		] );

		add_theme_support( 'post-thumbnails' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script('modernizr', asset_path('scripts/modernizr.js'), [], null, true);
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'rentiflat-main-js', asset_path( 'scripts/main.js' ),
			[ ], null, true
		);
	}

	public function enqueue_styles() {
		wp_enqueue_style( 'rentiflat-main-css', asset_path( 'styles/main.css' ),
			[ ], null );
	}
}
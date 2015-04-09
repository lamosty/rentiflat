<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

use Lamosty\RentiFlat\Utils;
use Lamosty\RentiFlat\Utils\Template_Helper;

final class RentiFlat {
	const VERSION = '1.0.0';
	const TEXT_DOMAIN = 'rentiflat-theme';
	const DIST_DIR = '/dist/';


	/** @var Theme $theme */
	public $theme;

	/** @var Flat $flat */
	public $flat;

	/**
	 * IOC dependencies
	 */
	public function needs() {
		return [
			'theme',
			'flat'
		];
	}

	public function init() {
		$this->theme->init();
		$this->flat->init();

		$this->add_wp_actions();
		$this->add_wp_filters();

	}

	private function add_wp_actions() {

		add_action( 'after_setup_theme', [ $this, 'setup_theme' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );

	}

	private function add_wp_filters() {
		add_filter( 'template_include', __NAMESPACE__ . '\Utils\Theme_Wrapper::wrap', 99 );

	}

	public function setup_theme() {
		register_nav_menus( [
			'primary_navigation' => __( 'Primary Navigation', self::TEXT_DOMAIN )
		] );

		add_theme_support( 'post-thumbnails' );
	}

	public function enqueue_scripts() {
		// Deregister WordPress jQuery and register our own
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', Template_Helper::asset_path( 'scripts/jquery.js' ), [ ], null, true );

		wp_register_script( 'modernizr', Template_Helper::asset_path( 'scripts/modernizr.js' ), [ ], null, true );

		wp_enqueue_script( 'rentiflat-main-js', Template_Helper::asset_path( 'scripts/main.js' ),
			[
				'modernizr',
				'jquery'
			],
			null, true
		);
	}

	public function enqueue_styles() {
		wp_enqueue_style( 'rentiflat-main-css', Template_Helper::asset_path( 'styles/main.css' ),
			[ ], null );
	}
}
<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

final class Theme {
	/**
	 * Name of the theme (prefix).
	 *
	 * @const NAME
	 */
	const NAME = 'rentiflat';

	private $theme_options = [ ];

	public function init() {
		$this->add_wp_actions();
	}

	private function add_wp_actions() {
		add_action( 'after_switch_theme', [ $this, 'after_switch_theme' ] );

	}

	/**
	 * Fired after theme is activated
	 */
	public function after_switch_theme() {
	}

}
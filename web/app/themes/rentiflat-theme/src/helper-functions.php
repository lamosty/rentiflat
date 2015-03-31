<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat\Helper_Functions;

use Lamosty\RentiFlat\RentiFlat;

/**
 * Theme wrapper
 *
 * @link https://roots.io/sage/docs/theme-wrapper/
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */

class Theme_Wrapper {
	// Stores the full path to the main template file
	public static $main_template;

	// Basename of template file
	public $slug;

	// Array of templates
	public $templates;

	// Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
	public static $base;

	public function __construct( $template = 'base.php' ) {
		$this->slug      = basename( $template, '.php' );
		$this->templates = [ $template ];
		if ( self::$base ) {
			$str = substr( $template, 0, - 4 );
			array_unshift( $this->templates, sprintf( $str . '-%s.php', self::$base ) );
		}
	}

	public function __toString() {
		$this->templates = apply_filters( 'sage/wrap_' . $this->slug, $this->templates );

		return locate_template( $this->templates );
	}

	public static function wrap( $main ) {
		// Check for other filters returning null
		if ( ! is_string( $main ) ) {
			return $main;
		}

		self::$main_template = $main;
		self::$base          = basename( self::$main_template, '.php' );

		if ( self::$base === 'index' ) {
			self::$base = false;
		}

		return new Theme_Wrapper();
	}
}

class JsonManifest {
	private $manifest;

	public function __construct($manifest_path) {
		if (file_exists($manifest_path)) {
			$this->manifest = json_decode(file_get_contents($manifest_path), true);
		} else {
			$this->manifest = [];
		}
	}

	public function get() {
		return $this->manifest;
	}

	public function getPath($key = '', $default = null) {
		$collection = $this->manifest;
		if (is_null($key)) {
			return $collection;
		}
		if (isset($collection[$key])) {
			return $collection[$key];
		}
		foreach (explode('.', $key) as $segment) {
			if (!isset($collection[$segment])) {
				return $default;
			} else {
				$collection = $collection[$segment];
			}
		}
		return $collection;
	}
}

function template_path() {
	return Theme_Wrapper::$main_template;
}

function asset_path($filename) {
	$dist_path = get_template_directory_uri() . RentiFlat::DIST_DIR;
	$directory = dirname($filename) . '/';
	$file = basename($filename);
	static $manifest;

	if (empty($manifest)) {
		$manifest_path = get_template_directory() . RentiFlat::DIST_DIR . 'assets.json';
		$manifest = new JsonManifest($manifest_path);
	}

	if (WP_ENV !== 'development' && array_key_exists($file, $manifest->get())) {
		return $dist_path . $directory . $manifest->get()[$file];
	} else {
		return $dist_path . $directory . $file;
	}
}
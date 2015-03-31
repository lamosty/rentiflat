<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat\Utils;

use Lamosty\RentiFlat\RentiFlat;

class JSON_Manifest {
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

function asset_path($filename) {
	$dist_path = get_template_directory_uri() . RentiFlat::DIST_DIR;
	$directory = dirname($filename) . '/';
	$file = basename($filename);
	static $manifest;

	if (empty($manifest)) {
		$manifest_path = get_template_directory() . RentiFlat::DIST_DIR . 'assets.json';
		$manifest = new JSON_Manifest($manifest_path);
	}

	if (WP_ENV !== 'development' && array_key_exists($file, $manifest->get())) {
		return $dist_path . $directory . $manifest->get()[$file];
	} else {
		return $dist_path . $directory . $file;
	}
}


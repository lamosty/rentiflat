<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

use Lamosty\RentiFlat\Utils;

function asset_path( $filename ) {
	$dist_path = get_template_directory_uri() . RentiFlat::DIST_DIR;
	$directory = dirname( $filename ) . '/';
	$file      = basename( $filename );
	static $manifest;

	if ( empty( $manifest ) ) {
		$manifest_path = get_template_directory() . RentiFlat::DIST_DIR . 'assets.json';
		$manifest      = new Utils\JSON_Manifest( $manifest_path );
	}

	if ( WP_ENV !== 'development' && array_key_exists( $file, $manifest->get() ) ) {
		return $dist_path . $directory . $manifest->get()[ $file ];
	} else {
		return $dist_path . $directory . $file;
	}
}


function template_path() {
	return Utils\Theme_Wrapper::$main_template;
}
<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat\Utils;

use Lamosty\RentiFlat\RentiFlat;


final class Template_Helper {

	public static function asset_path( $filename ) {
		$dist_path = get_template_directory_uri() . RentiFlat::DIST_DIR;
		$directory = dirname( $filename ) . '/';
		$file      = basename( $filename );
		static $manifest;

		if ( empty( $manifest ) ) {
			$manifest_path = get_template_directory() . RentiFlat::DIST_DIR . 'assets.json';
			$manifest      = new JSON_Manifest( $manifest_path );
		}

		if ( WP_ENV !== 'development' && array_key_exists( $file, $manifest->get() ) ) {
			return $dist_path . $directory . $manifest->get()[ $file ];
		} else {
			return $dist_path . $directory . $file;
		}
	}


	public static function template_path() {
		return Theme_Wrapper::$main_template;
	}

	public static function nav_set_active( $route, $class = 'active' ) {
		global $wp;

		$request = $wp->request;
		$route   = trim( $route, '/' );

		if ( $request == $route ||
		     strpos( $request, $route . '/' ) === 0 // subpage, such as /page/2/
		) {
			return $class;
		}

		return '';
	}

	public static function flat_has_feature( $flat_id, $feature_name ) {
		$feature = get_post_meta($flat_id, $feature_name, true);

		if ($feature == '1') {
			return true;
		} else {
			return false;
		}
	}

}


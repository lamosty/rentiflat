<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat\Utils;

class Admin_Helper {

	public static function is_screen( $base, $post_type = '' ) {
		$screen = get_current_screen();

		if ($screen->base == $base && $screen->post_type == $post_type) {
			return true;
		}

		return false;
	}
}
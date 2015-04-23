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


	/** @var Theme $theme */
	public $theme;

	/** @var Flat $flat */
	public $flat;

	/** @var Bid $bid */
	public $bid;

	/** @var User $user */
	public $user;

	/**
	 * IOC dependencies
	 */
	public function needs() {
		return [
			'theme',
			'flat',
			'bid',
			'user'
		];
	}

	public function init() {
		$this->theme->init();
		$this->flat->init();
		$this->bid->init();
		$this->user->init();
	}
}
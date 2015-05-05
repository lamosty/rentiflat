<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookSession;

class FB_Auth {
	private $app_id = '741717739281848';
	private $app_secret = '35209c6efe542f78bc00839ee7c47dfa';

	public function init() {
		$this->set_default_application();

		$this->add_wp_actions();
	}

	private function set_default_application() {
		if ( ! session_id() ) {
			session_start();
		}

		FacebookSession::setDefaultApplication( $this->app_id, $this->app_secret );
	}

	private function add_wp_actions() {

	}

	public function get_fb_login_url() {
		$helper = new FacebookRedirectLoginHelper( get_site_url() . '/wp-login.php?action=register' );

		return $helper->getLoginUrl();
	}

}
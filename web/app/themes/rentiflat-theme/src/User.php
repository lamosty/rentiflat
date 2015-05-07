<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

class User {

	private $user_role = 'rentiflat_user';

	private $user_capabilities = [

		// RentiFlat Flat post type
		'publish_rentiflat_flats',
		'edit_rentiflat_flats',
		'edit_published_rentiflat_flats',
		'delete_rentiflat_flats',
		'delete_published_rentiflat_flats',
		'read_private_rentiflat_flats',
		// RentiFlat Bid post type
		'publish_rentiflat_bids',
		'edit_rentiflat_bids',
		'edit_published_rentiflat_bids',
		'delete_rentiflat_bids',
		'delete_published_rentiflat_bids',
		'read_private_rentiflat_bids',
		// common
		'read',
		'upload_files'
	];

	public function init() {
		$this->add_wp_actions();
		$this->add_wp_filters();
	}

	private function add_wp_actions() {
		add_action( 'after_switch_theme', [ $this, 'add_user_role' ] );
		add_action( 'after_switch_theme', [ $this, 'add_capabilities_to_user' ] );
		add_action( 'after_switch_theme', [ $this, 'add_capabilities_to_admin' ] );
	}

	private function add_wp_filters() {
		add_filter( 'show_admin_bar', [ $this, 'handle_admin_bar' ] );
	}

	public function add_user_role() {
		add_role( $this->user_role, __( 'RentiFlat User' ) );
	}

	public function add_capabilities_to_user() {
		$role = get_role( $this->user_role );

		foreach ( $this->user_capabilities as $cap ) {
			$role->add_cap( $cap );
		}
	}

	public function add_capabilities_to_admin() {
		$role = get_role( 'administrator' );

		$capabilities = array_merge( $this->user_capabilities, [
			'delete_others_rentiflat_flats',
			'edit_others_rentiflat_flats'
		] );

		foreach ( $capabilities as $cap ) {
			$role->add_cap( $cap );
		}
	}

	public function handle_admin_bar() {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		return false;
	}

	public static function get_profile_picture(\WP_User $user) {
		$uploads_dir = wp_upload_dir()['baseurl'];

		return $uploads_dir . '/' . $user->rentiflat_fb_picture;
	}

	public static function get_full_name(\WP_User $user) {
		return $user->user_firstname . ' ' . $user->user_lastname;
	}

	public static function get_fb_url(\WP_User $user) {
		return $user->rentiflat_fb_url;
	}
}
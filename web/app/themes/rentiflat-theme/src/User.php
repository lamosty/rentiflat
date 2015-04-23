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
	}

	private function add_wp_actions() {
		add_action( 'after_switch_theme', [ $this, 'add_user_role' ] );
		add_action( 'after_switch_theme', [ $this, 'add_capabilities_to_user' ] );
		add_action( 'after_switch_theme', [ $this, 'add_capabilities_to_admin' ] );
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


}
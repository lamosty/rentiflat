<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

use Encase\Container;

function initialize_IOC() {
	$container = new Container();

	$container
		->object( 'RentiFlat', function () {
			return new RentiFlat();
		} )
		->singleton( 'flat', __NAMESPACE__ . '\Flat' )
		->singleton( 'bid', __NAMESPACE__ . '\Bid' )
		->singleton( 'theme', __NAMESPACE__ . '\Theme' )
		->singleton( 'user', __NAMESPACE__ . '\User' )
		->singleton( 'fb_auth', __NAMESPACE__ . '\FB_Auth' );

	return $container;
}


function initialize_app( Container $container ) {
	/** @var RentiFlat $rentiflat */
	$rentiflat = $container->lookup( 'RentiFlat' );
	$rentiflat->init();
}


function get_IOC_container() {
	static $container;

	if ( ! $container ) {
		$container = initialize_IOC();
	}

	return $container;
}

initialize_app( get_IOC_container() );

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
		->singleton( 'theme', __NAMESPACE__ . '\Theme' );

	return $container;
}


function initialize_app( Container $container ) {
	/** @var RentiFlat $rentiflat */
	$rentiflat = $container->lookup( 'RentiFlat' );

	add_action( 'init', [ $rentiflat, 'init' ] );
}

$container = initialize_IOC();

initialize_app( $container );

<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

use Encase\Container;
use Lamosty\RentiFlat\RentiFlat;


function initialize_IOC() {
	$container = new Container();

	$container
		->object( 'RentiFlat', function () {
			return new RentiFlat();
		} );

	return $container;
}


function initialize_app( Container $container ) {
	/** @var RentiFlat $rentiflat */
	$rentiflat = $container->lookup( 'RentiFlat' );

	$rentiflat->init();
}

$container = initialize_IOC();

initialize_app( $container );

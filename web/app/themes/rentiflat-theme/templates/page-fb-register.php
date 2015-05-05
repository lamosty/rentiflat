<?php
/*
 * Template Name: Facebook Register Page
 */

/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

$container = get_IOC_container();

/** @var FB_Auth $fb_auth */
$fb_auth = $container->lookup('fb_auth');

?>

<div class="container">
	<section id="fb-register" class="center">
		<h1><?= __( 'Register with RentiFlat' ); ?></h1>

		<p><?= __( "To register, you need to authenticate with your Facebook profile." ); ?></p>
		<a href="<?= $fb_auth->get_fb_login_url(); ?>" class="btn btn-primary">
			Authenticate with Facebook
		</a>
	</section>
</div>


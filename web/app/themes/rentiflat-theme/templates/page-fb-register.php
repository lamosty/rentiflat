<?php
/*
 * Template Name: Facebook Register Page
 */

/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

$container = get_IOC_container();

/** @var Auth $auth */
$auth = $container->lookup( 'auth' );

$auth->enqueue_fb_js_sdk();


?>

<div id="fb-root"></div>
<div class="container">
	<section id="fb-register" class="center">
		<?php

		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {

			$errors = $auth->register_user();

			if ( ! is_wp_error( $errors ) ) {

				$user_id = $errors;

				$auth->add_fb_data($user_id);
				$auth->template_registration_successful();

			} else {

				$auth->template_registration_errors( $errors );
				$auth->template_registration_form();
			}
		} else {

			$auth->template_auth_with_fb();
			$auth->template_registration_form( [ 'display' => 'none' ] );

		}

		?>


	</section>
</div>


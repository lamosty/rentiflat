<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;

class Auth {
	private $app_id;
	private $app_secret;

	private $profile_pictures_path = 'rentiflat_profiles';

	public function init() {
		$this->app_id = getenv('FB_APP_ID');
		$this->app_secret = getenv('FB_APP_SECRET');
	}

	public function enqueue_fb_js_sdk() {
		?>
		<script>
			window.fbAsyncInit = function () {
				FB.init({
					appId: '<?= $this->app_id; ?>',
					xfbml: true,
					cookie: true,
					version: 'v2.3'
				});

				jQuery(document).trigger('rentiflat_fbInit');
			};

			(function (d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) {
					return;
				}
				js = d.createElement(s);
				js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>
	<?php
	}

	public function get_data_from_fb() {
		$fb = new Facebook( [
			'app_id'                => $this->app_id,
			'app_secret'            => $this->app_secret,
			'default_graph_version' => 'v2.3'
		] );

		$helper = $fb->getJavaScriptHelper();

		$access_token = null;

		try {
			$access_token = $helper->getAccessToken();
		} catch ( FacebookSDKException $e ) {
			// There was an error communicating with Graph
			// Or there was a problem validating the signed request
			echo $e->getMessage();
			exit;
		}

		$data = $fb->get( '/me', $access_token )->getDecodedBody();

		$user_data = [
			'first_name' => $data['first_name'],
			'last_name'  => $data['last_name'],
			'fb_url'     => $data['link']
		];

		$data = $fb->sendRequest(
			'GET',
			'/me/picture',
			[
				'width'  => 130,
				'height' => 130
			],
			$access_token
		);

		$user_data['fb_picture'] = $data->getHeaders()['Location'];

		return $user_data;
	}

	public function add_fb_data( $user_id ) {
		$user_data = $this->get_data_from_fb();

		update_user_meta( $user_id, 'first_name', $user_data['first_name'] );
		update_user_meta( $user_id, 'last_name', $user_data['last_name'] );
		add_user_meta( $user_id, 'rentiflat_fb_url', $user_data['fb_url'], true );

		$upload_basedir       = wp_upload_dir()['basedir'];
		$profile_pictures_dir = $upload_basedir . '/' . $this->profile_pictures_path;

		if ( ! file_exists( $profile_pictures_dir ) ) {
			mkdir( $profile_pictures_dir, 0755 );
		}

		copy( $user_data['fb_picture'], $profile_pictures_dir . '/' . $user_id . '.jpg' );

		$picture_path = $this->profile_pictures_path . '/' . $user_id . '.jpg';
		add_user_meta( $user_id, 'rentiflat_fb_picture', $picture_path, true );
	}

	public function register_user() {
		$user_login = $_POST['user_login'];
		$user_email = $_POST['user_email'];

		return register_new_user( $user_login, $user_email );
	}

	public function template_registration_errors( \WP_Error $errors ) {
		?>
		<h1><?= __( 'Registration unsuccessful' ); ?></h1>
		<ul class="errors">
			<?php

			foreach ( $errors->get_error_messages() as $error ) {
				?>

				<li><?= $error; ?></li>

			<?php
			}

			?>
		</ul>
	<?php
	}

	public function template_registration_form( $style = [ 'display' => 'block' ] ) {
		$style_string = '';

		foreach ( $style as $key => $value ) {
			$style_string .= $key . ':' . $value . ';';
		}

		$user_login = '';
		if ( array_key_exists( 'user_login', $_POST ) ) {
			$user_login = $_POST['user_login'];
		}

		$user_email = '';
		if ( array_key_exists( 'user_email', $_POST ) ) {
			$user_email = $_POST['user_email'];
		}

		?>
		<form id="rentiflat-register-form" class="form-horizontal" method="post" style="<?= $style_string; ?>">
			<fieldset>
				<div class="form-group">
					<div class="input-group form-control-wrapper">
						<input type="text" name="user_login"
						       class="form-control <?= strlen( $user_login ) > 1 ? '' : 'empty'; ?>"
						       value="<?= esc_attr( wp_unslash( $user_login ) ); ?>"
							/>

						<div class="floating-label"><?php _e( 'Username' ); ?></div>
						<span class="material-input"></span>
					</div>
				</div>
				<div class="form-group">
					<div class="form-control-wrapper">
						<input type="email" name="user_email"
						       class="form-control <?= strlen( $user_login ) > 1 ? '' : 'empty'; ?>"
						       value="<?= esc_attr( wp_unslash( $user_email ) ); ?>"
							/>

						<div class="floating-label"><?php _e( 'E-mail' ); ?></div>
						<div class="material-input"></div>
					</div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary"><?php _e( 'Register' ); ?></button>
				</div>
			</fieldset>
			<p><?php _e( 'A password will be e-mailed to you.' ) ?></p>
		</form>
	<?php
	}

	public function template_registration_successful() {
		?>
		<h1><?= __( 'Registration successful!' ); ?></h1>
		<p><?= __( 'Please check your email for the password.' ); ?></p>
		<a href="<?= site_url( '/wp-login.php' ); ?>" class="btn btn-primary">
			<?= __( 'Login' ); ?>
		</a>
	<?php
	}

	public function template_auth_with_fb() {
		?>
		<h1><?= __( 'Register with RentiFlat' ); ?></h1>

		<p><?= __( "To register, you firstly need to authenticate with your Facebook profile." ); ?></p>
		<a id="fb-login" href="#" class="btn btn-primary">
			Authenticate with Facebook
		</a>
	<?php
	}


}
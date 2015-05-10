<?php
/**
 * @ Lamosty.com 2015
 */

use Lamosty\RentiFlat\Theme;
use Lamosty\RentiFlat\Flat;
use Lamosty\RentiFlat\Utils\Template_Helper as TH;
use Lamosty\RentiFlat\User;

// Set some global variables for the current flat page
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		$flat_owner = get_userdata( get_the_author_meta( 'ID' ) );

		do_action( 'rentiflat_flat_page', get_the_ID(), $flat_owner );

		$post_id = get_the_ID();

		$flat_photos = get_attached_media( 'image' );

		$featured_photo_id = get_post_thumbnail_id();

		$photos = [ ];

		foreach ( $flat_photos as $photo_id => $photo_object ) {
			$photos[ $photo_id ] = [
				'thumbnail' => wp_get_attachment_image_src( $photo_id, Theme::FLAT_THUMBNAIL_SIZE )[0],
				'large'     => wp_get_attachment_image_src( $photo_id, 'post-thumbnail' )[0]
			];
		}


		?>

		<div class="container">
			<section id="flat-photos">
				<div class="main-photo">
					<img class="img-responsive" src="<?= $photos[ $featured_photo_id ]['large']; ?>"
					     alt="Flat photo" id="large-photo"/>
				</div>
				<div class="other-photos">
					<?php

					foreach ( $photos as $photo_id => $photo ) {
						?>
						<a href="#" data-photo-large="<?= $photo['large']; ?>">
							<img class="img-responsive" src="<?= $photo['thumbnail']; ?>" alt="Flat photo"/>
						</a>

					<?php
					}

					?>
				</div>
			</section>

			<section id="main-info" class="row">
				<div class="col-md-10">
					<div class="row">
						<div class="title col-md-10">
							<h1 class="name"><?= get_the_title(); ?></h1>

							<div class="address"><?= Flat::get_address( $post_id ); ?></div>
						</div>
						<div class="price-info">
							<div class="price">
								<?= Flat::get_price( $post_id ); ?>
								<span class="currency">&euro;</span>
							</div>
							<div class="interval">per month</div>
						</div>
					</div>
					<div class="features row">
						<div class="feature col-md-3">
							<i class="circle-icon mdi-maps-local-hotel"></i>

							<div
								class="text"><?= Flat::get_type( $post_id ); ?></div>
						</div>

						<div class="feature col-md-3">
							<i class="circle-icon mdi-social-people"></i>

							<div class="text">
								<?php $num_of_persons = (int) get_post_meta( $post_id, 'num_of_persons', true ); ?>

								for
								<?= sprintf( _n( '1 person', '%s persons', $num_of_persons ), $num_of_persons ); ?>
							</div>
						</div>

						<div class="feature col-md-3">
							<i class="circle-icon mdi-image-texture"></i>

							<div class="text">
								area
								<?= Flat::get_area( $post_id ); ?>
								m<sup>2</sup>
							</div>
						</div>

						<?php
						$new_building = get_post_meta( $post_id, 'new_building', true );

						if ( $new_building == '1' ) :
							?>
							<div class="feature col-md-3">
								<i class="circle-icon mdi-social-location-city"></i>

								<div class="text">new building</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="flat-owner col-md-2">
					<div class="picture">
						<img src="<?= User::get_profile_picture( $flat_owner ); ?>"
						     alt="<?= User::get_full_name( $flat_owner ); ?> profile picture"/>
					</div>
					<div class="name">
						<a href="<?= User::get_fb_url( $flat_owner ); ?>" target="_blank">
							<?= User::get_full_name( $flat_owner ); ?>
							<sup><i class="mdi-action-open-in-new"></i></sup>

						</a>
					</div>
				</div>


			</section>
		</div>

		<section id="more-info" class="bg-silver">
			<div class="container">


				<div class="description">
					<?php the_content(); ?>
				</div>
				<div class="features">
					<div class="feature">
						<div class="name">Elevator</div>
						<i class=
						   "mdi-navigation-<?= TH::flat_has_feature( $post_id, 'elevator' ) ? 'check' : 'close'; ?>">
						</i>
					</div>
					<div class="feature">
						<div class="name">Balcony</div>
						<i class=
						   "mdi-navigation-<?= TH::flat_has_feature( $post_id, 'balcony' ) ? 'check' : 'close'; ?>">
						</i>
					</div>
					<div class="feature">
						<div class="name">Cellar</div>
						<i class=
						   "mdi-navigation-<?= TH::flat_has_feature( $post_id, 'cellar' ) ? 'check' : 'close'; ?>">
						</i>
					</div>
					<div class="feature">
						<div class="name">Floor</div>
						<span class="floor-num">
							<?= get_post_meta( $post_id, 'floor_num', true ); ?>.
						</span>
					</div>
				</div>
			</div>
		</section>

		<section id="location-map">

		</section>
		<div class="container">
			<section id="bids">
			</section>
		</div>

	<?php

	endwhile;
endif;
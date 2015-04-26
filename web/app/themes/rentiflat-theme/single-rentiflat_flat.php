<?php
/**
 * @ Lamosty.com 2015
 */

use Lamosty\RentiFlat\Theme;

// Set some global variables for the current flat page
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

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
							<h1 class="name">1 izbový byt v centre Starého mesta</h1>

							<div class="address">Dunajská 45, 90028 Bratislava</div>
						</div>
						<div class="price-info">
							<div class="price">
								450
								<span class="currency">&euro;</span>
							</div>
							<div class="interval">per month</div>
						</div>
					</div>
					<div class="features row">
						<div class="feature col-md-3">
							<i class="circle-icon mdi-maps-local-hotel"></i>

							<div class="text">1 room</div>
						</div>

						<div class="feature col-md-3">
							<i class="circle-icon mdi-social-people"></i>

							<div class="text">for 2 persons</div>
						</div>

						<div class="feature col-md-3">
							<i class="circle-icon mdi-image-texture"></i>

							<div class="text">area 45m</div>
						</div>

						<div class="feature col-md-3">
							<i class="circle-icon mdi-social-location-city"></i>

							<div class="text">new building</div>
						</div>
					</div>
				</div>
				<div class="flat-owner col-md-2">
					<div class="picture">
						<img src="holder.js/130x130" alt="Flat owner picture"/>
					</div>
					<div class="name">
						<a href="#" target="_blank">
							Rastislav Lamos
							<sup><i class="mdi-action-open-in-new"></i></sup>

						</a>
					</div>
				</div>


			</section>
		</div>

		<section id="more-info" class="bg-silver">
			<div class="container">


				<div class="description">
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sit amet euismod arcu. Curabitur
						lacus
						magna,
						ultrices sed lacus eu, consectetur suscipit purus. Etiam vehicula urna non turpis pretium, ut
						sagittis
						urna
						malesuada. Maecenas pretium lacus urna, at laoreet mi gravida quis. Pellentesque laoreet diam
						ante, ac
					</p>

					<p>
						convallis libero bibendum a. Fusce aliquam tristique mi sed aliquam. Donec ac molestie urna.
						Etiam eget
						pharetra urna. Nam est magna, hendrerit in porta ac, tempor eget arcu. Sed tempor consectetur
						odio, quis
						fermentum nibh interdum sit amet.
					</p>
					<ul>
						<li>Point 1</li>
						<li>Point 2</li>
						<li>Some other long point</li>
					</ul>
				</div>
				<div class="features">
					<div class="feature">
						<div class="name">Elevator</div>
						<i class="mdi-navigation-check"></i>
					</div>
					<div class="feature">
						<div class="name">Balcony</div>
						<i class="mdi-navigation-check"></i>
					</div>
					<div class="feature">
						<div class="name">Cellar</div>
						<i class="mdi-navigation-close"></i>
					</div>
					<div class="feature">
						<div class="name">Floor</div>
						<span class="floor-num">2.</span>
					</div>
				</div>
			</div>
		</section>

		<section id="location-map">

		</section>
		<div class="container">
			<section id="bids" class="row">
				<div class="bid-form-section col-md-6">
					<div class="row">
						<div class="tenant">
							<div class="picture">
								<img src="holder.js/130x130" alt="User tenant picture"/>
							</div>
							<div class="name">
								Rastislav Lamos
							</div>
						</div>
						<div class="col-md-6">
							<form id="bid-form" class="form-horizontal" action="#">
								<fieldset>
									<div class="form-group">
										<div class="col-md-6">
											<div class="input-group form-control-wrapper">
												<input type="text" class="form-control empty"/>

												<div class="floating-label">Bidding price</div>
												<span class="material-input"></span>
												<span class="input-group-addon">$</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-12">
											<div class="form-control-wrapper">
												<input type="email" class="form-control empty"/>

												<div class="floating-label">Email address</div>
												<div class="material-input"></div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-12">
											<button type="submit" class="btn btn-primary">I'm interested</button>
										</div>
									</div>
								</fieldset>
							</form>

						</div>
					</div>
				</div>
				<div class="bids-list col-md-6">
					<h3>List of candidates</h3>

					<div class="bid">
						<div class="picture">
							<img src="holder.js/130x130" alt="Candidate picture"/>
						</div>
						<div class="info">
							<div class="title">Name</div>
							<div class="name">
								Rastislav
						<span class="label label-info" data-toggle="tooltip" data-placement="top"
						      title="Only flat owners can see the last name.">
							Hidden
						</span>
							</div>

							<div class="title">Email address</div>
							<div class="email">
						<span class="label label-info" data-toggle="tooltip" data-placement="top"
						      title="Only flat owners can see the email address.">
							Hidden
						</span>
							</div>
						</div>
						<div class="price-info">
							<div class="price">
								460
								<span class="currency">&euro;</span>
							</div>
							<div class="date">02/29/2015</div>
						</div>
					</div>

					<div class="bid">
						<div class="picture">
							<img src="holder.js/130x130" alt="Candidate picture"/>
						</div>
						<div class="info">
							<div class="title">Name</div>
							<div class="name">
								Rastislav Lamos
							</div>

							<div class="title">Email address</div>
							<div class="email">
								lamos.rasto@gmail.com
							</div>
						</div>
						<div class="price-info">
							<div class="price">
								1500
								<span class="currency">&euro;</span>
							</div>
							<div class="date">02/29/2015</div>
						</div>
					</div>
				</div>

			</section>
		</div>

	<?php

	endwhile;
endif;
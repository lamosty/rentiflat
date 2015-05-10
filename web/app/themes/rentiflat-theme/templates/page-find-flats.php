<?php
/*
 * Template Name: Find Flats Page
 */

/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

$flats = new \WP_Query( [
	'post_type' => Flat::$post_type_id
] );

?>

<section class="container" id="find-flats">
	<h1>Find Flats</h1>

	<div class="flats">
		<?php

		while ( $flats->have_posts() ) :
			$flats->the_post();

			?>

			<a class="flat" href="<?= get_the_permalink(); ?>">
				<div class="flat-inner">
					<div class="picture">
						<?= get_the_post_thumbnail( get_the_ID(), Theme::FLAT_FIND_FLATS_SIZE ); ?>
					</div>
					<div class="main-info">
						<h3 class="title">
							<?= get_the_title(); ?>
						</h3>

						<div class="rooms">
							<i class="mdi-maps-local-hotel"></i>
							<?= Flat::get_type( get_the_ID() ); ?>
						</div>
						<div class="area">
							<i class="mdi-image-texture"></i>
							area <?= Flat::get_area( get_the_ID() ); ?> m<sup>2</sup>
						</div>
						<div class="location"><?= Flat::get_address( get_the_ID() ); ?></div>
					</div>
					<div class="price-info-container">
						<div class="price-info">
							<div class="price">
								<?= Flat::get_price( get_the_ID() ); ?> <span class="currency">€</span>
							</div>
							<div class="interval">per month</div>
						</div>
					</div>
				</div>
			</a>
		<?php endwhile; ?>
	</div>
</section>

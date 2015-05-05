<?php
/**
 * @ Lamosty.com 2015
 */

use Lamosty\RentiFlat\Utils\Template_Helper;

?>
<div class="top-bar">
	<nav class="navbar navbar-default" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse"
				        data-target=".navbar-responsive-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<div class="navbar-brand">RentiFlat</div>
			</div>

			<div class="navbar-collapse collapse navbar-responsive collapse">
				<ul class="nav navbar-nav">
					<li class="<?= Template_Helper::nav_set_active( '/' ); ?>">
						<a href="<?= home_url( '/' ); ?>">Home</a>
					</li>
					<li class="<?= Template_Helper::nav_set_active( '/add-new-flat/' ); ?>">
						<a href="<?= home_url( '/add-new-flat/' ); ?>">Add New Flat</a>
					</li>
					<li class="<?= Template_Helper::nav_set_active( '/find-flats/' ); ?>">
						<a href="<?= home_url( '/find-flats/' ); ?>">Find Flats</a>
					</li>
					<li class="<?= Template_Helper::nav_set_active( '/contact/' ); ?>">
						<a href="<?= home_url( '/contact/' ); ?>">Contact</a>
					</li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					<li><a href="<?= home_url( '/register/' ); ?>">Register</a></li>
					<li><a href="<?= home_url( '/login/' ); ?>">Login</a></li>
				</ul>
			</div>
		</div>
	</nav>
</div>

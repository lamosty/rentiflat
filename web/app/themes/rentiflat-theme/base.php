<?php
/**
 * @ Lamosty.com 2015
 */

namespace Lamosty\RentiFlat;

use Lamosty\RentiFlat\Utils\Template_Helper;

?>

<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php

do_action( 'get_header' );


?>

<div class="wrap container" role="document">
	<div class="content row">
		<main class="main" role="main">
			<?php include Template_Helper::template_path(); ?>
		</main>
		<!-- /.main -->

	</div>
	<!-- /.content -->
</div>
<!-- /.wrap -->

<?php


wp_footer();

?>
</body>
</html>


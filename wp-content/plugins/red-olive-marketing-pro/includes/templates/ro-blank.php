<?php

/*
 * Template Name: RO Blank
 *
 * Simple template for RO Marketing plugin Social Media Reviews Page
 */

?>

<?php get_header(); ?>

<div id="main-content" class="main-content">

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php
				while ( have_posts() ) : the_post();

					the_content();

				endwhile;
			?>

		</div>
	</div>
</div>


<?php get_footer(); ?>
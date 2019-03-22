<?php get_header(); ?>

<!-- POTENTIONAL LEFTHAND SIDEBARS ECT GO HERE -->
<div id="main">

	<div class="container page-content">

		<!-- MAIN PAGE CONTENT SHOULD GO HERE -->
		<?php if ( have_posts() ) : ?>

		  <?php while ( have_posts() ) : the_post(); ?>

		  	<div id="single-post">

			  	<h1 class="page-title"><?php the_title() ?></h1>

					<?php the_content() ?>

				</div>

		  <?php endwhile; ?>

		<?php endif; ?>

	</div>

</div>
<!-- POTENTIONAL RIGHTHAND SIDEBARS ECT GO HERE -->

<?php get_footer(); ?>

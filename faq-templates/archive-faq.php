<?php get_header(); ?>

<div id="main">

  <div class="container">

		<?php if ( have_posts() ) : ?>

			<div id="page-archive">

				<h1>Frequently Asked Questions</h1>

				<?php while ( have_posts() ) : the_post(); ?>

					<div class="post-block type-faq">

						<div class="content-wrapper">

		          <h2 class="post-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>

		          <h3 class="excerpt"><?php the_excerpt() ?></h3>

              <!-- Only display link if content is different from excerpt -->
		          <?php if ( get_the_content() != "" && get_the_excerpt() !== get_the_content() ) : ?>
		            <a href="<?php the_permalink() ?>">More Details</a>
		          <?php endif; ?>

		        </div>

	        </div>

				<?php endwhile; ?>

			</div>

		<?php endif; ?>

	</div>

</div>

<?php get_footer(); ?>

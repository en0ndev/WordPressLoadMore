<?php

function your_post_loop() {

	if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'assets/content', get_post_type() ); ?>

		<?php endwhile; ?>

		<?php echo '<div class="button_loadmore" id="load_more">Load More</div>'; ?>

		<?php else : ?>

			<?php get_template_part('assets/content-none'); ?>

		<?php endif;

	}

	?>
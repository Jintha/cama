<?php get_header(); ?>

<div id="wrappinner">

	<div id="main">

	<?php if (have_posts()) : ?>

		<h3 class="response">Search Results</h3>

		<?php while (have_posts()) : the_post(); ?>

			<div class="entryBox">
			<div class="title">
				<h2>
		<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
		<?php the_title(); ?></a>
				</h2>
			</div><!-- end .title -->
		<span class="date">
		<?php the_time('l, F jS, Y') ?>
		</span><!-- end .date-->

	<div class="entryBottom">
		<span>
		Posted in <?php the_category(', ') ?> | 
		<?php edit_post_link('Edit', '', ' | '); ?>  
		<?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
		</span>
	</div><!-- end .entryBottom -->

</div><!-- end .entryBox -->

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Previous Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Next Entries &raquo;') ?></div>
		</div>

	<?php else : ?>

		<h3 class="response">No posts found. Try a different search?</h3>
		

	<?php endif; ?>

	</div><!-- end #main -->

<?php get_sidebar(); ?>

</div><!-- end #wrappinner -->

<?php include (TEMPLATEPATH . '/sidebar2.php'); ?>

<?php get_footer(); ?>
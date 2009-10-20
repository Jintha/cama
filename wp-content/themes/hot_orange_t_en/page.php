<?php get_header(); ?>

<div id="wrappinner">

	<div id="main">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div class="entryBox" id="post-<?php the_ID(); ?>">

<div class="title">
		<h2>
	<?php the_title(); ?>
		</h2>
</div><!-- end .title -->

			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div><!-- end .entry -->

	<div class="entryBottom">
		<span>
			<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '', ''); ?>
		</span>
	</div><!-- end .entryBottom -->

</div><!-- end .entryBox -->
	
</div><!-- end #main -->

<?php get_sidebar(); ?>

</div><!-- end #wrappinner -->

<?php include (TEMPLATEPATH . '/sidebar2.php'); ?>

<?php get_footer(); ?>
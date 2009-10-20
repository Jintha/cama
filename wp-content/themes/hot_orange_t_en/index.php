<?php get_header(); ?>

<div id="wrappinner">

<div id="main">

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

	<div class="entryBox" id="post-<?php the_ID(); ?>">

	<div class="title">
		<h2>
	<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
<?php the_title(); ?></a>
		</h2>
	</div><!-- end .title -->

	<span class="date">
		<?php the_time('F jS, Y') ?> 
	</span><!-- end .date-->

	<div class="entry">
		<?php the_content('Read the rest of this entry &raquo;'); ?>
	</div><!-- end .entry -->

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

		<h3 class="response">Not Found</h3>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		

	<?php endif; ?>

</div><!-- end #main -->

<?php get_sidebar(); ?>

</div><!-- end #wrappinner -->

<?php include (TEMPLATEPATH . '/sidebar2.php'); ?>


<?php get_footer(); ?>

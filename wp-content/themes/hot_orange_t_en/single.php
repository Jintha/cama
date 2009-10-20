<?php get_header(); ?>

<div id="wrappinner">

	<div id="main">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="entryBox" id="post-<?php the_ID(); ?>">

	<div class="title">
		<h2>
	<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
	<?php the_title(); ?></a>
		</h2>
	</div><!-- end .title -->

	<div class="entry">
		<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
	<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
	</div><!-- end .entry -->

	<div class="entryBottom">
	</div><!-- end .entryBottom -->

</div><!-- end .entryBox -->



	<div class="entryBox">
		<div class="entryDescription">
			<p>
	This entry was posted
	on <?php the_time('l, F jS, Y') ?> at <?php the_time() ?>
	and is filed under <?php the_category(', ') ?>.
	You can follow any responses to this entry through the <?php comments_rss_link('RSS 2.0'); ?> feed.

	<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
	// Both Comments and Pings are open ?>
	You can <a href="#respond">leave a response</a>, or 
	<a href="<?php trackback_url(true); ?>" rel="trackback">trackback</a> from your own site.

	<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
	// Only Pings are Open ?>
	Responses are currently closed, but you can 
	<a href="<?php trackback_url(true); ?> " rel="trackback">trackback</a> from your own site.

	<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
	// Comments are open, Pings are not ?>
	You can skip to the end and leave a response. Pinging is currently not allowed.

	<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
	// Neither Comments, nor Pings are open ?>
	Both comments and pings are currently closed.

	<?php } edit_post_link('Edit this entry.','',''); ?>				
		</p>
	</div><!-- end .entryDescription -->

	<div class="entryBottom">
	</div><!-- end .entryBottom -->

</div><!-- end .entryBox -->
<!--	end of single post 	-->

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

</div><!-- end #main -->

<?php get_sidebar(); ?>

</div><!-- end #wrappinner -->

<?php include (TEMPLATEPATH . '/sidebar2.php'); ?>

<?php get_footer(); ?>

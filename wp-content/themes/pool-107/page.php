<?php get_header(); ?>

	<div id="bloque">
		<div id="noticias">
		
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div class="entrada">
				<h2 id="post-<?php the_ID(); ?>"><?php the_title(); ?></h2>
				
			<?php the_content("Continue reading ".the_title('', '', false)."..."); ?>
			<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
			<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
			</div>
			
		</div>

		<?php endwhile; endif; ?>

<?php get_footer(); ?>

<!-- begin sidebar -->
		<div id="sidebar">
			
		<?php if (function_exists('wp_theme_switcher')) { ?>
			<div id="themes">			
			<h3><?php _e('Themes'); ?></h3>
				<?php wp_theme_switcher(); ?>
			</div>
		<?php } ?>
		
			<div id="categories">			
			<h3><?php _e('Categories:'); ?></h3>
				<ul>
				<?php wp_list_cats('sort_column=name&optioncount=1&feed=rss'); ?>
				</ul>
			</div>
			
			<div id="archives">			
			<h3><?php _e('Archives:'); ?></h3>
				<ul>
				<?php wp_get_archives('type=monthly&show_post_count=1'); ?>
				</ul>
			</div>
			
			<div id="blogroll">
			<h3>Blogroll</h3>
				<ul>
				<?php get_links(-1, '<li>', '</li>', ' - '); ?>
				</ul>
			</div>

			<div id="meta">
				<h3><?php _e('Meta:'); ?></h3>
				<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
				<li><a href="http://wordpress.org/" title="<?php _e('Powered by WordPress, state-of-the-art semantic personal publishing platform.'); ?>"><abbr title="WordPress">WP</abbr></a></li>
				<?php wp_meta(); ?>
				</ul>
			</div>
		
		</div>
			
<div class="both"></div>
			
</div>

<!-- end sidebar -->
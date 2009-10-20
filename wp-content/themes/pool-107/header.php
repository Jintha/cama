<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
	
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats please -->

	<style type="text/css" media="screen">
		@import url(<?php bloginfo('stylesheet_url'); ?>);
	</style>

	<link rel="Shortcut Icon" href="<?php echo get_settings('home'); ?>/wp-content/themes/pool/images/favicon.ico" type="image/x-icon" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php wp_get_archives('type=monthly&format=link'); ?>
	<?php //comments_popup_script(); // off by default ?>
	<?php wp_head(); ?>
</head>

<body>
<div id="content">
	
	<div id="header" onclick="location.href='<?php echo get_settings('home'); ?>';" style="cursor: pointer;">
		<h1><a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a></h1>
	</div>

	<div id="pages">
			<div class="alignleft">
				<ul>
				<li><a href="<?php echo get_settings('home'); ?>">Blog</a></li>
				<?php wp_list_pages('title_li='); ?>
				</ul>
			</div>
		
			<div id="search">
				<form id="searchform" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input type="text" name="s" id="s" size="20" value="search in blog..." />
				</form>
			</div>
	</div>
	
<!-- end header -->
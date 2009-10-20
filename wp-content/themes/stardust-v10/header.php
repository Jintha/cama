<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>

	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats please -->

	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />
    
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="shortcut icon" href="<?php bloginfo('url'); ?>/wp-content/themes/stardust/favicon.ico" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_get_archives('type=monthly&format=link'); ?>
	<?php //comments_popup_script(); // off by default ?>
	<?php wp_head(); ?>
</head>

<body>
<div id="container">

<ul class="skip">
<li><a href="#wrapper">Skip to content</a></li>
<li><a href="#menu">Skip to menu</a></li>
</ul>

<hr />

    <div id="header">
    <h1><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1>
    <p class="payoff"><?php bloginfo('description'); ?></p>
   
   <form id="searchform" method="get" action="<?php bloginfo('home'); ?>">
	<div>
    	<label for="s"><?php _e('Search'); ?></label>
		<input type="text" name="s" id="s" size="15" value="search..." onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value=='search...') this.value='';"/>
		<button type="submit"><img src="<?php bloginfo('template_url'); ?>/images/cerca.jpg" alt="search" /></button><!--<input type="submit" value="<?php _e('Search'); ?>" />-->
	</div>
	</form>
    
    <p id="rss"><a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to this site!"><img src="<?php bloginfo('template_url'); ?>/images/rss.jpg" alt="Rss 2.0" /></a></p>
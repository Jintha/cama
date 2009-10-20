=== Creative Clans Slide Show  ====
Contributors: tonnaer
Donate link: http://www.creativeclans.nl
Tags: slideshow, presentation, flash, widget, creative, clans
Requires at least: 2.7
Tested up to: 2.8.4
Stable tag: 1.2.1

A free widget to use the Creative Clans Slide Show in your Wordpress website. 

== Description ==

A free, small (only 12Kb) flash slide show widget, that can easily be integrated
into your Wordpress 2.8 website, and can be entirely (or almost) personalized 
through a bunch of parameters.

Features:
* Succesfully tested with the new version 2.8 of Wordpress
* also works if you've given 'wordpress its own directory' (see http://codex.wordpress.org/Giving_WordPress_Its_Own_Directory)
* works with redirects
* works with permalinks
* height and width can be set
* 28 transition types
* links can be enabled (one for the whole slide show, or one for each slide)
* slides can be shown in forward, backward or random order
* border style, color, width and transparancy can be specified
* caption can be text or image
* caption can be the same for the whole slide show, or a different one for each slide
* caption characteristics (position, style, font, fontsize, color, transparancy, etc)
  can be specified
* you can have more than one slide show on your pages.
* etc.

== Installation ==

1. Upload `creativeclans-slideshow` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Upload some pictures (remember to disable 'Organize my uploads into
   month- and year-based folders' in the Wordpress miscellanious settings, or use
   a plugin that allows you to upload the images to a folder with a fixed name, 
   or else you'll have to know the folder of each image and include it in the image
   name).
4. You can add the slide show through the 'Widgets' menu, and change the
   settings by editing the added widget.
5. You can add as many slide shows as you want. Each slide show has it's own 
   set of settings.
6. To make the images appear in the slide show, you need to set the first two
   parameters:
   * path : the absolute path to the slides folder, WITH the final slash.
       (use this setting when you have disabled 'Organize my uploads into
        month- and year-based folders' in the Wordpress miscellanious settings, 
        or use a plugin that allows you to upload the images to a single folder)
   * Images : list of the image names, one image per line.    
       (if you didn't use the 'path' setting, or only put a part of the path
        there (because you've enabled 'Organize my uploads into month- and 
        year-based folders' in the Wordpress miscellanious settings, or anyway
        the images are stored in different folders), then you'll have to add
        the (missing part of) the path to the image names)
    
== Frequently Asked Questions ==

= Where I can find more documentation? =
On the plugin homepage, and in the 'settings documentation.txt' document included in the package.

= Can I make the slide show multi-lingual? =
Yes you can. It involves using the qTranslate plugin and the Widget Logic plugin. Read all the details on the plugin homepage.

== Screenshots ==
No screenshots are available

== Version history ==

= Version 1.1 =
* Works with permalinks

= Version 1.0 =
* Initial release version

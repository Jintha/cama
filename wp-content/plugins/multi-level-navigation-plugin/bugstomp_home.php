<?php
// equinevideo reported a potential bug when using the home page in menu item #1
// This script should correct for that bug ... hopefully!
// http://pixopoint.com/forum/index.php?topic=782.0

// Loading WP functions
require('../../../wp-blog-header.php');

// Checking user permissions
global $userdata;
get_currentuserinfo();
if ($userdata->user_level == 10) {global $wpdb;


echo '<p>Note to equinevideo: Please report the following message in the forum please as it will help me figure out what the heck is causing the problem. Thanks :)</p>
<h2 style="height:40px;background:#ddd">'.get_option('suckerfish_menuitem1').'</h2>';

delete_option('suckerfish_menuitem1');

echo '<p>If it has worked, you should see nothing in the following box.</p>
<h2 style="height:40px;background:#ddd">'.get_option('suckerfish_menuitem1').'</h2>';


}

?>

<?php
/*
Plugin Name: Downloads Manager
Plugin URI: http://giulioganci.netsons.org/2006/12/20/wp-downloads-manager/
Description: This Plugin adds a simple downloads manager to your blog.
Version: 1.0 rc-1
Author: Giulio Ganci
Author URI: http://giulioganci.netsons.org/

	Copyright 2008  Giulio Ganci  (email : julius.exe@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

### Load Languages File
load_plugin_textdomain('downloads-manager','wp-content/plugins/downloads-manager/languages');

global $table_prefix, $wpdb;
$dm_tables = array($table_prefix.'dm_downloads', $table_prefix.'dm_category');
$plugin_url = get_bloginfo('siteurl').'/wp-content/plugins/downloads-manager';
$iconsdir = $plugin_url.'/img/icons';
$downloadsdir = ABSPATH.'wp-content/plugins/downloads-manager/upload/';

### Load my Function
require_once('functions.php');

### Init Downloads Manager Admin Menu
function DownloadsManager_Init() {
  add_menu_page(__('Downloads Manager', 'downloads-manager'), __('Downloads Manager', 'downloads-manager'),6,dirname(__FILE__).'/pages/dm-main.php');
  if (function_exists('add_submenu_page')) {
    add_submenu_page(dirname(__FILE__).'/pages/dm-main.php', __('Add Download', 'downloads-manager'), __('Add Download', 'downloads-manager'), 6, dirname(__FILE__).'/pages/dm-add.php');
    add_submenu_page(dirname(__FILE__).'/pages/dm-main.php', __('Stats', 'downloads-manager'), __('Stats', 'downloads-manager'), 6, dirname(__FILE__).'/pages/dm-stats.php');
    add_submenu_page(dirname(__FILE__).'/pages/dm-main.php', __('Categories', 'downloads-manager'), __('Categories', 'downloads-manager'), 6, dirname(__FILE__).'/pages/dm-categories.php');
    add_submenu_page(dirname(__FILE__).'/pages/dm-main.php', __('Template', 'downloads-manager'), __('Template', 'downloads-manager'), 6, dirname(__FILE__).'/pages/dm-template.php');
    add_submenu_page(dirname(__FILE__).'/pages/dm-main.php', __('Uninstall Downloads Manager', 'downloads-manager'), __('Uninstall Downloads Manager', 'downloads-manager'), 6, dirname(__FILE__).'/pages/dm-uninstall.php');
  }
}

### Init Downloads Manager Widgets
function DownloadsManager_WidgetsInit() {
  if ( !function_exists('register_sidebar_widget') )
    return;

  ### Most Downloaded Widget
  function widget_dm_top10($args) {
    $settings = get_option('widget_dm_top10');
    extract($args);
    $title = $settings['title'];
    echo $before_widget.$before_title.$title.$after_title;
    dm_get_most_downloaded('<li>', '</li>', $settings['limit'], $settings['avarage'], $settings['category']);
    echo $after_widget;
  }
  
  function widget_dm_top10_settings() {
    $settings = get_option('widget_dm_top10');
		if (!is_array($settings))
			$settings = array('title' => __('Most Downloaded', 'downloads-manager'), 'limit' => 10, 'avarage' => false, 'category' => false);
    if ($_POST['most_downloaded_widgets_update']) {
			$settings['title'] = strip_tags(addslashes($_POST['most_downloaded_widgets_title']));
			$settings['limit'] = intval($_POST['most_downloaded_widgets_limit']);
			$settings['avarage'] = $_POST['most_downloaded_widgets_avarage'];
      $settings['category'] = $_POST['most_downloaded_widgets_category'];
			update_option('widget_dm_top10', $settings);
		}
    $checked = $settings['category'] ? 'checked' : '';
    echo '<p><label>'.__('Title', 'downloads-manager').': <input type="text" name="most_downloaded_widgets_title" value="'.htmlspecialchars(stripslashes($settings['title'])).'"></label></p>';
    echo '<p><label>'.__('Limit', 'downloads-manager').': <input type="text" name="most_downloaded_widgets_limit" value="'.stripslashes($settings['limit']).'"></label></p>';
    echo '<p>'.__('Show Category', 'downloads-manager').': <input type="checkbox" name="most_downloaded_widgets_category" value="true" '.$checked.'></p>';
    $checked = $settings['avarage'] ? 'checked' : '';
    echo '<p>'.__('Show Avarage', 'downloads-manager').': <input type="checkbox" name="most_downloaded_widgets_avarage" value="true" '.$checked.'></p>';
    echo '<input type="hidden" id="most_downloaded_widgets_update" name="most_downloaded_widgets_update" value="1" />';
  }
  
  ### New Download Widget
  function widget_dm_new_download($args) {
    $settings = get_option('widget_dm_new_download');
    extract($args);
    $title = $settings['title'];
    echo $before_widget.$before_title.$title.$after_title;
    dm_get_new_download('<li>', '</li>', $settings['limit'], $settings['avarage'], $settings['category']);
    echo $after_widget;
  }
  
  function widget_dm_new_download_settings() {
    $settings = get_option('widget_dm_new_download');
		if (!is_array($settings))
			$settings = array('title' => __('New Downloads', 'downloads-manager'), 'limit' => 10, 'avarage' => false, 'category' => false);
    if ($_POST['new_download_widgets_update']) {
			$settings['title'] = strip_tags(addslashes($_POST['new_download_widgets_title']));
			$settings['limit'] = intval($_POST['new_download_widgets_limit']);
			$settings['avarage'] = $_POST['new_download_widgets_avarage'];
      $settings['category'] = $_POST['new_download_widgets_category'];
			update_option('widget_dm_new_download', $settings);
		}
    $checked = $settings['category'] ? 'checked' : '';
    echo '<p><label>'.__('Title', 'downloads-manager').': <input type="text" name="new_download_widgets_title" value="'.htmlspecialchars(stripslashes($settings['title'])).'"></label></p>';
    echo '<p><label>'.__('Limit', 'downloads-manager').': <input type="text" name="new_download_widgets_limit" value="'.stripslashes($settings['limit']).'"></label></p>';
    echo '<p>'.__('Show Category', 'downloads-manager').': <input type="checkbox" name="new_download_widgets_category" value="true" '.$checked.'></p>';
    $checked = $settings['avarage'] ? 'checked' : '';
    echo '<p>'.__('Show Avarage', 'downloads-manager').': <input type="checkbox" name="new_download_widgets_avarage" value="true" '.$checked.'></p>';
    echo '<input type="hidden" id="new_download_widgets_update" name="new_download_widgets_update" value="1" />';
  }

  register_sidebar_widget(__('Most Downloaded', 'downloads-manager'), 'widget_dm_top10', 1);
  register_widget_control(__('Most Downloaded', 'downloads-manager'), 'widget_dm_top10_settings', 400, 200);
  
  register_sidebar_widget(__('New Downloads', 'downloads-manager'), 'widget_dm_new_download', 1);
  register_widget_control(__('New Downloads', 'downloads-manager'), 'widget_dm_new_download_settings', 400, 200);
}

### Add file_id to query vars
function DownloadsManager_QueryVars($public_query_vars) {
	$public_query_vars[] = "file_id";
	return $public_query_vars;
}

### Function Activated when an user download a file
function DownloadsManager_DownloadFile() {
	global $wpdb, $user_ID, $table_prefix;
	$id = intval(get_query_var('file_id'));
	if($id > 0) {
    $file = $wpdb->get_row("SELECT id, link, permissions FROM ".$table_prefix."dm_downloads WHERE id='$id'");
    if(!$file) {
      header('HTTP/1.0 404 Not Found');
      header('Refresh: 4; URL='.$_SERVER['PHP_SELF']);
      die(__('Invalid File ID.', 'downloads-manager'));
    }
    if(($file->permissions == 'no' && intval($user_ID) > 0) || $file->permissions == 'yes') {
      $wpdb->query("UPDATE ".$table_prefix."dm_downloads SET clicks = clicks + 1 WHERE id = '$id'");
      header('Location: '.$file->link);
    }
    else
      echo '<script language="JavaScript" type="text/JavaScript">alert("'.__('Sorry, you need to be registered to download this file!','downloads-manager').'");</script>';
  }
}

### When the plugin is activated Install it
if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
  add_action('init', 'DownloadsManager_Install');
}

### Add rewrite role to .htaccess
function DownloadsManager_ModRewriteRule() { 
	add_rewrite_rule('download/([0-9]{1,})/?$', 'index.php?file_id=$matches[1]');
}

### Hooks
add_action('init', 'DownloadsManager_ModRewriteRule'); 
add_action('admin_menu', 'DownloadsManager_Init');
add_action('template_redirect', 'DownloadsManager_DownloadFile');
add_filter('query_vars', 'DownloadsManager_QueryVars');
add_filter('the_content', 'DownloadsManager_CodeReplace');
add_filter('the_content', 'DownloadsManager_DownloadsPage');
add_action('plugins_loaded', 'DownloadsManager_WidgetsInit');

################################## Events ##################################

### Update Download
if(isset($_POST['dm_update'])) {
  if ($_POST['dm_category'] != 0) {
    $link = ($_POST['dm_upload2link'] == 1) ? escape_var($_POST['dm_link2']) : escape_var($_POST['dm_link']);
    if($link != '') {
      if(preg_match('/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $_POST['dm_date'])) {
        $dd = substr($_POST['dm_date'], 0, 2);
        $mm = substr($_POST['dm_date'], 3, 2);
        $yyyy = substr($_POST['dm_date'], 6, 4);
        $date = mktime(0, 0, 0, $mm, $dd, $yyyy);
      }
      $wpdb->query("UPDATE ".$table_prefix."dm_downloads SET name='".escape_var($_POST['dm_name'])."', icon='".escape_var($_POST['dm_icon'])."', description='".escape_var($_POST['dm_description'])."', category='".escape_var($_POST['dm_category'])."', permissions='".escape_var($_POST['dm_permissions'])."', link='".$link."', date='".$date."', clicks='".escape_var($_POST['dm_clicks'])."' WHERE id='".escape_var($_POST['dm_id'])."'");
      $dm_message = '<div class="updated fade" id="message"><p>'.__('Updated Information','downloads-manager').'</p></div>';
    }
    else
      $dm_message = '<div class="updated fade" id="message"><p>'.__('Error! You must choose a file','downloads-manager').'</p></div>';
  }
  else 
    $dm_message = '<div class="updated fade" id="message"><p>'.__('Error! You must choose a category','downloads-manager').'</p></div>';
    
}

### Add Download
if(isset($_POST['dm_add'])) 
  {
    if ($_POST['dm_category'] != 0)
      {
				$date = time();
        $link = $_POST['dm_upload2link'] ? escape_var($_POST['dm_link2']) : escape_var($_POST['dm_link']);
        if($link != '') {
          if(preg_match('/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $_POST['dm_date'])) {
            $dd = substr($_POST['dm_date'], 0, 2);
            $mm = substr($_POST['dm_date'], 3, 2);
            $yyyy = substr($_POST['dm_date'], 6, 4);
            $date = mktime(0, 0, 0, $mm, $dd, $yyyy);
          }
          $wpdb->query("INSERT INTO ".$table_prefix."dm_downloads
          (name, icon, category, description, permissions, date, link, clicks)
          VALUES
          ('".escape_var($_POST['dm_name'])."', '".escape_var($_POST['dm_icon'])."', '".escape_var($_POST['dm_category'])."', '".escape_var($_POST['dm_description'])."', '".escape_var($_POST['dm_permissions'])."', '".$date."', '".$link."', '".escape_var($_POST['dm_clicks'])."')");
          $dm_message = '<div class="updated fade" id="message"><p>'.__('Download Added','downloads-manager').'</p></div>';
        }
        else
          $dm_message = '<div class="updated fade" id="message"><p>'.__('Error! You must choose a file','downloads-manager').'</p></div>';
      }
    else
      $dm_message = '<div class="updated fade" id="message"><p>'.__('Error! You must choose a category','downloads-manager').'</p></div>';
  }

### Upload File
if(isset($_POST['dm_upload'])) {

  $file_name = $_FILES["upfile"]["name"];

  if(@is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
    move_uploaded_file($_FILES["upfile"]["tmp_name"], $downloadsdir.$file_name) or die(__('Can\'t find destination folder','downloads-manager'));
    $dm_upload_url = $plugin_url."/upload/".$file_name;
    $dm_message = '<div class="updated fade" id="message"><p>'.__('File Uploaded','downloads-manager').'</p></div>';
  } 
  else {
    $dm_message = '<div class="updated fade" id="message"><p>'.__('Error during upload of','downloads-manager').' '.$_FILES["upfile"]["name"].'</p></div>';
  }
}

### Remove or Delete Download
if(isset($_POST['dm_remove'], $_POST['dm_id_remove'], $_POST['dm_name_remove'])) {
  $wpdb->query("DELETE FROM ".$table_prefix."dm_downloads WHERE id='".escape_var($_POST['dm_id_remove'])."'");
  if($_POST['dm_remove_file_too'] == 'remove')
    unlink('../wp-content/plugins/downloads-manager/upload/'.$_POST['dm_name_remove']);
  $dm_message = '<div class="updated fade" id="message"><p>'.__('File Removed!','downloads-manager').'</p></div>';
}

### Edit Templates
if(isset($_POST['dm_template_edit'])) {
  if(isset($_POST['singleTemplate']) || isset($_POST['pageTemplate'])) {
    if (is_writeable('../wp-content/plugins/downloads-manager/single-download-template.tpl')) {
      $handle1 = fopen('../wp-content/plugins/downloads-manager/single-download-template.tpl', 'w+');
      fwrite($handle1, stripslashes($_POST['singleTemplate']));
      fclose($handle1);
    }
    else
      $dm_message = '<div class="updated fade" id="message"><p>'.__('Error... Can\' write file page-download-template.tpl!','downloads-manager').'</p></div>';
    if (is_writeable('../wp-content/plugins/downloads-manager/page-download-template.tpl')) {
      $handle2 = fopen('../wp-content/plugins/downloads-manager/page-download-template.tpl', 'w+');
      fwrite($handle2, stripslashes($_POST['pageTemplate']));
      fclose($handle2);
    }
    else
      $dm_message = '<div class="updated fade" id="message"><p>'.__('Error... Can\' write file page-download-template.tpl!','downloads-manager').'</p></div>';
  }
  $dm_message = '<div class="updated fade" id="message"><p>'.__('Templates Updated!','downloads-manager').'</p></div>';
}


### Add Category
if(isset($_POST['dm_add_category']) && isset($_POST['dm_cat_name'])) {
  $wpdb->query("INSERT INTO ".$table_prefix."dm_category (name) VALUES ('".escape_var($_POST['dm_cat_name'])."')");
  $dm_message = '<div class="updated fade" id="message"><p>'.__('Added Category!','downloads-manager').' '.$_POST['dm_cat_name'].'</p></div>';
}


### Edit Category
if(isset($_POST['cat_id']) && isset($_POST['cat_newname']) && $_POST['cat_newname'] != "") {
  $wpdb->query("UPDATE ".$table_prefix."dm_category SET name = '".escape_var($_POST['cat_newname'])."' WHERE id = '".escape_var($_POST['cat_id'])."'");
  $dm_message = '<div class="updated fade" id="message"><p>'.__('Category Updated!','downloads-manager').'</p></div>';
}

### Delete Category
if(isset($_POST['cat_deleteid']) && isset($_POST['cat_deletename']) && $_POST['cat_deletename'] != "") {
  $wpdb->query("DELETE FROM ".$table_prefix."dm_category WHERE id='".escape_var($_POST['cat_deleteid'])."'");
  $dm_message = '<div class="updated fade" id="message"><p>'.sprintf(__('Category "%s" Deleted!','downloads-manager'), $_POST['cat_deletename']).'</p></div>';
}

### Uninstall Plugin
if(isset($_POST['dm_uninstall'])) {
  if($_POST['dm_sure'] == 'yes') {
    foreach($dm_tables as $dm_table)
      $wpdb->query("DROP TABLE ".$dm_table);
    $dm_message = '<div class="updated fade" id="message"><p>'.__('Uninstall Completed','downloads-manager').'</p></div>';
    $dm_uninstall = true;
  }
  else
    $dm_message = '<div class="updated fade" id="message"><p>'.__('Check the "I\'m sure" field','downloads-manager').'</p></div>';
}

?>

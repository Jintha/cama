<?php
/*
Plugin Name:File Uploader
Description: Upload files according to category and display in user side.
Author:Linish , India
Version:1.0 Beta
Author URI:http://www.altd.in
*/
ob_start();
function jal_install()
{
global $wpdb;
$table_name_cat = $wpdb->prefix."file_cat";
$table_name_files = $wpdb->prefix."files";
if(($wpdb->get_var("show tables like '$table_name_cat'") != $table_name_cat)&&($wpdb->get_var("show tables like '$table_name_files'") != $table_name_files)) {
$sql_cat = "CREATE TABLE " . $table_name_cat . " (
  `cat_id` int(11) NOT NULL auto_increment,
  `category_name` varchar(32) NOT NULL,
  `dir_name` varchar(32) NOT NULL,
  `file_count` int(10) NOT NULL default '0',
  `icon_name` varchar(50) NOT NULL default 'default.png',
  PRIMARY KEY  (`category_name`,`cat_id`),
  KEY `cat_id` (`cat_id`)
);";	
$sql_files = "CREATE TABLE " . $table_name_files . " (
  `file_id` int(10) NOT NULL auto_increment,
  `file_name` text NOT NULL,
  `display_name` text NOT NULL,
  `description` text NOT NULL,
  `cat_id` int(10) NOT NULL,
  `download_count` int(10) NOT NULL default '0',
  `icon_name` varchar(100) NOT NULL,
  PRIMARY KEY  (`file_id`)
) ;";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql_cat);
dbDelta($sql_files);
}
}
function jal_uninstall()
{
global $wpdb;
$table_name_cat = $wpdb->prefix."file_cat";
$table_name_files = $wpdb->prefix."files";
if(($wpdb->get_var("show tables like '$table_name_cat'") == $table_name_cat)&&($wpdb->get_var("show tables like '$table_name_files'") == $table_name_files)) {
$sql_cat = "DROP TABLE " . $table_name_cat.",".$table_name_files .";";
$wpdb->query($sql_cat);
}
}
function file_upload_add_category()
{
 include("file_upload_add_cat.php");
}
function file_upload_view_category()
{
 include("file_upload_view_cat.php");	
}
function file_upload_file()
{
 include("file_upload_file.php");
}
function file_upload_view_file()
{
 include("file_upload_view_file.php");
}
function file_upload_settings()
{
 include("settings.php");
}
function show_files($type)
{
 if($type == "ALL")
 {
  include("file_upload_show_all.php");
 }
 if($type == "SIDEBAR")
 {
  include("file_upload_show_sidebar.php");
 }
 
}
function file_upload_show($content)
{
 //echo "<h2>".__('Add Category')."</h2>";
 $results = array();

	preg_match_all("/\[\s?fileuploader \s?(.*)\s?\]/", $content, $results);
	
	//print_r($results);

	$i = 0;
	foreach ($results[0] as $r) {

		$content = str_replace($r, show_files($results[1][$i]), $content);
		$i++;
	}

	return $content;
}
function file_upload_actions()
{
	add_menu_page("File Uploader","File Uploader",8,__FILE__,"file_upload_view_category");
	add_submenu_page(__FILE__,"View Category","View Category",8,"View Category","file_upload_view_category");
	add_submenu_page(__FILE__,"Add Category","Add Category",8,"Add Category","file_upload_add_category");	
	add_submenu_page(__FILE__,"Upload File","Upload File",8,"Upload File","file_upload_file");
	add_submenu_page(__FILE__,"View Files","View Files",8,"View Files","file_upload_view_file");
	add_submenu_page(__FILE__,"File Uploader Settings","File Uploader Settings",8,"File Uploader Settings","file_upload_settings");
}

//jal_install();
register_activation_hook(__FILE__,'jal_install');
register_deactivation_hook(__FILE__,'jal_uninstall');
add_filter('the_content', 'file_upload_show');
add_action('admin_menu','file_upload_actions');
?>
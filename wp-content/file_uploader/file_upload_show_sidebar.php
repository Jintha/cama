<?php
//Show Categories
global $wpdb;
$table_name_categories = $wpdb->prefix."file_cat";
$select_categories = "SELECT * FROM ".$table_name_categories." ORDER BY category_name";
$result_cat = $wpdb->get_results($select_categories);
foreach($result_cat as $cat)
{
 $category_name = $cat->category_name;
 $page_id= $_GET['page_id'];
 $cat_id = $cat->cat_id;
 $file_count_sidebar = $cat->file_count;
 $dir_name = $cat->dir_name;
 $icon_thumb_name = $cat->icon_name;
 $icon_folder = substr(get_option('icon_thumb_folder'),strpos(get_option('icon_thumb_folder'),'/',1)+1);
 $icon_thumb_path = get_option('blog_url').$icon_folder.$icon_thumb_name ;
 //Build the HTML code
 $retval .= '<div class="file_uploader_list" style="font-size:12px;" align="left">';
 $retval .= '<a href="'.$blog_url.'?page_id='.$page_id.'&cat_id='.$cat_id.'" style="text-decoration:none;">';
 $retval .= '<img src="'.$icon_thumb_path.'" border="0"/>';
 $retval .= $category_name."(".$file_count_sidebar.")";
 $retval .= '</a></div>';
}
 echo  $retval;
?>



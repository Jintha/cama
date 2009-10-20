<?php
include ('ps_pagination.php');
$file_id = $_POST['file_id'];
$cat_id = $_POST['cat_id'];
$function = $_POST['function'];
$display_name = addslashes($_REQUEST['display_name']);
$description = addslashes($_REQUEST['description']);

if($file_id !='')
{
 global $wpdb;
 $table_name = $wpdb->prefix."files";
 $table_count = $wpdb->prefix."file_cat";
 $default_path = get_option('upload_folder'); // Default path
 switch($function)
 {
  case 'delete':
   $select_query = "SELECT file_name FROM ".$table_name." WHERE file_id=$file_id";
   $result_query = $wpdb->get_var($select_query);
   $select_count = "SELECT file_count,dir_name FROM ".$table_count." WHERE cat_id=$cat_id";
   $result_count = $wpdb->get_results($select_count);
   foreach ($result_count as $counter)
   {
    $count_new = $counter->file_count - 1;
	$dir_name = $counter->dir_name;
   }
   $delete_folder_path = $default_path.$dir_name."/".$result_query;
   $result_delete = unlink($delete_folder_path);
   if($result_delete)
   {
   $update_query = "UPDATE ".$table_count." SET file_count=$count_new WHERE cat_id=$cat_id";
   $wpdb->query($update_query);
   $delete_query = "DELETE FROM ".$table_name." WHERE file_id=$file_id";
   $result = $wpdb->query($delete_query);
   if($result)
   {
     _e('<div class="updated"><p><strong>File Deleted</strong></p></div>');
   }
   else
   {
    _e('<div class="error"><p><strong>Error in deleting !!!</strong></p></div>');
   }
   }
   else
   {
    _e('<div class="error"><p><strong>Error in file deleting !!!</strong></p></div>');
   }
  break;
  case 'update':
   $update_query = "UPDATE ".$table_name." SET display_name='".$display_name."', description='".
   $description."' WHERE file_id=$file_id";
   $res = $wpdb->query($update_query);
   if($res )
   {
    _e('<div class="updated"><p><strong>Details Name Updated</strong></p></div>');
   }
   else
   {
    _e('<div class="error"><p><strong>Error in Updating</strong></p></div>');
   }
  break;
 }
/* global $wpdb;
 $table_name = $wpdb->prefix."files";
 $delete_query = "DELETE FROM ".$table_name." WHERE file_id=$file_id";
 echo $delete_query;*/
}
?>

<div class="wrap">
<?php echo "<h2>".__('Files List')."</h2>";?>
<?php 
global $wpdb;
$table_name = $wpdb->prefix."files";
$table_category = $wpdb->prefix."file_cat";
$i =0;
$nos_pages_admin = get_option('admin_max'); // No od datas per page
$nos_links_admin = get_option('admin_max_links'); // No of links to b displayed
$select_query = "SELECT * FROM ".$table_name." ORDER BY cat_id";
$passed_link = get_option('blog_url')."wp-admin/admin.php?page=View%20Files";
	//Create a PS_Pagination object
	$pager = new PS_Pagination($wpdb,$select_query,$nos_pages_admin ,$nos_links_admin,$passed_link);
	
	//The paginate() function returns a mysql result set 
	$result = $pager->paginate();

//$result = $wpdb->get_results($select_query );
//print_r($result);
?>
<table width="100%" cellpadding="10" cellspacing="10" border="0">
<tr>
<th>Nos.</th>
<th>Name</th>
<th>Times Downloaded</th>
<th>Category</th>
<th>Display Name</th>
<th>Description</th>
<th>Options</th>
</tr>
<?php
foreach ($result as $row){
?>
<form name="edit_upload<?php _e($i+1);?>" method="post" action="<?php echo str_replace('%7E','~',$_SERVER['REQUEST_URI']);?>" enctype="multipart/form-data">
<tr>
<td align="center" width="50">
<?php _e(++$i); ?>
</td>
<td  width="220">
<?php _e($row->file_name); ?>
</td>
<td  width="150" align="center">
<?php _e($row->download_count); ?>
</td>
<td  width="150" align="center">
<?php
$category_id = $row->cat_id;
$select_query_category = "SELECT category_name FROM ".$table_category." WHERE cat_id=$category_id";
$category_name = $wpdb->get_var($select_query_category);
_e($category_name); 
 ?>
</td>
<td width="20%">
<input type="text" id="display_name" name="display_name" value="<?php _e(stripslashes($row->display_name)); ?>" size="15" />
<input type="hidden" value="<?php _e($row->file_id);?>" name="file_id" />
<input type="hidden" value="<?php _e($row->cat_id);?>" name="cat_id" />
</td>
<td >
<textarea id="description" name="description" cols="15" ><?php _e(stripslashes($row->description)); ?></textarea>
</td>
<td width="20%">
<p class="submit">
<input type="submit" value="delete" name="function" />
<input type="submit" value="update" name="function" />
</p>
</td>
</tr>
</form>
<?php } ?>
<tr>
<td colspan="6" >
<table width="100%" align="center" height="45">
<tr>
<td width="15%" align="center" style="border:1px solid #cccccc;">
<?php
//Display the link to previous page: <<
echo $pager->renderPrev();
?>
</td>
<td width="70%" align="center" style="border:1px solid #cccccc;">
<?php
//Display page links: 1 2 3
echo $pager->renderNav();
?>
</td>
<td width="15%" align="center" style="border:1px solid #cccccc;">
<?php
//Display the link to next page: >>
echo $pager->renderNext();
?>
</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
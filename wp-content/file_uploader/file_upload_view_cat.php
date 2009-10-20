<?php
$cat_id = $_GET['cat_id'];
if($cat_id != '')
{
 global $wpdb;
 $table_name = $wpdb->prefix."file_cat";
 $select_query = "SELECT dir_name FROM ".$table_name." WHERE cat_id=$cat_id";
 $default_path =get_option('upload_folder');
 $dir_name = $wpdb->get_var($select_query );
 rmdir($default_path.$dir_name);
 $delete_query = "DELETE FROM ".$table_name." WHERE cat_id = $cat_id";
 $res = $wpdb->query($delete_query);
?>
<div class="updated"><p><strong><?php _e('Category Deleted.');?></strong></p></div>
<?php
}
?>

<div class="wrap">
<?php echo "<h2>".__('View Category')."</h2>";?>
<?php
global $wpdb;
$table_name = $wpdb->prefix."file_cat";
$select_query = "SELECT * FROM ".$table_name."";
$result = $wpdb->get_results($select_query);
?>
<table width="500" align="left">
<tr>
<th>Category Name</th>
<th width="300"> Options</th>
</tr>
<?php foreach ($result as $res): ?>
<tr>
<td  style="padding-left:40px;">
<?php echo $res->category_name; ?>
</td>
<td align="center">
<a href="admin.php?page=Add Category&cat_id=<?php echo $res->cat_id;?>">Edit</a>
<a href="admin.php?page=View Category&cat_id=<?php echo $res->cat_id;?>">Delete</a>
</td>
</tr>
<?php endforeach; ?>
</table>
</div>

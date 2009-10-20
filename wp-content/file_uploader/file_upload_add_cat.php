<?php
$category_id = $_GET['cat_id']; // Category Id
if($_POST['file_upload_add_cat']== 'Y'){
if($category_id == '')
{
//Form data sent
$category_name = $_POST['fomr_upld_cat_name'];
if($category_name == '')
{
 _e('<div class="error"><p><strong>Category name should\'nt be empty</strong></p></div>');
}
else
{
$icon_name = strtolower($_FILES['fomr_upld_cat_icon']['name']);
$default_icon_path = get_option('icon_folder');
$default_thumb_folder = get_option('icon_thumb_folder');
if($icon_name == '')
{
$icon_name = "default.png";
}
else
{
	$new_file_name = $icon_name;
	move_uploaded_file($_FILES['fomr_upld_cat_icon']['tmp_name'],$default_icon_path.$new_file_name);
	copy($default_icon_path.$new_file_name,$default_thumb_folder.$new_file_name);
	include('image_resizer.php');
    resize_image($default_icon_path,64,$new_file_name);
	resize_image($default_thumb_folder,32,$new_file_name);
}

global $wpdb;
$default_path = get_option('upload_folder'); 
$default_icon_path = get_option('icon_folder');
$table_name = $wpdb->prefix."file_cat";
$dir_name = strtolower(str_replace(" ",'_',$category_name));
$select_query = "SELECT * FROM ".$table_name." WHERE category_name='$category_name'";
$result = $wpdb->query($select_query);
if($result == 0)
{
mkdir($default_path.$dir_name,0777);
$insert_query = "INSERT INTO ".$table_name."(category_name,dir_name) VALUES ('$category_name','$dir_name')";
$wpdb->query($insert_query);
?>
<div class="updated"><p><strong><?php _e('Category Value Saved.');?></strong></p></div>
<?php
}
else
{
?>
<div class="error"><p><strong><?php _e('Category Value Already Exists.');?></strong></p></div>
<?php
}
}
}
else
{
$category_name = $_POST['fomr_upld_cat_name'];
$icon_name = strtolower($_FILES['fomr_upld_cat_icon']['name']);
global $wpdb;
$default_path = get_option('upload_folder'); // Default path
$default_icon_path = get_option('icon_folder');
$default_thumb_folder = get_option('icon_thumb_folder');
$dir_name_new = strtolower(str_replace(" ",'_',$category_name));
$table_name = $wpdb->prefix."file_cat";
$select_query = "SELECT dir_name FROM ".$table_name." WHERE cat_id=$category_id";
$dir_name_old = $wpdb->get_var($select_query );
rename($default_path.$dir_name_old,$default_path.$dir_name_new);
$dir_name = $dir_name_new;
//File upload part begins
if($icon_name != '')
{
 $select_query = "SELECT icon_name FROM ".$table_name." WHERE cat_id=$category_id";
 $icon_name_delete = $wpdb->get_var($select_query);
 if($icon_name_delete !="default.png")
 {
 	$deletepath = $default_icon_path.$icon_name_delete;
	$delete_thumb = $default_thumb_folder.$icon_name_delete;
	if(file_exists($deletepath))
	{
	  unlink($deletepath);
	}
	if(file_exists($delete_thumb))
	{
	  unlink($delete_thumb);
	}
 }
	$new_file_name = $icon_name;
	move_uploaded_file($_FILES['fomr_upld_cat_icon']['tmp_name'],$default_icon_path.$new_file_name);
	copy($default_icon_path.$new_file_name,$default_thumb_folder.$new_file_name);
	include('image_resizer.php');
    resize_image($default_icon_path,64,$new_file_name);
	resize_image($default_thumb_folder,32,$new_file_name);
	$update_query = "UPDATE ".$table_name." SET category_name='$category_name', dir_name='$dir_name' 
	,icon_name='$new_file_name' WHERE cat_id=$category_id";
	$res = $wpdb->query($update_query);
}
else
{
// File upload part ends
$update_query = "UPDATE ".$table_name." SET category_name='$category_name', dir_name='$dir_name' WHERE cat_id=$category_id";
$res = $wpdb->query($update_query);
}
if($res)
{
?>
<div class="updated"><p><strong><?php _e('Category Value Updated.');?></strong></p></div>
<?php
}
else
{?>
<div class="error"><p><strong><?php _e('Updation Failed.'.mysql_error());?></strong></p></div>
<?php
}}}
if($category_id == '')
{
 $category_name = '';
}
else
{
global $wpdb;
$table_name = $wpdb->prefix."file_cat"; // To get table name
$select_query = "SELECT category_name FROM ".$table_name." WHERE cat_id=".$category_id."";
$category_name = $wpdb->get_var($select_query);
}
?>

<div class="wrap">
<?php echo "<h2>".__('Add Category')."</h2>";?>

<form name="add_cat" method="post" action="<?php echo str_replace('%7E','~',$_SERVER['REQUEST_URI']);?>" enctype="multipart/form-data">
<input type="hidden" name="file_upload_add_cat" value="Y" />
<input type="hidden" name="category_id" value="<?php echo $category_id;?>" />

<p><?php _e("Category Name:");?><input type="text" name="fomr_upld_cat_name" value="<?php echo $category_name;?>" size="20" maxlength="32" /><?php _e("ex:Computer");?></p>
<p><?php _e("Category Icon&nbsp;&nbsp;:");?><input type="file" name="fomr_upld_cat_icon" value="" size="20" maxlength="32" /><?php _e("ex:Use 64 X 64 icons");?></p>

	<p class="submit">
	<input type="submit" name="Submit" value="<?php if($category_id == ''){_e('Add Category','file_upld_trdom');}else{_e('Update Category','file_upld_trdom');}?>" />
	</p>

</form>
</div>
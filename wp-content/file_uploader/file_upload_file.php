<?php
if($_POST['isposted'] == 'Yes' && $_FILES['upload_file']['name'] != "" && 
$_POST['display_name'] != "")
{
 global $wpdb;
 $table_name_select = $wpdb->prefix."file_cat";
 $table_name = $wpdb->prefix."files";
 $cat_id = $_POST['category'];
 $file_name = $_FILES['upload_file']['name'];
 $display_name = addslashes($_POST['display_name']);
 $description = addslashes($_POST['description']);
 $file_size = ceil($_FILES['upload_file']['size']/1024/1024);
 $max_file_size = get_option('file_size');
 $select_query = "SELECT dir_name FROM ".$table_name_select." WHERE cat_id=$cat_id";
 $dir_name = $wpdb->get_var($select_query);
 $icon_path ="pdf.png";
 
 //File size check
 if($file_size > $max_file_size)
 {
  _e('<div class="error"><p><strong>Exceeded Max File Size !!1</strong></p></div>');
 }
 else
 {

 //Check extension
 $pos = strripos($file_name, '.', 1); 
 $ext = substr($file_name,($pos+1),3);
 if($ext != "pdf")
 {
  _e('<div class="error"><p><strong>Only PDF files can be uploaded</strong></p></div>'); //_e() or echo can be used
 }
 else
 { 
 //Upload file Section
 $upload_folder = get_option('upload_folder');
 $target_path = $upload_folder.$dir_name."/";
 $target_path = $target_path . basename( $_FILES['upload_file']['name']); 
 if(!file_exists($target_path))
 {
  if(move_uploaded_file($_FILES['upload_file']['tmp_name'], $target_path))
  {
   //Insert to database
   $insert_query = "INSERT INTO ".$table_name." (file_name,cat_id,display_name,description,icon_name) VALUES ('$file_name',$cat_id,'$display_name','$description','$icon_path')";
   $wpdb->query($insert_query) or die(mysql_error());
   //Select count
   $select_query = "SELECT file_count FROM ".$table_name_select." WHERE cat_id=$cat_id";
   $counter = $wpdb->get_var($select_query);
   $counter = $counter + 1; // Incrent count
   //Update count
   $update_query = "UPDATE ".$table_name_select." SET file_count=$counter WHERE cat_id=$cat_id";
   $wpdb->query($update_query)or die(mysql_error());
   _e('<div class="updated"><p><strong>File Uploaded Succesfully.</strong></p></div>');
  }
  else
  {
   _e('<div class="error"><p><strong>File Upload Error</strong></p></div>');
  }
 }
 else
 {
  _e('<div class="error"><p><strong>File Exists !!!</strong></p></div>');
 }
 
 } // End of else of extension checking
 } // End of else of max file size checking
}
if($_POST['isposted'] == 'Yes' && $_FILES['upload_file']['name'] == "")
{
 _e('<div class="error"><p><strong>File name can\'t be EMPTY !!!</strong></p></div>');
}
 if($_POST['isposted'] == 'Yes' && $_POST['display_name'] == "")
 {
  _e('<div class="error"><p><strong>Display name can\'t be EMPTY !!!</strong></p></div>');
 }
?>

<div class="wrap">
<?php echo "<h2>".__('Upload File')."</h2>";?>
<form name="upload_frm" method="post" action="<?php echo str_replace('%7E','~',$_SERVER['REQUEST_URI']);?>" enctype="multipart/form-data">
<input type="hidden" value="Yes" name="isposted" />
<table width="550" cellpadding="5" cellspacing="10" border="0">
<tr>
<td align="left" >
<?php _e("Select Category");?>
</td>
<td align="left">
<select name="category">
<?php
global $wpdb;
$table_name = $wpdb->prefix."file_cat"; // To get table name
//Select Categories
$select_query = "SELECT cat_id,category_name FROM ".$table_name."";
$row = $wpdb->get_results($select_query);
foreach ($row as $rows):
?>
<option value="<?php _e($rows->cat_id);?>"><?php _e($rows->category_name);?></option>
<?php
endforeach;
?>
</select>
</td>
</tr>
<tr>
<td align="left">
<?php _e("Select File"); ?>
</td>
<td align="left">
<input type="file" name="upload_file" />&nbsp;&nbsp; <?php _e("(Only pdf files)"); ?>
</td>
</tr>
<tr>
<td align="left">
<?php _e("Display Name"); ?>
</td>
<td align="left">
<input type="text" name="display_name" value="" />
</td>
</tr>
<tr>
<td align="left" valign="top">
<?php _e("Description"); ?>
</td>
<td align="left">
<textarea name="description">Default file description</textarea>
</td>
</tr>
<tr>
<td align="center" colspan="2">
<p class="submit">
<input type="submit" value="Upload File" name="Submit"/>
</p>
</td>
</tr>
</table>
</form>
</div>
<?php
if($_POST['file_upload_send'] == "Y")
{
 $upload_folder = $_POST['upload_folder'];
 update_option('upload_folder',$upload_folder);
 $icon_folder   = $_POST['icon_folder'];
 update_option('icon_folder',$icon_folder);
 $icon_thumb_folder = $_POST['icon_thumb_folder'];
 update_option('icon_thumb_folder',$icon_thumb_folder);
 $blog_url = $_POST['blog_url'];
 update_option('blog_url',$blog_url);
 $admin_max = $_POST['admin_max'];
 if($admin_max == "" || $admin_max == 0)
 {
  $admin_max = 3;
 }
 update_option('admin_max',$admin_max);
 $admin_max_links = $_POST['admin_max_links'];
 if($admin_max_links == "" || $admin_max_links == 0)
 {
  $admin_max_links = 3;
 }
 update_option('admin_max_links',$admin_max_links);
 $user_max = $_POST['user_max'];
 if($user_max == "" || $user_max == 0)
 {
  $user_max = 3;
 }
 update_option('user_max',$user_max);
 $user_max_links = $_POST['user_max_links'];
 if($user_max_links == "" || $user_max_links == 0)
 {
  $user_max_links = 3;
 } 
 update_option('user_max_links',$user_max_links);
 $file_size = $_POST['file_size'];
 $pos =strpos(ini_get('upload_max_filesize'),"M",1);
 $available_size = substr(ini_get('upload_max_filesize'),0,$pos);
 if($file_size <= $available_size)
 {
  update_option('file_size',$file_size);
  _e('<div class="updated"><p><strong>Settings Saved.</strong></p></div>');
 }
 else
 {
  _e('<div class="error"><p><strong>Exceeded Max Upload Limit.Max Avalable Size is'.$available_size.'&nbsp;MB</strong></p></div>');
 }
}
else
{
 $upload_folder = get_option('upload_folder');
 $icon_folder = get_option('icon_folder');
 $icon_thumb_folder = get_option('icon_thumb_folder');
 $file_size = get_option('file_size');
 $blog_url = get_option('blog_url');
 $admin_max = get_option('admin_max'); 
 $user_max = get_option('user_max');
 $admin_max_links = get_option('admin_max_links');
 $user_max_links = get_option('user_max_links');
 if($upload_folder == '')
 {
  $upload_folder  = "../upload_files/";
  update_option('upload_folder',$upload_folder);
 }
 if($icon_folder == '')
 {
  $icon_folder = "../upload_files/icons/";
  update_option('icon_folder',$icon_folder);
 }
 if($icon_thumb_folder == '')
 {
  $icon_thumb_folder = "../upload_files/icons/thumb/";
  update_option('icon_thumb_folder',$icon_thumb_folder);
 }
 if($file_size == '')
 {
  $pos =strpos(ini_get('upload_max_filesize'),"M",1);
  $file_size = substr(ini_get('upload_max_filesize'),0,$pos);
  update_option('file_size',$file_size);
 }
 if($blog_url == '')
 {
  $path = "http://".$_SERVER['HTTP_HOST'];//"http://localhost/wordpress/";
  $blog_url = $path;
  update_option('blog_url',$blog_url);
 }
 if($admin_max == '')
 {
  $admin_max = 10;
  update_option('admin_max',$admin_max);
 }
 if($admin_max_links == '')
 {
  $admin_max_links = 4;
  update_option('admin_max_links',$admin_max_links);
 }
 if($user_max == '')
 {
  $user_max = 10;
  update_option('user_max',$user_max);
 }
 if($user_max_links == '')
 {
  $user_max_links = 4;
  update_option('user_max_links',$user_max_links);
 }
}
?>
<div class="wrap">
<?php echo "<h2>".__('File Uploader Settings')."</h2>";?>
<form name="settings" method="post" action="<?php echo str_replace('%7E','~',$_SERVER['REQUEST_URI']);?>" 
enctype="multipart/form-data">
<input type="hidden" name="file_upload_send" value="Y" />
<?php echo "<h4>".__('Folder Settings')."</h4>";?>
<table width="70%" cellpadding="5" cellspacing="10">
<tr>
<td align="left">
<?php _e("Upload Folder");?>
</td>
<td>
<input type="text" name="upload_folder" id="upload_folder" value="<?php echo $upload_folder;?>" size="20" />&nbsp;&nbsp;<?php _e("Ex:foldername/");?>
</td>
</tr>
<tr>
<td>
<?php _e("Icon Folder");?>
</td>
<td>
<input type="text" name="icon_folder" id="icon_folder" value="<?php echo $icon_folder;?>" size="20" />&nbsp;&nbsp;<?php _e("Ex:foldername/");?>
</td>
</tr>
<tr>
<td>
<?php _e("Icon Thumb Folder");?>
</td>
<td>
<input type="text" name="icon_thumb_folder" id="icon_thumb_folder" value="<?php echo $icon_thumb_folder;?>" size="20" />&nbsp;&nbsp;<?php _e("Ex:foldername/");?>
</td>
</tr>
<tr>
<td>
<?php _e("Blog URL");?>
</td>
<td>
<input type="text" name="blog_url" id="blog_url" value="<?php echo $blog_url;?>" size="20" />&nbsp;&nbsp;<?php _e("Ex:http://www.example.com/");?>
</td>
</tr>
<tr>
<td>
<?php _e("Max File Size");?>
</td>
<td>
<input type="text" name="file_size" id="file_size" value="<?php echo $file_size;?>" size="20" maxlength="10" />&nbsp;&nbsp;<?php _e("MB");?>
</td>
</tr>
<tr>
<td>
<?php echo "<h4>".__('Pagination Settings')."</h4>";?>
</td>
</tr>
<tr>
<td>
<?php _e("Nos Pages(Admin)");?>
</td>
<td>
<input type="text" name="admin_max" id="admin_max" value="<?php echo $admin_max;?>" size="20" maxlength="10" />&nbsp;&nbsp;<?php _e("Default:10 Per Page");?>
</td>
</tr>
<tr>
<td>
<?php _e("Nos Links(Admin)");?>
</td>
<td>
<input type="text" name="admin_max_links" id="admin_max_links" value="<?php echo $admin_max_links;?>" size="20" maxlength="10" />&nbsp;&nbsp;<?php _e("Default:3 Links");?>
</td>
</tr>
<tr>
<td>
<?php _e("Nos Pages(User)");?>
</td>
<td>
<input type="text" name="user_max" id="user_max" value="<?php echo $user_max;?>" size="20" maxlength="10" />&nbsp;&nbsp;<?php _e("Default:10 Per Page");?>
</td>
</tr>
<tr>
<td>
<?php _e("Nos Links(User)");?>
</td>
<td>
<input type="text" name="user_max_links" id="user_max_links" value="<?php echo $user_max_links;?>" size="20" maxlength="10" />&nbsp;&nbsp;<?php _e("Default:3 Links");?>
</td>
</tr>
<tr>
<td colspan="2" align="center">
<p class="submit">
<input type="submit" value="Update Settings" />
</p>
</td>
</tr>
</table>
</form>
</div>
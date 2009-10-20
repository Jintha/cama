<?php
include ('ps_pagination.php');
if($_GET['cat_id'] == '' && $_GET['file_id'] == '')
{
//Random Files
global $wpdb;
$table_name_files = $wpdb->prefix."files";
$table_name_cat = $wpdb->prefix."file_cat";
$select_files = "SELECT file_id,file_name,display_name,icon_name,download_count FROM ".$table_name_files." ORDER BY RAND() LIMIT 0,3";
$result_files = $wpdb->get_results($select_files);
$select_cat = "SELECT * FROM ".$table_name_cat." ORDER BY category_name";
$result_cat = $wpdb->get_results($select_cat);
$counter =0;
$counter_cat = 0;
$page_id = $_GET['page_id'];
$blog_url = get_option('blog_url');
?>
<table width=" 450" align="left" cellpadding="0" cellspacing="10" border="0">
<tr>
<th align="left" colspan="3">
<strong>Random files</strong>
</th>
</tr>
<tr style="font-size: 12px;">
<?php
foreach($result_files as $files)
{
 $display_name = stripslashes($files->display_name);
 $file_name = $files->file_name;
 $file_id = $files->file_id;
 $down_count = $files->download_count;
 $icon_name = $files->icon_name;
 $icon_folder = substr(get_option('icon_folder'),strpos(get_option('icon_folder'),'/',1)+1);
 $icon_path = $blog_url.$icon_folder.$icon_name ;
 $counter = $counter + 1;
 /* Find whether the permalinks is enabled or not begins*/
 $request_uri = $_SERVER['REQUEST_URI'];
 $position_qmark = strpos($request_uri,"?",'1');
 if($position_qmark == '')
 {
  $concat = "?";
 }
 else
 {
  $concat = "&";
 }
  /* Find whether the permalinks is enabled or not ends*/
?>
<td >
<a href="<?php _e($_SERVER['REQUEST_URI']); _e($concat);?>file_id=<?php _e($file_id);?>" style="text-decoration:none;">
<?php /*page_id=<?php _e($page_id);?>& _e($blog_url)*/?>
<img src="<?php _e($icon_path)?>"  border="0"/><br />
<?php
 _e($display_name);
 _e("(".$down_count.")");
 ?>
 </a>
</td>
<?php
if($counter > 1) { _e('</tr><tr style="font-size: 12px;">'); $counter = 0; }
 }
 if($counter % 2 == 1)
 {
  _e("<td>&nbsp;</td>");
 }
  ?>
</tr>
</table>

<table width=" 450" align="left" cellpadding="0" cellspacing="10" border="0" >
<tr>
<th align="left" colspan="3">
<strong>Categories</strong>
</th>
</tr>
<tr style="font-size: 12px;">
<?php
foreach($result_cat as $cat)
{
 $category_name = $cat->category_name;
 $cat_id = $cat->cat_id;
 $file_count = $cat->file_count;
 $dir_name = $cat->dir_name;
 $icon_name = $cat->icon_name;
 $icon_folder = substr(get_option('icon_folder'),strpos(get_option('icon_folder'),'/',1)+1);
 $icon_path = $blog_url.$icon_folder.$icon_name ;
 $counter_cat = $counter_cat + 1;
?>
<td >
<?php $_REQUEST['file_id'];?>
<?php
/*_e('<a href="'.$blog_url.'?page_id='.$page_id.'&cat_id='.$cat_id.'" style="text-decoration:none;"><img src="'.$icon_path.'" border="0"/><br />');*/
_e('<a href="'.$_SERVER['REQUEST_URI'].$concat.'cat_id='.$cat_id.'" style="text-decoration:none;"><img src="'.$icon_path.'" border="0"/><br />');
 _e($category_name);
 _e("(".$file_count.")</a>");
 ?>
 </a>
</td>
<?php
if($counter_cat > 1) { _e('</tr><tr style="font-size: 12px;">'); $counter_cat = 0; }
 }
 if($counter_cat % 2 == 1)
 {
  _e("<td>&nbsp;</td>");
 }
  ?>
</tr>
</table>
<?php 
}
if($_GET['cat_id'] != '' && $_GET['file_id'] == '')
{
//Display Files
global $wpdb;
$cat_id = $_GET['cat_id'];
$page_id = $_GET['page_id'];
$table_name_files = $wpdb->prefix."files";
$table_name_cat = $wpdb->prefix."file_cat";
$select_files = "SELECT file_id,file_name,display_name,icon_name,download_count FROM ".$table_name_files." WHERE cat_id=".$cat_id." ORDER BY display_name ";
$nos_pages_admin = get_option('user_max'); // No od datas per page
$nos_links_admin = get_option('user_max_links'); // No of links to b displayed
//$links_page =substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"?",1)+1);
$check_and = strpos($_SERVER['REQUEST_URI'],"&",1);
$check_and1 = strripos($_SERVER['REQUEST_URI'],"&",1);
if($check_and == $check_and1)
{
 if($_GET['page_id'] != '')
 {
 $links_page =substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"?",1));
 $links_page = get_option('blog_url').$links_page;
 }
 else
 {
 $links_page = get_option('blog_url').substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"/",1)+1);
 if(strpos($links_page,"&",1) >0)
 {
  $links_page = substr($links_page,0,strpos($links_page,'&',1));
 }
 }
}
else
{
 $filtered_url = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"?",1));
 $filtered_url = substr($filtered_url,0,strripos($filtered_url,"&",1));
 $links_page   = $filtered_url;
 if($_GET['page_id'] != '')
 {
 $links_page   = get_option('blog_url').$links_page;
 }
}

	//Create a PS_Pagination object
	$pager = new PS_Pagination($wpdb,$select_files,$nos_pages_admin ,$nos_links_admin,$links_page);

$result_files = $wpdb->get_results($select_files);
$select_cat_name = "SELECT category_name,icon_name FROM ".$table_name_cat." WHERE cat_id=$cat_id";
$result_cat_name = $wpdb->get_results($select_cat_name);
foreach($result_cat_name as $cat_name)
{
 $category_name = $cat_name->category_name;
 $icon_name_cat = $cat_name->icon_name;
}
$number_files = sizeof($result_files);
$blog_url = get_option('blog_url');
$icon_folder = substr(get_option('icon_folder'),strpos(get_option('icon_folder'),'/',1)+1);
$icon_path_cat = $blog_url.$icon_folder.$icon_name_cat;
$counter =0;
?>
<table width=" 450" align="left" cellpadding="0"  border="0">
  <tr>
    <th align="left" colspan="2" valign="middle" > <strong>
      <?php _e($category_name);?>
      </strong> <br />
    </th>
    <th> <img src="<?php _e($icon_path_cat)?>" border="0" align="texttop" style="float:right" /> </th>
  </tr>
  <?php if($number_files > 0) {?>
  <tr style="font-size: 12px;">
    <?php
//The paginate() function returns a mysql result set 
$result_files = $pager->paginate();

foreach($result_files as $files)
{
 $display_name = $files->display_name;
 $file_name = $files->file_name;
 $file_id = $files->file_id;
 $down_count = $files->download_count;
 $icon_name = $files->icon_name;
 $icon_path = $blog_url.$icon_folder.$icon_name ;
 $counter = $counter + 1;

 /* Find whether the permalinks is enabled or not begins*/
 $request_uri = $_SERVER['REQUEST_URI'];
 $position_qmark = strpos($request_uri,"?",'1');
 if($position_qmark == '')
 {
  $concat1 = "?";
 }
 else
 {
  $concat1 = "&";
 }
  /* Find whether the permalinks is enabled or not ends*/

?>

    <td><a href="<?php _e($_SERVER['REQUEST_URI']); _e($concat1);?>file_id=<?php _e($file_id);?>" style="text-decoration:none;"> <img src="<?php _e($icon_path)?>" border="0" /><br />
	
	<?php /* _e($blog_url);?>?page_id=<?php _e($page_id);?>&file_id=<?php _e($file_id);*/?>
	
          <?php
 _e($display_name);
 _e("(".$down_count.")");
 ?>
    </a> </td>
    <?php
if($counter > 2) { _e('</tr><tr bgcolor="#DDDDDD" style="font-size: 12px;">'); $counter = 0; }
 }
 if($counter % 2 == 1)
 {
  _e("<td>&nbsp;</td>");
 }
 if($counter == 1 || $counter == 2)
 {
  _e("<td>&nbsp;</td>");
 }
  ?>
  </tr>
  <?php } if($number_files == 0) { ?>
  <tr>
    <td bgcolor="#DDDDDD" colspan="3"><strong>No files to list</strong> <?php echo $file_count; ?> </td>
  </tr>
  <?php } ?>
  <?php if($number_files > 0) {?>
  <tr>
    <td colspan="3"><table width="100%" align="center" height="45">
      <tr>
        <td width="25%" align="center" style="border:1px solid #cccccc;"><?php
//Display the link to previous page: <<
echo $pager->renderPrev();
?>
        </td>
        <td width="50%" align="center" style="border:1px solid #cccccc;"><?php
//Display page links: 1 2 3
echo $pager->renderNav();
?>
        </td>
        <td width="25%" align="center" style="border:1px solid #cccccc;"><?php
//Display the link to next page: >>
echo $pager->renderNext();
?>
        </td>
      </tr>
    </table></td>
  </tr>
  <?php } ?>
  <tr>
  <?php
    $request_url_main = '';
	/*Back to main url*/
	if($_GET['cat_id'] != '')
	{
	if(strpos($_SERVER['REQUEST_URI'],'&',1) > 0)
	{
	$request_url_main = substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'&',1));
	if($_GET['page_display']!='' && $_GET['page_id'] =='')
	{
	$request_url_main = substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'?',1));
	}
	}
	else
	{
	$request_url_main = substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'?',1));
	}
	}
	?>
    <td colspan="3" align="right"><a href="<?php _e($request_url_main);?>" style="text-decoration:none">&lt;&lt;Back to main</a> </td>
	
	<?php /*_e($blog_url.'?page_id='.$page_id);*/?>
  </tr>
</table>
<?php
}
if($_GET['file_id'] != '')
{
//Display Files
global $wpdb;
$file_id = $_GET['file_id'];
$page_id = $_GET['page_id'];
$table_name_files = $wpdb->prefix."files";
$table_name_cat = $wpdb->prefix."file_cat";
$select_files = "SELECT cat_id,file_name,display_name,icon_name,download_count,description FROM ".$table_name_files." WHERE file_id=".$file_id;
$result_files = $wpdb->get_results($select_files);
$blog_url = get_option('blog_url');
$download_btn = $blog_url.substr(get_option('icon_folder'),strpos(get_option('icon_folder'),'/',1)+1)."download.png";
foreach($result_files as $files)
{
 $display_name = stripslashes($files->display_name);
 $description = stripslashes($files->description);
 $file_name = $files->file_name;
 $down_count = $files->download_count;
 $icon_name = $files->icon_name;
 $icon_folder = substr(get_option('icon_folder'),strpos(get_option('icon_folder'),'/',1)+1);
 $icon_path = $blog_url.$icon_folder.$icon_name ;
 $cat_id = $files->cat_id;
?>
<table width="450" cellpadding="0" cellspacing="10">
<tr>
<th>
<?php
 _e($display_name);
 ?>
</th>
<th>
<img src="<?php _e($icon_path)?>"  border="0"/><br />
</th>
</tr>
<tr>
<td>
<?php _e($description);?>
</td>
<td>
<a href="<?php _e($_SERVER['REQUEST_URI']);?>&down=yes"><img src="<?php _e($download_btn);?>"  border="0"/></a>
</td>
</tr>
<?php
/*Back to list url*/
$request_url = '';
$pos_qm = strpos($_SERVER['REQUEST_URI'],'&',1);
if($pos_qm == '')
{
 $request_url = $_SERVER['HTTP_REFERER']."?cat_id=".$cat_id;
}
else
{
 $request_url = $_SERVER['HTTP_REFERER'];
}
?>
<tr>
<td colspan="2" align="right">
<a href="<?php _e($request_url);?>" style="text-decoration:none">
&lt;&lt;Back to List</a>
<?php /*_e($blog_url);?>/?page_id=<?php _e($page_id); ?>&cat_id=<?php _e($cat_id);*/ ?>
</td>
</tr>
</table>
<?php 
}  // End for each
//File Download Code starts
if($_GET['down'] =="yes")
{
 $download_count = $down_count + 1;
 $update_query = "UPDATE ".$table_name_files." SET download_count=$download_count WHERE file_id=$file_id";
 $wpdb->query($update_query);
 $select_query = "SELECT dir_name FROM ".$table_name_cat." WHERE cat_id=$cat_id";
 $get_cat = $wpdb->get_var($select_query);
 $file_path = $blog_url.substr(get_option('upload_folder'),strpos(get_option('upload_folder'),'/',1)+1).$get_cat."/".$file_name;
 $bytes = filesize($file_path); 
 header("Content-type: application/jpg");
 header("Content-disposition: attachment; filename=\"$file_name\"");
 header("Content-length: $bytes");
 //Read the file and output to browser
 @readfile($file_path);
 //Code to record the download in database  
}
//File Download Code Ends
} // End if
?>
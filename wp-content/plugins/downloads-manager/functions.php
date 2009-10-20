<?php

### Install Databases
function DownloadsManager_Install() {
  global $wpdb, $table_prefix;
  $sql1 = "CREATE TABLE `".$table_prefix."dm_downloads` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `category` int(3) unsigned NOT NULL,
  `description` text NOT NULL,
  `permissions` enum('yes','no') NOT NULL default 'yes',
  `date` int(11) NOT NULL,
  `clicks` int(6) NOT NULL,
  PRIMARY KEY  (`id`)
  ) AUTO_INCREMENT=1 ;";
  $sql2 = "CREATE TABLE `".$table_prefix."dm_category` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
  ) AUTO_INCREMENT=1 ;";
  $wpdb->query($sql1);
  $wpdb->query($sql2);
  $dmdb = $wpdb->get_results("SELECT * from `".$table_prefix."dm_category` WHERE name = '".__('General','downloads-manager')."'");
  if(!$dmdb[0]->id)
  	$wpdb->query("INSERT INTO `".$table_prefix."dm_category` (name) VALUES ('".__('General','downloads-manager')."')");
}

function escape_var($var) {
  return $var = (!get_magic_quotes_gpc ()) ? addslashes($var) : $var;
}

function get_upload_max_filesize() {
  $upload_max_filesize =  ini_get('upload_max_filesize');
  preg_match('/^(\d+)([kmg])/i', $upload_max_filesize, $matches);
  switch($matches[2]) {
    case 'K' : $value = $matches[1] * 1024; break;
    case 'M' : $value = $matches[1] * 1024 * 1024; break;
    case 'G' : $value = $matches[1] * 1024 * 1024 * 1024; break;
    default : break;
  }
  print($value);
}

### Function to get file size
function FileSizeOf($link) {
  if(strpos($link, get_option('siteurl')) === false) {
    $link = str_replace(' ', '%20', $link);
    if(function_exists('get_headers')) {
      $headers = @get_headers($link, 1);
      if($headers['Content-Length'] == '') 
        return;
      $size = $headers['Content-Length'];
    }
    else {
      $file = @file_get_contents($link);
      if($file == false) 
        return;
      $size = strlen($file);
    }
  }
  else {
    $file = ABSPATH.'wp-content/plugins/downloads-manager/upload/'.basename($link);
    $size = @filesize($file);
  }
  $i = 0;
  $type = array("B", "KB", "MB", "GB");
  while (($size/1024)>1) {
    $size=$size/1024;
    $i++;
  }
  return substr($size,0,strpos($size,'.')+3).$type[$i];
}

### Get All Incos
function FetchIcons() {
  $iconsdir = '../wp-content/plugins/downloads-manager/img/icons/';
  $handle = opendir($iconsdir);
  if ($handle === false)
    echo '<option value="">'.__('Error...','downloads-manager').'</option>';
  else {
    while (false !== ($icon = readdir($handle))) {
      if ($icon != "." && $icon != ".." && $icon != "default.gif") {
        list($width, $height, $type) = getimagesize($iconsdir.$icon);
        $allowed_types = array('gif' => 1, 'jpg' => 2, 'png' => 3, 'bmp => 6');
        if (in_array($type,$allowed_types) && ($width <= 48) && ($height <= 48))
          echo '<option value="'.$icon.'">'.$icon.'</option>';
      }
    }
  }
  closedir($handle);
}

### List of Files
function lsFile() {
  global $downloadsdir, $wpdb, $table_prefix, $plugin_url;
  $handle = opendir($downloadsdir);
  if ($handle === false)
    echo '<option value="">'.__('Error...','downloads-manager').'</option>';
  else {
    $dl_arr = array();
    $rows = $wpdb->get_results("SELECT name, link FROM ".$table_prefix."dm_downloads");
    foreach($rows as $row)
      array_push($dl_arr, basename($row->link));
    while (false !== ($file = readdir($handle))) {
      if ($file != "." && $file != ".." && $file != "robots.txt")
        if(in_array($file, $dl_arr))
          echo '<option value="'.$plugin_url.'/upload/'.$file.'">'.$file.' - '.__('Already Added','downloads-manager').'</option>';
        else
          echo '<option value="'.$plugin_url.'/upload/'.$file.'">'.$file.'</option>';
    }
  }
  closedir($handle);
}

### Get Categories
function FetchCategory() {
  global $wpdb, $table_prefix;
  $rows = $wpdb->get_results("SELECT id,name FROM ".$table_prefix."dm_category");
  foreach($rows as $row)
    echo '<option value="'.$row->id.'">'.$row->name.'</option>';
}

### How Many Clicks Per Day
function dm_avarage($date, $clicks) {
  $dayago = time() - $date;
  $duration = $dayago / 86400;
  return $avarage = round(($clicks / $duration) , 2);
}

### Get Most Downloaded Files
function dm_get_most_downloaded($before, $after, $limit = 10, $av = false, $ct = false) {
  global $wpdb, $table_prefix;
  $rows = $wpdb->get_results("SELECT d.id as id, d.name as name, d.link as link, d.clicks as clicks, d.date as date, d.category as category, c.name as catname FROM ".$table_prefix."dm_downloads d, ".$table_prefix."dm_category c WHERE d.category=c.id ORDER BY d.clicks DESC LIMIT 0, $limit");
  $link = '/?file_id=%d';
  echo '<ul>';
  foreach($rows as $row) {
    $avarage = $av ? ' - '.dm_avarage($row->date, $row->clicks).' '.__('Clicks per day', 'downloads-manager') : '';
    $cat = $ct ? ' - '.__('Category', 'downloads-manager').': <b>'.$row->catname.'</b>' : '';
    printf($before.'<span style="color: grey;">('.$row->clicks.')</span> <b><a href="'.get_bloginfo('siteurl').$link.'">'.$row->name.'</a></b>'.$cat.$avarage.' '.$after, $row->id);
  }
  echo '</ul>';
}

### Get Last Added Files
function dm_get_new_download($before, $after, $limit = 10, $av = false, $ct = false) {
  global $wpdb, $table_prefix;
  $rows = $wpdb->get_results("SELECT d.id as id, d.name as name, d.link as link, d.clicks as clicks, d.date as date, d.category as category, c.name as catname FROM ".$table_prefix."dm_downloads d, ".$table_prefix."dm_category c WHERE d.category=c.id ORDER BY d.date DESC LIMIT 0, $limit");
  $link = '/?file_id=%d';
  echo '<ul>';
  foreach($rows as $row) {
    $avarage = $av ? ' - '.dm_avarage($row->date, $row->clicks).' '.__('Clicks per day', 'downloads-manager') : '';
    $cat = $ct ? ' - '.__('Category', 'downloads-manager').': <b>'.$row->catname.'</b>' : '';
    printf($before.'<b><a href="'.get_bloginfo('siteurl').$link.'">'.$row->name.'</a></b>'.$cat.$avarage.' '.$after, $row->id);
  }
  echo '</ul>';
}

### Replace Code [dm]id[/dm] with download box
function DownloadsManager_CodeReplace($content) {
  return $content = preg_replace("/\[dm\](\d+)\[\/dm\]/ise", "dm_embedded('\\1')", $content);
}

### Load Download Box Template
function dm_embedded($dmID) {
  global $wpdb, $table_prefix, $plugin_url, $iconsdir;
  $dmTemplate = 'wp-content/plugins/downloads-manager/single-download-template.tpl';
  $handle = @fopen($dmTemplate, 'r');
  if(!$handle)
    return __('Error... Unable to load download template. Search single-download-template.tpl in your plugin folder!','downloads-manager');
  $dmTemplateContent = @fread($handle, filesize($dmTemplate));
  $dmDownload = $wpdb->get_row("SELECT * FROM ".$table_prefix."dm_downloads WHERE id='".$dmID."'", ARRAY_A);
  if (!$dmDownload)
    return __('This is not a valid download id','downloads-manager');
  $dmDownload['icon'] = $iconsdir.'/'.$dmDownload['icon'];
  $dmDownload['date'] = date('d/m/Y', $dmDownload['date']);
  $dmDownload['size'] = FileSizeOf($dmDownload['link']);
  $dmDownload['url'] = get_bloginfo('siteurl').'/?file_id='.$dmID;
  $dmTemplateContent = preg_replace('/\{(t)([^}]*)}/e', __('\\2','downloads-manager'), $dmTemplateContent);
  $dmTemplateContent = preg_replace('/\{([^}]*)}/e', '$dmDownload[\\1]', $dmTemplateContent);
  fclose($handle);
  return stripslashes($dmTemplateContent);
}

### Load Download Page Template
function DownloadsManager_DownloadsPage($content) {
  global $wpdb, $table_prefix, $iconsdir;
  if(!preg_match("|<!--download table-->|", $content))
    return $content;
  $start = strpos($content, '<!--download table-->');
  $before = substr($content, 0, $start);
  $after = substr($content, 21+$start);
  $dmTemplate = 'wp-content/plugins/downloads-manager/page-download-template.tpl';
  $handle = @fopen($dmTemplate, 'r');
  if(!$handle)
    return __('Error... Unable to load page template. Search page-download-template.tpl in your plugin folder!','downloads-manager');
  $dmTemplateContent = fread($handle, filesize($dmTemplate));
  $content = "";
  $cats = $wpdb->get_results("SELECT id, name FROM ".$table_prefix."dm_category");
  if(!empty($cats)) {
    foreach($cats as $cat) {
      $rows = $wpdb->get_results("SELECT d.id,d.name,d.link,d.icon,d.category,d.description,d.date,d.clicks FROM ".$table_prefix."dm_downloads d WHERE d.category='".$cat->id."'", ARRAY_A);
      if(!empty($rows)) {
        $content .= "<h2 style=\"border-bottom: 1px dotted #CCC\">".$cat->name."</h2>";
        foreach($rows as $row) {
          $row['icon'] = $iconsdir.'/'.$row['icon'];
          $row['date'] = date('d/m/Y', $row['date']);
          $row['size'] = FileSizeOf($row['link']);
          $row['url'] = get_bloginfo('siteurl').'/?file_id='.$row['id'];
          $dmTemplateContent = preg_replace('/\{(t)([^}]*)}/e', __('\\2','downloads-manager'), $dmTemplateContent);
          $content .= preg_replace('/\{([^}]*)}/e', '$row[\\1]', $dmTemplateContent);
        }
      }
    }
  }
  fclose($handle);
  $content = $before.$content.$after;
  return $content;
}

?>

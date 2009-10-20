<?php

$count = $wpdb->get_row("SELECT COUNT(id) as downloads FROM ".$table_prefix."dm_downloads");

$per_page = 10;
$tot_pages = ($count->downloads == 0) ? 1 : ceil($count->downloads / $per_page);
$current_page = (!$_POST['p']) ? 1 : (int)$_POST['p'];
$first = ($current_page - 1) * $per_page;

$rows = $wpdb->get_results("SELECT d.id as id, d.name as name, ".
                          "d.category as category, ".
                          "d.icon as icon, ".
                          "d.description as description, ".
                          "d.permissions as permissions, ".
                          "d.date as date, ".
                          "d.link as link, ".
                          "d.clicks as clicks, ".
                          "c.name as catname ".
                          "FROM ".$table_prefix."dm_downloads d, ".$table_prefix."dm_category c ".
                          "WHERE d.category=c.id LIMIT $first, $per_page", ARRAY_A);

?>

<style type="text/css" media="screen">
.dm_true {
  background: url('<?php echo $plugin_url.'/img/true.png' ?>') no-repeat;
  padding: 0px 0px 0px 20px;
}

.dm_false {
  background: url('<?php echo $plugin_url.'/img/false.png' ?>') no-repeat;
  padding: 0px 0px 0px 20px;
}

#dm_cancel {
  text-align: center;
}

#dm_edit {
  text-align: center;
}
</style>

<script language="JavaScript" type="text/JavaScript">
<!--

function IconPreview(icon) {
  if (icon == "default.gif")
    document.getElementById('dm_iconpreview').innerHTML = '<img src="<?php echo $plugin_url; ?>/img/icons/default.gif" alt="default.gif"  />';
  else
    document.getElementById('dm_iconpreview').innerHTML = '<img src="<?php echo $plugin_url; ?>/img/icons/'+icon+'" alt="'+icon+'"  />';
}


function EditDownload(id, name, category, icon, description, permissions, link, date, clicks) {

  var categorybox = document.dm.dm_category;
  var categorylen = categorybox.options.length;
  var iconbox = document.dm.dm_icon;
  var iconlen = iconbox.options.length;
  var mod = (permissions == 'yes') ? 0 : 1;
  var i;
  var j;

  document.getElementById('dm_div_remove').style.display = 'none';
  document.getElementById('dm_div').style.display = '';
  document.getElementById('donwload_name').innerHTML = '<?php _e('Edit Download','downloads-manager'); ?> '+name;
  document.dm.dm_id.value = id;
  document.dm.dm_name.value = name;
  document.dm.dm_date.value = date;
  document.dm.dm_clicks.value = clicks;

  for (i = 0; i < categorylen; i++) {
    if (categorybox.options[i].text == category)
      categorybox.selectedIndex = i;
    }

  for (j = 0; j < iconlen; j++) {
    if (iconbox.options[j].text == icon) {
      iconbox.selectedIndex = j;
      document.getElementById('dm_iconpreview').innerHTML = '<img src="<?php echo $plugin_url; ?>/img/icons/'+icon+'" alt="'+icon+'"  />';
    }
  }
    
  document.dm.dm_description.value = description;
  document.dm.dm_permissions[mod].checked = true;

  if(link.indexOf('<?php echo get_option('siteurl'); ?>')) {
    document.dm.dm_link2.value = link;
    document.dm.dm_link.disabled = true;
    document.dm.dm_upload2link.checked = true;
    document.dm.dm_link.options.selected = false;
    document.getElementById('fromlink').style.display = '';
  }
  else {
    document.dm.dm_link2.value = '';
    document.dm.dm_link.value = link;
    document.dm.dm_upload2link.checked = false;
    document.getElementById('fromlink').style.display = 'none';
  }
  document.dm.dm_name.value = name;

}

function RemoveDownload(id, name, basename, link) {
  document.dm_form_remove.dm_id_remove.value = id;
  document.dm_form_remove.dm_name_remove.value = basename;
  document.getElementById('dm_div').style.display = 'none';
  document.getElementById('dm_div_remove').style.display = '';
  document.getElementById('donwload_name_remove').innerHTML = '<?php _e('Remove Download','downloads-manager'); ?> '+name+' ?';
  if(link.indexOf('<?php echo get_option('siteurl'); ?>'))
    document.getElementById('suredelete').style.display = 'none';
  else
    document.getElementById('suredelete').style.display = '';
}

function get_dm_code(e, dm_id) {
  ev = e || window.event;
  var obj;
  obj = document.getElementById('dm_code');
  obj.innerHTML = "<b><?php _e('Put this code into your post','downloads-manager'); ?></b><br />[dm]"+dm_id+"[/dm]<br /><a href=\"#\" onClick=\"document.getElementById('dm_code').style.display = 'none';\"><?php _e('Close','downloads-manager'); ?></a>";
  var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
  var leftPos = e.clientX - 100;

  obj.style.left = leftPos + 'px';
  obj.style.top = e.clientY + 2 + st + 'px';
  obj.style.opacity = 0.9;
  obj.style.MozOpacity = 0.9;
  obj.style.KhtmlOpacity = 0.9;
  obj.style.filter = "alpha(opacity=90)"; 
  obj.style.display = '';
}

function uselink() {
  if(document.dm.dm_upload2link.checked == true) {
    for(var k=0;k<document.dm.dm_link.options.length;k++)
      document.dm.dm_link.options[k].selected = false;
    document.dm.dm_link.disabled = true;
    document.getElementById('fromlink').style.display = 'block';
  }
  else {
    document.dm.dm_link.disabled = false;
    document.getElementById('fromlink').style.display = 'none';
  }
}

//-->
</script>

<?php echo $dm_message; ?>
<div id="dm_code" style="border: 1px solid #CCC; padding: 10px; margin: 10px 65px 10px 65px; width: 230px; display: none; position: absolute; text-align: center; background: white;">
null
</div>
<div class='wrap'>
  <h2><?php _e('Downloads Manager', 'downloads-manager'); ?></h2>
  <p>
  <?php _e('Wellcome! In this page there are all downloads you have added. Remeber to create categories (category page) else you can\'t add downloads.', 'downloads-manager'); ?><br />
  <?php _e('Good Blogging and enjoy it!', 'downloads-manager'); ?>
  </p>
    <table width="100%">
      <tr>
        <td width="50"><img src="<?php echo $plugin_url; ?>/img/unlock.gif"></td>
        <td width="150"><?php _e('Download for everyone','downloads-manager'); ?></td>
        <td width="50"><img src="<?php echo $plugin_url; ?>/img/lock.png"></td>
        <td><?php _e('Download for registred users','downloads-manager'); ?></td>
        <td style="text-align: right;">
          <form name="pages" method="post" onChange="this.submit()">
          <?php _e('Page', 'downloads-manager'); ?>: <select name="p">
          <?php
          for ($i = 1; $i <= $tot_pages; $i++) {
            if ($i == $current_page)
              echo "<option value=\"$i\" selected>$i</option>";
            else
              echo "<option value=\"$i\">$i</option>";
          }
          ?>
          </select>
          </form>
        </td>
      </tr>
    </table>

  
  <table class="widefat">
  <thead>
    <tr>
      <th scope="col"><?php _e('Icon', 'downloads-manager'); ?></th>
      <th scope="col"><?php _e('ID', 'downloads-manager'); ?></th>
      <th scope="col"><?php _e('Name', 'downloads-manager'); ?></th>
      <th scope="col"><?php _e('Size', 'downloads-manager'); ?></th>
      <th scope="col"><?php _e('Category', 'downloads-manager'); ?></th>
      <th scope="col"><?php _e('Added', 'downloads-manager'); ?></th>
      <th scope="col"><?php _e('Permissions', 'downloads-manager'); ?></th>
      <th scope="col"><?php _e('Clicks', 'downloads-manager'); ?></th>
      <th scope="col"><?php _e('Clicks per day', 'downloads-manager'); ?></th>
      <th scope="col" colspan="2"><?php _e('Actions', 'downloads-manager'); ?></th>
    </tr>
  </thead>
  <tbody id="the-list">
  <?php
  if(!empty($rows)) {
    foreach($rows as $row) {
      $permission = ($row['permissions'] == 'yes') ? '<img src="'.$plugin_url.'/img/unlock.gif" alt="unlock.gif">' : '<img src="'.$plugin_url.'/img/lock.png" alt="lock.png">';
      echo "  <tr>\n";
      echo "    <td><img src=\"".$plugin_url."/img/icons/".$row['icon']."\" alt=\"".$row['icon']."\"></td>\n";
      echo "    <td class=\"alternate\"><a href=\"javascript: void(0);\" title=\"".__('Click me!', 'downloads-manager')."\" onClick=\"javascript: get_dm_code(event, '".$row['id']."');\">".$row['id']."</a></td>\n";
      echo "    <td class=\"alternate\"><a href=\"".$row['link']."\">".stripslashes($row['name'])."</a></td>\n";
      echo "    <td class=\"alternate\">".FileSizeOf($row['link'])."</td>\n";
      echo "    <td class=\"alternate\">".stripslashes($row['catname'])."</td>\n";
      echo "    <td class=\"alternate\">".date('d/m/Y', $row['date'])."</td>\n";
      echo "    <td class=\"alternate\">".$permission."</td>\n";
      echo "    <td class=\"alternate\">".$row['clicks']."</td>\n";
      echo "    <td class=\"alternate\">".dm_avarage($row['date'], $row['clicks'])."</td>\n";
      echo "    <td class=\"alternate\"><a href=\"#delete\" onClick=\"RemoveDownload('".$row['id']."', '".$row['name']."', '".basename($row['link'])."', '".$row['link']."');\" class=\"delete\">".__('Delete', 'downloads-manager')."</a></td>";
      echo "    <td class=\"alternate\"><a href=\"#edit\" onClick=\"EditDownload('".$row['id']."', '".$row['name']."', '".$row['catname']."', '".$row['icon']."', '".$row['description']."', '".$row['permissions']."', '".$row['link']."', '".date('d/m/Y', $row['date'])."', '".$row['clicks']."');\" class=\"edit\">".__('Edit', 'downloads-manager')."</a></td>\n";
      echo "  </tr>\n";
    }
  }
  else {
    echo "  <tr>\n";
    echo "    <td class=\"alternate\" colspan=\"10\" style=\"text-align: center;\">".__('There are no downloads!', 'downloads-manager')."</td>\n";
    echo "  <tr>\n";
  }
  ?>
  </tbody>
  </table>
</div>

<a name="edit"></a>
<div class="wrap" id="dm_div" style="display: none;">
  <h2 id="donwload_name">None</h2>
  <form name="dm" method="POST" action="">
  <table align="center" cellpadding="3" width="100%" border="0">
    <tr>
      <th scope="row" align="right" width="100"><?php _e('File Name','downloads-manager') ?></th>
      <td width="500"><input type="text" name="dm_name" id="dm_name" size="40" /><input type="hidden" name="dm_id"></td>
      <th scope="row" rowspan="1">
        <?php _e('Files Uploaded','downloads-manager') ?>
      </th>

    </tr>
    <tr>
      <th scope="row" align="right">
      <?php _e('Description','downloads-manager') ?></th>
      <td>
        <textarea rows="6" name="dm_description" id="dm_description" style="width: 100%;"></textarea>
      </td>
      <th rowspan="5" style="vertical-align: top;">
        <select name="dm_link" multiple onChange="this.selectedIndex = this.selectedIndex" style="height: 200px;">
          <?php lsFile(); ?>
        </select>
        <p><label><input type="checkbox" name="dm_upload2link" value="1" onClick="uselink()"> <?php _e('Do you want edit a download link? (file remote only) ','downloads-manager') ?></label></p>
        <label id="fromlink" style="display: none;">Link: <input type="text" name="dm_link2" value="" size="50"></label>
      </th>
    </tr>
    <tr>
      <th scope="row" align="right">
      <?php _e('Category','downloads-manager') ?></th>
      <td>
        <select name="dm_category">
          <option value="0"><?php _e('Choose a Category...','downloads-manager') ?></option>
          <?php echo FetchCategory(); ?>
        </select>
      </td>
    </tr>
    <tr>
      <th scope="row" align="right"><?php _e('Icon','downloads-manager') ?></th>
      <td>
        <table border="0" cellspacing="0">
          <tr>
            <td>
              <select name="dm_icon" onChange="IconPreview(this.value)">
                <option value="default.gif">default.gif</option>
                <?php echo FetchIcons(); ?>
              </select>
              &nbsp; <?php _e('Preview','downloads-manager') ?> ==> 
            </td>
            <td>
              &nbsp; <span id="dm_iconpreview"><img src="<?php echo $plugin_url ?>/img/icons/default.gif" alt="" /></span>
            </td>
          </tr>
        </table>
      </td>
    </tr>
		<tr>
			<th scope="row" align="right"><?php _e('Clicks','downloads-manager') ?></th>
			<td><input type="text" name="dm_clicks"value="0" /></td>
		</tr>
		<tr>
			<th scope="row" align="right"><?php _e('Date','downloads-manager') ?></th>
			<td>
				<input type="text" name="dm_date" size="10" />
				<small>(<?php _e('dd/mm/yyyy','downloads-manager') ?>) <?php _e('Optional','downloads-manager') ?></small>
			</td>
		</tr>
    <tr>
      <th colspan="1" scope="row" align="right"><?php _e('Permissions','downloads-manager') ?></th>
      <td>
        <table>
          <tr>
            <td width="10"><input type="radio" name="dm_permissions" value="yes" checked="true" /></td>
            <td width="190"><?php _e('Download for all users','downloads-manager'); ?></td>
            <td width="10"><input type="radio" name="dm_permissions" value="no"/></td>
            <td width="300"><?php _e('Downlad for registred users only','downloads-manager'); ?></td>
          </tr>
        </table>
      </td>
      <th>
        <input type="submit" name="dm_update" id="dm_update" class="button" value="<?php _e('Update Information','downloads-manager') ?>" /> 
        <input type="reset" name="dm_reset" id="dm_reset" class="button" value="<?php _e('Reset Fields','downloads-manager') ?>" />
        <input type="button" class="button" value="<?php _e('Close','downloads-manager') ?>" onClick="document.getElementById('dm_div').style.display = 'none';" />
      </th>
    </tr>
  </table>
  </form>

</div>

<a name="delete"></a>
<div class="wrap" id="dm_div_remove" style="display: none;">
  <h2 id="donwload_name_remove">None</h2>
  <form name="dm_form_remove" method="POST" action="">
  <input type="hidden" name="dm_id_remove" value="">
  <input type="hidden" name="dm_name_remove" value="">
    <table border="0">
      <tr>
        <td><?php _e('Are you sure you want to remove this download?','downloads-manager'); ?></td>
        <td rowspan="2"><input type="submit" name="dm_remove" class="button" value="<?php _e('I\'m Sure','downloads-manager') ?>"> <input type="button" onClick="document.getElementById('dm_div_remove').style.display = 'none';" class="button" value="<?php _e('Close','downloads-manager') ?>"></td>
      </tr>
      <tr>
        <td><div id="suredelete"><input type="checkbox" name="dm_remove_file_too" value="remove" /> <?php _e('Do you want delete the local file?','downloads-manager'); ?></div></td>
      </tr>
    </table>
  </form>
</div>


<script language="JavaScript" type="text/JavaScript">
<!--

function IconPreview(icon) {
  if (icon == "default.gif")
    document.getElementById('dm_iconpreview').innerHTML = '<img src="<?php echo $plugin_url; ?>/img/icons/default.gif" alt="default.gif"  />';
  else
    document.getElementById('dm_iconpreview').innerHTML = '<img src="<?php echo $plugin_url; ?>/img/icons/'+icon+'" alt="'+icon+'"  />';
}


function EditDownload(id, name, category, icon, description, permissions, link) {

  var categorybox = document.dm.dm_category;
  var categorylen = categorybox.options.length;
  var iconbox = document.dm.dm_icon;
  var iconlen = iconbox.options.length;
  var mod = (permissions == 'yes') ? 0 : 1;
  var i;
  var j;

  document.getElementById('dm_div').style.display = '';
  document.getElementById('donwload_name').innerHTML = '<?php _e('Edit Download','downloads-manager'); ?> '+name;
  document.dm.dm_id.value = id;
  document.dm.dm_name.value = name;

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
  document.dm.dm_link.value = link;
  document.dm.dm_name.value = name;

}

function uselink() {
  if(document.dm.dm_upload2link.checked == true) {
    document.dm.dm_link.disabled = true;
    for(var k=0;k<document.dm.dm_link.options.length;k++)
      document.dm.dm_link.options[k].selected = false;
    document.getElementById('fromlink').style.display = '';
  }
  else {
    document.dm.dm_link.disabled = false;
    document.getElementById('fromlink').style.display = 'none';
  }
}

//-->
</script>

<?php echo $dm_message; ?>

<div class="wrap">
  <h2><?php _e('Upload Your File','downloads-manager'); ?></h2>
  <p><?php _e('Use this module to upload your files. If you find any problems you can use an ftp client to upload files into downloads-manager/upload/ directory.','downloads-manager'); ?></p>
  <form action="" name="dm_upload_form" method="POST" enctype="multipart/form-data">
    <input type="file" name="upfile" size="80">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php get_upload_max_filesize(); ?>">
    <input type="submit" name="dm_upload" value="<?php _e('Upload File','downloads-manager'); ?>">
  </form>
  <?php _e('Max file size','downloads-manager'); echo': '.ini_get('upload_max_filesize'); ?>
</div>

<br />

<div class="wrap">
  <h2 id="donwload_name"><?php _e('Add a Download','downloads-manager') ?></h2>
  <form name="dm" method="POST" action="">
  <table align="center" cellpadding="3" width="100%" border="0">
    <tr>
      <th scope="row" align="right" width="100"><?php _e('File Name','downloads-manager') ?></th>
      <td width="500"><input type="text" name="dm_name" id="dm_name" size="40" /></td>
      <th scope="row" rowspan="1">
        <?php _e('Files Uploaded','downloads-manager') ?>
      </th>

    </tr>
    <tr>
      <th scope="row" align="right">
      <?php _e('Description','downloads-manager'); ?></th>
      <td>
        <textarea rows="6" name="dm_description" id="dm_description" style="width: 100%;"></textarea>
      </td>
      <th rowspan="5" style="vertical-align: top;">
        <select name="dm_link" multiple onChange="this.selectedIndex = this.selectedIndex" style="height: 200px;">
          <?php lsFile(); ?>
        </select>
        <p><label><input type="checkbox" name="dm_upload2link" value="1" onClick="uselink();"> <?php _e('Do you want add a download from link?','downloads-manager') ?></label></p>
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
        <input type="submit" name="dm_add" id="dm_add" class="button" value="<?php _e('Add Download','downloads-manager') ?>" /> 
        <input type="reset" name="dm_reset" id="dm_reset" class="button" value="<?php _e('Reset Fields','downloads-manager') ?>" />
      </th>
    </tr>
  </table>
  </form>
</div>


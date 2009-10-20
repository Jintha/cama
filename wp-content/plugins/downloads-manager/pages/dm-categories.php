<script language="JavaScript" type="text/JavaScript">
<!--

function dmEditCategory(cat_id, cat_name) {
  var newname = '';
  if (confirm('<?php _e('Do you want to edit category', 'downloads-manager'); ?> '+cat_name+'?')) {
    while(newname == '')
      newname = prompt('<?php _e('Insert new name for this category', 'downloads-manager'); ?>', cat_name);
    if(newname)
      document.form_editcat.cat_id.value = cat_id;
      document.form_editcat.cat_newname.value = newname;
      document.form_editcat.submit();
  }
}

function dmDeleteCategory(cat_id, cat_name, count) {
  if(count != '0') {
    alert('<?php _e('You cant delete not empty categories', 'downloads-manager'); ?>');
    return;
  }
  if (confirm('<?php _e('Do you want to delete category', 'downloads-manager'); ?> '+cat_name+'?')) {
      document.form_editcat.cat_deleteid.value = cat_id;
      document.form_editcat.cat_deletename.value = cat_name;
      document.form_editcat.submit();
  }
}

//-->
</script>
<?php echo $dm_message; ?>
<div class="wrap">
<form method="POST" action="" name="form_editcat" >
<input type="hidden" name="cat_id" value="">
<input type="hidden" name="cat_newname" value="">
<input type="hidden" name="cat_deleteid" value="">
<input type="hidden" name="cat_deletename" value="">
  <h2><?php _e('Categories', 'downloads-manager'); ?></h2>
  <p>
    <?php _e('Add Category', 'downloads-manager'); ?>: <input type="text" name="dm_cat_name" > <input type="submit" name="dm_add_category" value=" <?php _e('Add Category', 'downloads-manager'); ?>" class="button">
  </p>
  <table class="widefat">
  <thead>
    <tr>
      <th scope="col"><?php _e('ID', 'downloads-manager'); ?></th>
      <th scope="col"><?php _e('Name', 'downloads-manager'); ?></th>
      <th scope="col"><?php _e('Downloads', 'downloads-manager'); ?></th>
      <th scope="col" colspan="2"><?php _e('Actions', 'downloads-manager'); ?></th>
    </tr>
  </thead>
  <tbody id="the-list">
  <?php
    $cats = $wpdb->get_results("SELECT * FROM ".$table_prefix."dm_category");
    foreach($cats as $cat) {
      $downloads = $wpdb->get_row("SELECT count(id) as ndownloads FROM ".$table_prefix."dm_downloads WHERE category='".$cat->id."'");
      echo "<tr>";
      echo "<td class=\"alternate\">".$cat->id."</td>";
      echo "<td class=\"alternate\">".stripslashes($cat->name)."</td>";
      echo "<td class=\"alternate\">".$downloads->ndownloads."</td>";
      echo "<td class=\"alternate\"><a href=\"javascript: dmDeleteCategory('".$cat->id."', '".$cat->name."', '".$downloads->ndownloads."')\" class=\"delete\">".__('Delete', 'downloads-manager')."</a></td>";
      echo "<td class=\"alternate\"><a href=\"javascript: dmEditCategory('".$cat->id."', '".$cat->name."')\" class=\"edit\">".__('Edit', 'downloads-manager')."</a></td>\n";
      echo "</tr>";
    }
  ?>
  </tbody>
  </table>
  </form>
</div>
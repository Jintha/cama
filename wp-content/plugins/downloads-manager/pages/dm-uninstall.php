<?php echo $dm_message; ?>
<div class="wrap">
<form method="POST" action="">
<h2><?php _e('Uninstall Downloads Manager', 'downloads-manager'); ?></h2>
<?php
if($dm_uninstall != true) {
?>
  <p align="center"><?php _e('When you\'ve installed this plugin it created these tables in your database.', 'downloads-manager'); ?></p>
  <div style="width: 400px; margin: auto;">
  <table class="widefat">
  <thead>
    <tr>
      <th scope="col"><?php _e('Table Name', 'downloads-manager'); ?></th>
      <th scope="col"><?php _e('Rows', 'downloads-manager'); ?></th>
    </tr>
  </thead>
    <?php
    foreach($dm_tables as $dm_table) {
      $row = $wpdb->get_row("SELECT COUNT(id) as nrow from ".$dm_table);
      echo "<tr>";
      echo "<td class=\"alternate\">".$dm_table."</td>";
      echo "<td class=\"alternate\">".$row->nrow."</td>";
      echo "</tr>";
    }
    ?>
  </table>
  </div>
  <p align="center"><?php _e('If you choose to uninstall downloads manager the table above will be deleted from your database. Are you sure?', 'downloads-manager'); ?></p>
  <p align="center"><label><input type="checkbox" name="dm_sure" value="yes"> <?php _e('I\'m Sure!', 'downloads-manager'); ?></label></p>
  <p align="center"><input type="submit" class="button" name="dm_uninstall" value="<?php _e('Uninstall Downloads Manager', 'downloads-manager'); ?>"</p>
<?php
}
else {
?>
<p align="center"><?php _e('Uninstall completed successfully', 'downloads-manager'); ?></p>
<?php
if(function_exists('wp_nonce_url')) { 
  $deactivate_plugin_url = wp_nonce_url('plugins.php?action=deactivate&amp;plugin=downloads-manager/downloads-manager.php', 'deactivate-plugin_downloads-manager/downloads-manager.php');
  echo '<p align="center">'.sprintf(__('<a href="%s">Click Here</a> and the plugin will be deactivated else it don\'t work again', 'downloads-manager'), $deactivate_plugin_url).'</p>';
  }
?>


<?php } ?>
</form>
</div>
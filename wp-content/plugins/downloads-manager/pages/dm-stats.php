<?php

$dm_stats = $wpdb->get_row("SELECT COUNT(id) as totdl, SUM(clicks) as totclicks FROM ".$table_prefix."dm_downloads");
$dm_cat = $wpdb->get_row("SELECT COUNT(id) as totcat FROM ".$table_prefix."dm_category");
$dm_first = $wpdb->get_row("SELECT name, date, link, clicks FROM ".$table_prefix."dm_downloads WHERE date in (SELECT MIN(date) FROM ".$table_prefix."dm_downloads)");
$dm_last = $wpdb->get_row("SELECT name, date, link, clicks FROM ".$table_prefix."dm_downloads WHERE date in (SELECT MAX(date) FROM ".$table_prefix."dm_downloads)");

?>
<div class="wrap">
  <h2><?php _e('Stats', 'downloads-manager'); ?></h2>
  <div style="width: 90%; margin: auto;">
    <table class="widefat" border="0">
    <thead>
      <tr>
        <th scope="col"><?php _e('Global Stats', 'downloads-manager'); ?></th>
        <th scope="col" style="text-align: center"><?php _e('10 Most Downloaded', 'downloads-manager'); ?></th>
      </tr>
    </thead>
      <tr>
        <td><b><?php _e('Tot Downloads', 'downloads-manager'); ?>:</b> <?php echo $dm_stats->totdl; ?></td>
        <td rowspan="7">
          <?php dm_get_most_downloaded('<li>', '</li>', 10, true, true); ?>
        </td>
      </tr>
      <tr>
        <td><b><?php _e('Tot Clicks', 'downloads-manager'); ?>:</b> <?php echo $dm_stats->totclicks; ?></td>
      </tr>
      <tr>
        <td><b><?php _e('Tot Categories', 'downloads-manager'); ?>:</b> <?php echo $dm_cat->totcat; ?></td>
      </tr>
      <tr>
        <td style="text-align: center; background: #ccc;"><b><?php _e('First Download Added', 'downloads-manager'); ?>:</b></td>
      </tr>
      <tr>
        <td style="text-align: center">
          <?php echo '<b><a href="'.$dm_first->link.'">'.$dm_first->name.'</a></b> - '.date("d/m/Y", $dm_first->date); ?><br />
          <?php echo '<b>'.__('Clicks', 'downloads-manager').':</b> '.$dm_first->clicks.' - <b>'.dm_avarage($dm_first->date, $dm_first->clicks).'</b> '.__('Clicks per day', 'downloads-manager'); ?>
        </td>
      </tr>
      <tr>
        <td style="text-align: center; background: #ccc;"><b><?php _e('Last Download Added', 'downloads-manager'); ?>:</b></td>
      </tr>
      <tr>
        <td style="text-align: center">
          <?php echo '<b><a href="'.$dm_last->link.'">'.$dm_last->name.'</a></b> - '.date("d/m/Y", $dm_last->date); ?><br />
          <?php echo '<b>'.__('Clicks', 'downloads-manager').':</b> '.$dm_last->clicks.' - <b>'.dm_avarage($dm_last->date, $dm_last->clicks).'</b> '.__('Clicks per day', 'downloads-manager'); ?>
        </td>
      </tr>
    </table>
  </div>
</div>
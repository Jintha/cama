<?php

$singleTemplate = '';
$pageTemplate = '';

if (!is_file('../wp-content/plugins/downloads-manager/page-download-template.tpl')) 
  $pageTemplate =  __('File page-download-template.tpl not found','downloads-manager');

if (!is_file('../wp-content/plugins/downloads-manager/single-download-template.tpl'))
  $singleTemplate = __('File single-download-template.tpl not found','downloads-manager');


$handle1 = @fopen('../wp-content/plugins/downloads-manager/single-download-template.tpl', 'r');
$handle2 = @fopen('../wp-content/plugins/downloads-manager/page-download-template.tpl', 'r');

if (!$handle1) 
  $singleTemplate =  __('File single-download-template.tpl not found','downloads-manager');
else {
  $singleTemplate = htmlspecialchars(fread($handle1, filesize('../wp-content/plugins/downloads-manager/single-download-template.tpl')));
  fclose($handle1);
}

if (!$handle2)
  $pageTemplate = __('File page-download-template.tpl not found','downloads-manager');
else {
  $pageTemplate = htmlspecialchars(fread($handle2, filesize('../wp-content/plugins/downloads-manager/page-download-template.tpl')));
  fclose($handle2);
}
  
echo $dm_message;

?>

<div class="wrap">
  <h2><?php _e('Template','downloads-manager'); ?></h2>
  <p><?php _e('Keywords','downloads-manager'); ?></p>
  <p><b>&#60&#33&#45&#45download table&#45&#45&#62 </b>: <?php _e('Show all downloads ordered by categories in posts or pages.','downloads-manager'); ?></p>
</div>

<div class="wrap">
<form method="POST" action="">
  <table border="0" width="100%">
    <tr>
      <th scope="col"><?php _e('Single Download Template','downloads-manager'); ?></th>
      <th scope="col"><?php _e('Page Download Template','downloads-manager'); ?></th>
    </tr>
    <tr>
      <td style="text-align: center;"><textarea cols="70" rows="25" name="singleTemplate" tabindex="1"><?php echo $singleTemplate; ?></textarea></td>
      <td style="text-align: center;"><textarea cols="70" rows="25" name="pageTemplate" tabindex="1"><?php echo $pageTemplate; ?></textarea></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;"><input type="submit" name="dm_template_edit" class="button" value="<?php _e('Update Templates','downloads-manager'); ?>"></td>
    </tr>
  </table>
</form>
</div>

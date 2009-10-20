<?php
/*
Plugin Name: Creative Clans Slide Show Wordpress Widget
Plugin URI: http://www.creativeclans.nl
Description: A widget to use the Creative Clans SlideShow in Wordpress. Version 1.2.1 also works if you've given 'wordpress its own directory' (see http://codex.wordpress.org/Giving_WordPress_Its_Own_Directory). For more info visit the <a href="http://www.creativeclans.nl">Creative Clans website</a>.
Version: 1.2.1
Author: Guido Tonnaer
Author URI: http://www.creativeclans.nl
*/

/*  Copyright 2009  Guido Tonnaer  (email : info@creativeclans.nl)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
    The CCSlideShow.swf flash file contained in this widget is released under 
    a different license, but as long as it is a part of this widget, you are
    free to use it as if it was released under the GNU/GPL license. 
    If you want to use it outside of the Wordpress widget, please refer to 
    http://www.creativeclans.nl for more info.
*/
    
    function widget_ccss_init() {
      if (!$options = get_option('widget_creativeclans_slideshow')) $options = array();
            
      $widget_ops = array('classname' => 'widget_creativeclans_slideshow', 'description' => 'Creative Clans Slide Show');
      $control_ops = array('width' => 400, 'height' => 350, 'id_base' => 'creativeclansslideshow');
      $name = 'Creative Clans Slide Show';
        
      $registered = false;
      foreach (array_keys($options) as $o) {
        if (!isset($options[$o]['width'])) continue;
                
        $id = "creativeclansslideshow-$o";
        $registered = true;
        wp_register_sidebar_widget($id, $name, 'widget_creativeclans_slideshow', $widget_ops, array( 'number' => $o ) );
        wp_register_widget_control($id, $name, 'widget_creativeclans_slideshow_control', $control_ops, array( 'number' => $o ) );
      }
      if (!$registered) {
        wp_register_sidebar_widget('creativeclansslideshow-1', $name, 'widget_creativeclans_slideshow', $widget_ops, array( 'number' => -1 ) );
        wp_register_widget_control('creativeclansslideshow-1', $name, 'widget_creativeclans_slideshow_control', $control_ops, array( 'number' => -1 ) );
      }
    }

    function widget_creativeclans_slideshow($args, $widget_args = 1) {
      extract($args, EXTR_SKIP );

      if (is_numeric($widget_args)) $widget_args = array('number' => $widget_args);
      $widget_args = wp_parse_args($widget_args, array( 'number' => -1 ));
      extract($widget_args, EXTR_SKIP);
      $options_all = get_option('widget_creativeclans_slideshow');
      if (!isset($options_all[$number])) return;

      $options = $options_all[$number];

      echo $before_widget;
      echo render_widget_creativeclans_slideshow($options, $number);
      echo $after_widget;
    }

    function widget_creativeclans_slideshow_checkinput ($value)
    {
      if (get_magic_quotes_gpc()) 
      {
        return trim(stripslashes($value));
      }
      else
      {
        return trim($value);
      }
    }

    function widget_creativeclans_slideshow_checkEffects($effects) { 
      $effectArrayIn = explode("\r\n", $effects);
      if (1 == count($effectArrayIn)) $effectArrayIn = explode("\n", $effectArrayIn[0]);
      $effectArrayOut = array();
	    foreach ($effectArrayIn as $key=>$value) {
        if ('' != trim($value)) $effectArrayOut[] = trim($value);
      }
      return implode(',', $effectArrayOut);
    }

    function widget_creativeclans_slideshow_checkSlideInfo($info) 
    { 
      $infoArray = explode("\r\n", $info);
      if (1 == count($infoArray)) $infoArray = explode("\n", $infoArray[0]);
      return $infoArray;
    }
    
    function widget_creativeclans_slideshow_control($widget_args = 1) {

      global $wp_registered_widgets;
      static $updated = false;
      $default_options = array(
            'width' => 400
          , 'height' => 300
          , 'backgroundColor' => '0xFFFFFF'
          , 'loops' => 0
          , 'wait' => 3000
          , 'effectTime' => 1
          , 'includeEffects' => ''
          , 'excludeEffects' => ''
          , 'stopOnMouseOver' => 'no'
          , 'enableLinks' => 'no'
          , 'linkTarget' => '_blank'
          , 'link' => ''
          , 'path' => ''
          , 'order' => 'forward'
          , 'slides' => 0
          , 'borderStyle' => 'none'
          , 'borderColor' => '0x000000'
          , 'borderThickness' => 1
          , 'borderAlpha' => 1.0
          , 'captionStyle' => 'none'
          , 'captionType' => 'text'
          , 'captionBackgroundColor' => '0x000000'
          , 'captionColor' => '0xFFFFFF'
          , 'captionText' => ''
          , 'captionTextRightMargin' => 0
          , 'captionTextLeftMargin' => 0
          , 'captionTextBottomMargin' => 0
          , 'captionTextFont' => 'Times New Roman'
          , 'captionTextFontSize' => 10
          , 'captionBackgroundAlpha' => 1.0
          , 'captionPosition' => 'top'
          , 'captionHorizontalOffset' => 0
          , 'captionVerticalOffset' => 0
          , 'captionImage' => ''
          , 'images' => ''
          , 'links' => ''
          , 'captions' => ''
      );

      $module_absolute_path = get_option('siteurl') . '/'.PLUGINDIR.'/creative-clans-slide-show/';
//      $module_path = PLUGINDIR.'/creative-clans-slide-show/';
      $pathArray = explode('/', get_bloginfo('wpurl'));
      $module_path = $_SERVER['DOCUMENT_ROOT'];
      foreach ($pathArray as $key => $value) {
        if ($key > 2) $module_path .= "/$value"; 
      }
      $module_path .= '/' . PLUGINDIR . '/creative-clans-slide-show/';

      if ( is_numeric($widget_args) ) $widget_args = array('number' => $widget_args);
      $widget_args = wp_parse_args($widget_args, array('number' => -1));
      extract($widget_args, EXTR_SKIP);
      $options_all = get_option('widget_creativeclans_slideshow');
      if (!is_array($options_all)) $options_all = array();  
      if (!$updated && !empty($_POST['sidebar'])) {
        $sidebar = (string)$_POST['sidebar'];

        $sidebars_widgets = wp_get_sidebars_widgets();
        if (isset($sidebars_widgets[$sidebar])) $this_sidebar =& $sidebars_widgets[$sidebar];
        else $this_sidebar = array();

        foreach ($this_sidebar as $_widget_id) {
          if ('widget_creativeclans_slideshow' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number'])) {
            $widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
            if (!in_array("creativeclansslideshow-$widget_number", $_POST['widget-id']))
              unset($options_all[$widget_number]);
              unlink($module_path.'xmlconfig'.$widget_number.'.xml');
              unlink($module_path.'xmlslides'.$widget_number.'.xml');
          }
        }
        foreach ((array)$_POST['widget_creativeclans_slideshow'] as $widget_number => $posted) {
          if (!isset($posted['width']) && isset($options_all[$widget_number]))
            continue;

          $options = array();
          foreach ($posted as $key => $value) {
          	$options[$key] = widget_creativeclans_slideshow_checkinput($value);
          }
          $options_all[$widget_number] = $options;
          
          // --> create the two xml files

          // check and create effect strings
          if ('' != $options['includeEffects']) $options['includeEffects'] = widget_creativeclans_slideshow_checkEffects($options['includeEffects']);
          if ('' != $options['excludeEffects']) $options['excludeEffects'] = widget_creativeclans_slideshow_checkEffects($options['excludeEffects']);

          // check and create slide info strings
          if ('' != $options['images']) $image = widget_creativeclans_slideshow_checkSlideInfo($options['images']);
          if ('' != $options['links']) $url = widget_creativeclans_slideshow_checkSlideInfo($options['links']);
          if ('' != $options['captions']) $title = widget_creativeclans_slideshow_checkSlideInfo($options['captions']);

          // Build config XML file
          $xml_data = '<?xml version="1.0" encoding="utf-8"?>'."\n";
?><?php
          $xml_data .= "<parameters>\n";
          foreach ($options as $key=>$value) {
            $xml_data .= "<parameter name=\"$key\">$value</parameter>\n";
          }
          $xml_data .= "</parameters>\n";

          // Write config XML file
          $xmlconfig_filename = $module_path.'xmlconfig'.$widget_number.'.xml';
          $xmlconfig_file = fopen($xmlconfig_filename,'w');
          fwrite($xmlconfig_file, $xml_data);
          fclose($xmlconfig_file);

          // Build slides XML file
          $xml_data = '<?xml version="1.0" encoding="utf-8"?>'."\n";
?><?php
          $xml_data .= "<images>\n";
          for($i=0; $i<count($image); $i++) {
            $xml_data .= "<image>\n";
            $xml_data .= "<itemName>".trim($image[$i])."</itemName>\n";
            if (isset($title[$i])) {
              $xml_data .= "<itemCaption>".trim($title[$i])."</itemCaption>\n";
            }
            else {
              $xml_data .= "<itemCaption></itemCaption>\n";
            }
            if (isset($url[$i])) {
              $xml_data .= "<itemLink>".trim($url[$i])."</itemLink>\n";
            }
            else {
              $xml_data .= "<itemLink></itemLink>\n";
            }
            $xml_data .= "</image>\n";
          }
          $xml_data .= '</images>'."\n";

          // Write slides XML file
          $xmlslides_filename = $module_path.'xmlslides'.$widget_number.'.xml';
          $xmlslides_file = fopen($xmlslides_filename,'w');
          fwrite($xmlslides_file, $xml_data);
          fclose($xmlslides_file);
        }
        update_option('widget_creativeclans_slideshow', $options_all);
        $updated = true;
      }

      if (-1 == $number) {
        $number = '%i%';
        $values = $default_options;
      }
      else {
        $values = $options_all[$number];
      }
      
?>     
<hr />
<B>Slide settings</B><br />
<label for="ccslideshow-path" title="Contains the absolute path to the slides folder, WITH the final slash.">Path:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-path" name="widget_creativeclans_slideshow[<?php echo $number; ?>][path]" type="text" value="<?php echo htmlspecialchars($values['path'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-images" title="List of the image names, one image per line.">Images:</label>
<textarea class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-images" name="widget_creativeclans_slideshow[<?php echo $number; ?>][images]" rows="8"><?php echo htmlspecialchars($values['images'], ENT_QUOTES); ?></textarea><br />
<label for="ccslideshow-captions" title="List of the captions, one caption per line.">Captions:</label>
<textarea class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captions" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captions]" rows="8"><?php echo htmlspecialchars($values['captions'], ENT_QUOTES); ?></textarea><br />
<label for="ccslideshow-links" title="List of links, one link per line.">Links:</label>
<textarea class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-links" name="widget_creativeclans_slideshow[<?php echo $number; ?>][links]" rows="8"><?php echo htmlspecialchars($values['links'], ENT_QUOTES); ?></textarea><br />
<hr />
<B>Several settings</B><br />
<label for="ccslideshow-width" title="The width of slideshow module specified in pixels.">Width (px):</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-width" name="widget_creativeclans_slideshow[<?php echo $number; ?>][width]" type="text" value="<?php echo htmlspecialchars($values['width'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-height" title="The height of slideshow module specified in pixels.">Height (px):</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-height" name="widget_creativeclans_slideshow[<?php echo $number; ?>][height]" type="text" value="<?php echo htmlspecialchars($values['height'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-backgroundColor" title="The slideshow background color. The color value must be specified in the format 0xXXXXXX. For example: 0xFFFFFF (white).">Background Color:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-backgroundColor" name="widget_creativeclans_slideshow[<?php echo $number; ?>][backgroundColor]" type="text" value="<?php echo htmlspecialchars($values['backgroundColor'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-loops" title="You can specify how many times to loop your slide show. Value '0' means infinite loops. Value has to be numeric.">Loops:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-loops" name="widget_creativeclans_slideshow[<?php echo $number; ?>][loops]" type="text" value="<?php echo htmlspecialchars($values['loops'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-wait" title="Time the slide is shown (in milliseconds). Value has to be numeric.">Wait (ms):</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-wait" name="widget_creativeclans_slideshow[<?php echo $number; ?>][wait]" type="text" value="<?php echo htmlspecialchars($values['wait'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-effectTime" title="Duration of the transition effect between slides (in seconds). Value has to be numeric.">Effect Time (s):</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-effectTime" name="widget_creativeclans_slideshow[<?php echo $number; ?>][effectTime]" type="text" value="<?php echo htmlspecialchars($values['effectTime'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-includeEffects" title="List of transition effects to be used (one effect per line). If you want to use all available effects, omit this parameter or leave it empty.	Possible values: see documentation (www.creativeclans.nl).">Include Effects:</label>
<textarea class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-includeEffects" name="widget_creativeclans_slideshow[<?php echo $number; ?>][includeEffects]" rows="8"><?php echo htmlspecialchars($values['includeEffects'], ENT_QUOTES); ?></textarea><br />
<label for="ccslideshow-excludeEffects" title="List of transition effects not to be used (one effect per line). If you want to use all available effects, omit this parameter or leave it empty.	Possible values: see documentation (www.creativeclans.nl).">Exclude Effects:</label>
<textarea class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-excludeEffects" name="widget_creativeclans_slideshow[<?php echo $number; ?>][excludeEffects]" rows="8"><?php echo htmlspecialchars($values['excludeEffects'], ENT_QUOTES); ?></textarea><br />
<label for="ccslideshow-stopOnMouseOver" title="If _yes_, the slide show is paused while the mouse hovers over the slideshow.">Stop on Mouse Over:</label>
<select class="widefat" style="width: 100;" name="widget_creativeclans_slideshow[<?php echo $number; ?>][stopOnMouseOver]" id="widget_creativeclans_slideshow-<?php echo $number; ?>-stopOnMouseOver">
    <option value="yes"<?php echo $values['stopOnMouseOver'] == 'yes' ? ' selected="selected"' : ''; ?>>yes</option>
    <option value="no"<?php echo $values['stopOnMouseOver'] == 'no' ? ' selected="selected"' : ''; ?>>no</option>
</select>
<label for="ccslideshow-enableLinks" title="If _yes_, clicking on the slide will open the link defined in the _Link_ parameter, or, if that doesn't have a value, the link defined for each slide in _Links_.">Enable Links:</label>
<select class="widefat" style="width: 100;" name="widget_creativeclans_slideshow[<?php echo $number; ?>][enableLinks]" id="widget_creativeclans_slideshow-<?php echo $number; ?>-enableLinks">
    <option value="yes"<?php echo $values['enableLinks'] == 'yes' ? ' selected="selected"' : ''; ?>>yes</option>
    <option value="no"<?php echo $values['enableLinks'] == 'no' ? ' selected="selected"' : ''; ?>>no</option>
</select>
<label for="ccslideshow-link" title="If used, it replaces the link of all slides.">Link:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-link" name="widget_creativeclans_slideshow[<?php echo $number; ?>][link]" type="text" value="<?php echo htmlspecialchars($values['link'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-linkTarget" title="Specify the target of the link.">Link Target:</label>
<select class="widefat" style="width: 100;" name="widget_creativeclans_slideshow[<?php echo $number; ?>][linkTarget]" id="widget_creativeclans_slideshow-<?php echo $number; ?>-linkTarget">
    <option value="_self"<?php echo $values['linkTarget'] == '_self' ? ' selected="selected"' : ''; ?>>The current frame in the current window</option>
    <option value="_blank"<?php echo $values['linkTarget'] == '_blank' ? ' selected="selected"' : ''; ?>>A new window</option>
    <option value="_parent"<?php echo $values['linkTarget'] == '_parent' ? ' selected="selected"' : ''; ?>>The parent of the current frame</option>
    <option value="_top"<?php echo $values['linkTarget'] == '_top' ? ' selected="selected"' : ''; ?>>The top-level frame in the current window</option>
</select>
<label for="ccslideshow-order" title="Order in which the slides are shown.">Order:</label>
<select class="widefat" style="width: 100;" name="widget_creativeclans_slideshow[<?php echo $number; ?>][order]" id="widget_creativeclans_slideshow-<?php echo $number; ?>-order">
    <option value="forward"<?php echo $values['order'] == 'forward' ? ' selected="selected"' : ''; ?>>forward</option>
    <option value="backward"<?php echo $values['order'] == 'backward' ? ' selected="selected"' : ''; ?>>backward</option>
    <option value="random"<?php echo $values['order'] == 'random' ? ' selected="selected"' : ''; ?>>random</option>
</select>
<label for="ccslideshow-slides" title="You can specify how many of the images must be shown. Is used when _Order_ is _random_. Value _0_ means all images. Value has to be numeric.">Number of slides:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-slides" name="widget_creativeclans_slideshow[<?php echo $number; ?>][slides]" type="text" value="<?php echo htmlspecialchars($values['slides'], ENT_QUOTES); ?>" /><br />
<hr />
<B>Border settings</B><br />
<label for="ccslideshow-borderStyle" title="Choose the border style.">Style:</label>
<select class="widefat" style="width: 100;" name="widget_creativeclans_slideshow[<?php echo $number; ?>][borderStyle]" id="widget_creativeclans_slideshow-<?php echo $number; ?>-borderStyle">
    <option value="none"<?php echo $values['borderStyle'] == 'none' ? ' selected="selected"' : ''; ?>>none</option>
    <option value="solid"<?php echo $values['borderStyle'] == 'solid' ? ' selected="selected"' : ''; ?>>solid</option>
    <option value="blurred"<?php echo $values['borderStyle'] == 'blurred' ? ' selected="selected"' : ''; ?>>blurred</option>
</select>
<label for="ccslideshow-borderColor" title="The border color is used when _Style_ is _solid_ or _blurred_. The color value must be specified in the format 0xXXXXXX. For example: 0x000000 (black).">Color:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-borderColor" name="widget_creativeclans_slideshow[<?php echo $number; ?>][borderColor]" type="text" value="<?php echo htmlspecialchars($values['borderColor'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-borderThickness" title="The border thickness is used when _Style_ is _solid_ or _blurred_. Value has to be numeric.">Thickness:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-borderThickness" name="widget_creativeclans_slideshow[<?php echo $number; ?>][borderThickness]" type="text" value="<?php echo htmlspecialchars($values['borderThickness'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-borderAlpha" title="The border transparancy is used when _Style_ is _solid_ or _blurred_. Value has to be numeric from 0 to 1.0.">Transparancy:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-borderAlpha" name="widget_creativeclans_slideshow[<?php echo $number; ?>][borderAlpha]" type="text" value="<?php echo htmlspecialchars($values['borderAlpha'], ENT_QUOTES); ?>" /><br />
<hr />
<B>Caption settings</B><br />
<label for="ccslideshow-captionStyle" title="Choose the caption style.">Style:</label>
<select class="widefat" style="width: 100;" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captionStyle]" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captionStyle">
    <option value="none"<?php echo $values['captionStyle'] == 'none' ? ' selected="selected"' : ''; ?>>none</option>
    <option value="fixed"<?php echo $values['captionStyle'] == 'fixed' ? ' selected="selected"' : ''; ?>>fixed</option>
    <option value="hide"<?php echo $values['captionStyle'] == 'hide' ? ' selected="selected"' : ''; ?>>hide</option>
</select>
<label for="ccslideshow-captionType" title="The caption type is used when _Style_ is _fixed_ or _hide_.">Type:</label>
<select class="widefat" style="width: 100;" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captionType]" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captionType">
    <option value="text"<?php echo $values['captionType'] == 'text' ? ' selected="selected"' : ''; ?>>text</option>
    <option value="image"<?php echo $values['captionType'] == 'image' ? ' selected="selected"' : ''; ?>>image</option>
</select>
<label for="ccslideshow-captionBackgroundColor" title="The caption background color is used when _Style_ is _fixed_ or _hide_, and _Type_ is _text_. The color value must be specified in the format 0xXXXXXX. For example: 0x000000 (black).">Background Color:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captionBackgroundColor" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captionBackgroundColor]" type="text" value="<?php echo htmlspecialchars($values['captionBackgroundColor'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-captionColor" title="The caption color is used when _Style_ is _fixed_ or _hide_, and _Type_ is _text_. The color value must be specified in the format 0xXXXXXX. For example: 0xFFFFFF (white).">Color:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captionColor" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captionColor]" type="text" value="<?php echo htmlspecialchars($values['captionColor'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-captionText" title="The caption text is used when _Style_ is _fixed_ or _hide_, and _Type_ is _text_. If used, it replaces the caption text of all slides.">Text:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captionText" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captionText]" type="text" value="<?php echo htmlspecialchars($values['captionText'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-captionTextRightMargin" title="Text right margin" description="The caption text right margin is used when _Style_ is _fixed_ or _hide_, and _Type_ is _text_. Has to be a numeric value.">Text right margin:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captionTextRightMargin" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captionTextRightMargin]" type="text" value="<?php echo htmlspecialchars($values['captionTextRightMargin'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-captionTextLeftMargin" title="The caption text left margin is used when _Style_ is _fixed_ or _hide_, and _Type_ is _text_. Has to be a numeric value.">Text left margin:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captionTextLeftMargin" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captionTextLeftMargin]" type="text" value="<?php echo htmlspecialchars($values['captionTextLeftMargin'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-captionTextBottomMargin" title="The caption text bottom margin is used when _Style_ is _fixed_ or _hide_, and _Type_ is _text_. Has to be a numeric value.">Text bottom margin:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captionTextBottomMargin" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captionTextBottomMargin]" type="text" value="<?php echo htmlspecialchars($values['captionTextBottomMargin'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-captionTextFont" title="The caption text font is used when _Style_ is _fixed_ or _hide_, and _Type_ is _text_. If the font doesn't exist, the default flash font (Times New Roman) will be used.">Font:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captionTextFont" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captionTextFont]" type="text" value="<?php echo htmlspecialchars($values['captionTextFont'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-captionTextFontSize" title="The caption text font size is used when _Style_ is _fixed_ or _hide_, and _Type_ is _text_. Has to be a numeric value.">Font size:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captionTextFontSize" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captionTextFontSize]" type="text" value="<?php echo htmlspecialchars($values['captionTextFontSize'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-captionBackgroundAlpha" title="The caption transparancy is used when _Style_ is _fixed_ or _hide_, and _Type_ is _text_. Value has to be numeric from 0 to 1.0.">Transparancy:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captionBackgroundAlpha" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captionBackgroundAlpha]" type="text" value="<?php echo htmlspecialchars($values['captionBackgroundAlpha'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-captionPosition" title="The caption position is used when _Style_ is _fixed_ or _hide_.">Position:</label>
<select class="widefat" style="width: 100;" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captionPosition]" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captionPosition">
    <option value="top"<?php echo $values['captionPosition'] == 'top' ? ' selected="selected"' : ''; ?>>top</option>
    <option value="bottom"<?php echo $values['captionPosition'] == 'bottom' ? ' selected="selected"' : ''; ?>>bottom</option>
    <option value="offset"<?php echo $values['captionPosition'] == 'offset' ? ' selected="selected"' : ''; ?>>offset</option>
</select>
<label for="ccslideshow-captionHorizontalOffset" title="The caption horizontal offset is used when _Position_ is _offset_. Has to be a numeric value.">Horizontal offset:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captionHorizontalOffset" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captionHorizontalOffset]" type="text" value="<?php echo htmlspecialchars($values['captionHorizontalOffset'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-captionVerticalOffset" title="The caption vertical offset is used when _Position_ is _offset_. Has to be a numeric value.">Vertical offset:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captionVerticalOffset" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captionVerticalOffset]" type="text" value="<?php echo htmlspecialchars($values['captionVerticalOffset'], ENT_QUOTES); ?>" /><br />
<label for="ccslideshow-captionImage" title="The caption image is used when _Style_ is _fixed_ or _hide_, and _Type_ is _image_. Has to contain the absolute path to the image to be used.">Image:</label>
<input class="widefat" id="widget_creativeclans_slideshow-<?php echo $number; ?>-captionImage" name="widget_creativeclans_slideshow[<?php echo $number; ?>][captionImage]" type="text" value="<?php echo htmlspecialchars($values['captionImage'], ENT_QUOTES); ?>" /><br />
<?php
    }
    
    function render_widget_creativeclans_slideshow($par, $moduleid) {

//      $module_absolute_path = get_option('home') . '/'.PLUGINDIR.'/creative-clans-slide-show/';
      $module_absolute_path = get_bloginfo('wpurl') . '/'.PLUGINDIR.'/creative-clans-slide-show/';
//      $module_path = PLUGINDIR.'/creative-clans-slide-show/';
      $pathArray = explode('/', get_bloginfo('wpurl'));
      $module_path = '';
      foreach ($pathArray as $key => $value) {
        if ($key > 2) $module_path .= "/$value"; 
      }
      $module_path .= '/' . PLUGINDIR . '/creative-clans-slide-show/';

      // If it doesn't exist yet, build config XML file
      $write_module_path = $_SERVER['DOCUMENT_ROOT'];
      foreach ($pathArray as $key => $value) {
        if ($key > 2) $write_module_path .= "/$value"; 
      }
      $write_module_path .= '/' . PLUGINDIR . '/creative-clans-slide-show/';
      $xmlconfig_filename = $write_module_path.'xmlconfig'.$moduleid.'.xml';
      if (!file_exists($xmlconfig_filename)) {
        // check and create effect strings
        if ('' != $par['includeEffects']) $par['includeEffects'] = widget_creativeclans_slideshow_checkEffects($par['includeEffects']);
        if ('' != $par['excludeEffects']) $par['excludeEffects'] = widget_creativeclans_slideshow_checkEffects($par['excludeEffects']);

        // create config XML file        
        $xml_data = '<?xml version="1.0" encoding="utf-8"?>'."\n";
?><?php
        $xml_data .= "<parameters>\n";
        foreach ($par as $key=>$value) {
        	$xml_data .= "<parameter name=\"$key\">$value</parameter>\n";
        }
        $xml_data .= "</parameters>\n";

        // Write config XML file
        $xmlconfig_file = fopen($xmlconfig_filename,'w');
        fwrite($xmlconfig_file, $xml_data);
        fclose($xmlconfig_file);
      }

      // If it doesn't exist yet, build slides XML file
      $xmlslides_filename = $write_module_path.'xmlslides'.$moduleid.'.xml';
      if (!file_exists($xmlslides_filename)) {
        // check and create slide info strings
        if ('' != $par['images']) $image = widget_creativeclans_slideshow_checkSlideInfo($par['images']);
        if ('' != $par['links']) $url = widget_creativeclans_slideshow_checkSlideInfo($par['links']);
        if ('' != $par['captions']) $title = widget_creativeclans_slideshow_checkSlideInfo($par['captions']);

        // Build slides XML file
        $xml_data = '<?xml version="1.0" encoding="utf-8"?>'."\n";
?><?php
        $xml_data .= "<images>\n";
        for($i=0; $i<count($image); $i++) {
          $xml_data .= "<image>\n";
          $xml_data .= "<itemName>".trim($image[$i])."</itemName>\n";
          if (isset($title[$i])) {
            $xml_data .= "<itemCaption>".trim($title[$i])."</itemCaption>\n";
          }
          else {
            $xml_data .= "<itemCaption></itemCaption>\n";
          }
          if (isset($url[$i])) {
            $xml_data .= "<itemLink>".trim($url[$i])."</itemLink>\n";
          }
          else {
            $xml_data .= "<itemLink></itemLink>\n";
          }
          $xml_data .= "</image>\n";
        }
        $xml_data .= '</images>'."\n";

        // Write slides XML file
        $xmlslides_file = fopen($xmlslides_filename,'w');
        fwrite($xmlslides_file, $xml_data);
        fclose($xmlslides_file);
      }

      $result = 
<<<CCSSWIDGET
<div id="cc-is{$moduleid}" class="ccslideshow" style="width:{$par['width']}px height:{$par['height']}px">
    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
            codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0"
            width="{$par['width']}" height="{$par['height']}">
      <param name="wmode" value="opaque" />
      <param name="movie" value="{$module_absolute_path}CCSlideShow.swf" />
      <param name="FlashVars" value="config={$module_absolute_path}xmlconfig{$moduleid}.xml&amp;slides={$module_absolute_path}xmlslides{$moduleid}.xml" />
      <embed src="{$module_absolute_path}CCSlideShow.swf" 
             type="application/x-shockwave-flash"
             pluginspage="http://www.macromedia.com/go/getflashplayer"
             wmode="opaque"
             width="{$par['width']}" height="{$par['height']}" 
      			 FlashVars="config={$module_absolute_path}xmlconfig{$moduleid}.xml&amp;slides={$module_absolute_path}xmlslides{$moduleid}.xml" />
    </object>
</div>
CCSSWIDGET;

?><?php
      return $result;
    }

add_action('widgets_init', 'widget_ccss_init');

?>
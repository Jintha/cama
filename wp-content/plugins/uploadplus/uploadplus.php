<?php
/*
Plugin Name: Upload+
Plugin URI: http://pixline.net/wordpress-plugins/upload-plus/en/
Description: Security and sanity in file names while uploading. Once activate, please <a href="options-general.php?page=uploadplus">define your settings</a>. 
Author: Pixline
Version: 2.7
Author URI: http://pixline.net/

Copyright (C) 2007+ Paolo Tresso aka Pixline (http://pixline.net/)

Includes hints and code by:
	Francesco Terenzani (http://terenzani.it/)
	Jennifer Hodgdon (http://www.poplarware.com/)

Make use of UTF8 PHP classes by http://phputf8.sourceforge.net/

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

require_once 'utf8/utf8.php';
require_once 'utf8/str_ireplace.php';
require_once  UTF8 . '/utils/validation.php';
require_once  UTF8 . '/utils/ascii.php';
require_once 'utf8_to_ascii/utf8_to_ascii.php';

$version = get_option('uploadplus_version');
if(isset($version) && $version < 4):
	$style = get_option('uploadplus_style');
	$cleanlevel = ($style!="") ? $style[0] : array(0=>3);
	$lc = get_option('uploadplus_lettercase');
	$case = ($lc!="") ? $lc[0] : array(0=>1);

	update_option('uploadplus_version', 4);
	update_option('uploadplus_cleanlevel', $cleanlevel );
	update_option('uploadplus_case', $case );
	update_option('uploadplus_prefix', 3);

	delete_option('uploadplus_style');
	delete_option('uploadplus_prefix_custom');
	delete_option('uploadplus_prefix_standard');
	delete_option('uploadplus_lettercase');
endif;



// adds 'settings' link alongside the plugin activation link
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'upp_plugin_setting_links' ); 
function upp_plugin_setting_links( $links ) { 
 // Add a link to this plugin's settings page
 $settings_link = '<a href="options-misc.php">'.__('Options').'</a>'; 
 array_unshift( $links, $settings_link ); 
 return $links; 
}




/* find extension */
function upp_findexts ($filename) { 
	$exts = split("[/\\.]", $filename) ; 
	$n = count($exts)-1; 
	$exts = $exts[$n]; 
	return $exts; 
} 

/* find full filename */
function upp_find_filename ($filename) { 
	$explode = explode("/",$filename);
	$explode = array_reverse($explode);
	return $explode[0];
} 

/*    sanitize uploaded file name    */
function upp_mangle_filename($file_name){	
	/* remove internal dots (cosmetical, it would be done by WP, but we need to display it :)*/
	$ext = upp_findexts($file_name);
	$file_name = str_replace(".".$ext,"",$file_name);
	$file_name = str_replace(".","",$file_name);
		
	// initial cleaning
	$file_name = str_replace("(","",$file_name);
	$file_name = str_replace(")","",$file_name);
	$file_name = str_replace("'","",$file_name);
	$file_name = str_replace('"',"",$file_name);
	$file_name = str_replace(',',"",$file_name);

	// some language-based prefilter. props denis.
	$de_from 	= array('ä','ö','ü','ß','Ä','Ö','Ü');
	$de_to 		= array('ae','oe','ue','ss','Ae','Oe','Ue');
	$file_name	= str_replace($de_from, $de_to, $file_name);

	$utf8ornot = get_option('uploadplus_utf8toascii');
	if($utf8ornot[0]==1) $file_name = utf8_to_ascii($file_name); 

	$file_name = $file_name.".".$ext;
	
	$lettercase = get_option('uploadplus_case');
	switch($lettercase[0]):
		case "1":
			$file_name = utf8_strtolower($file_name);
			break;
		case "2":
			$file_name = utf8_strtoupper($file_name);
			break;
	endswitch;

	$y = get_option('uploadplus_cleanlevel');
	switch($y[0]):
	case "1":
		$file_name = ereg_replace("[^A-Za-z0-9._]", "-", $file_name);
		$file_name = utf8_ireplace("_", "-", $file_name);	
		$file_name = utf8_ireplace(" ", "-", $file_name);
		$file_name = utf8_ireplace("%20", "-", $file_name);
		break;
	case "2":	
		$file_name = ereg_replace("[^A-Za-z0-9._]", "", $file_name);
		$file_name = utf8_ireplace("_", "", $file_name);	
		$file_name = utf8_ireplace("-", "", $file_name);	
		$file_name = utf8_ireplace("%20", "", $file_name);
		break;
	case "3":
		$file_name = ereg_replace("[^A-Za-z0-9._]", "_", $file_name);
		$file_name = utf8_ireplace("-", "_", $file_name);	
		$file_name = utf8_ireplace(" ", "_", $file_name);
		$file_name = utf8_ireplace("%20", "_", $file_name);
		break;
	endswitch;

	$sep = ($y[0]=='1') ? "-" : "";
	if(!$sep) $sep = ($y[0]=='3') ? "_" : "";

	$standard = get_option('uploadplus_prefix');
		switch($standard):
			case "0":		$file_name = date('d').$sep.$file_name;			break;
			case "1":		$file_name = date('md').$sep.$file_name;			break;
			case "2":		$file_name = date('ymd').$sep.$file_name;			break;
			case "3":		$file_name = date('Ymd').$sep.$file_name;			break;
			case "4":		$file_name = date('YmdHi').$sep.$file_name;			break;
			case "5":		$file_name = date('YmdHis').$sep.$file_name;			break;
			case "6":		$file_name = date('U').$sep.$file_name;			break;
			case "7":		$file_name = mt_rand().$sep.$file_name;			break;
			case "8":		$file_name = md5(mt_rand()).$sep.$file_name;			break;
			case "9":		$file_name = str_replace( array(".","_","-"," ") ,$sep, utf8_to_ascii(get_bloginfo('name'))).$sep.$file_name;			break;
			case "A":		$file_name = str_replace( array(".","_","-"," ") ,"", utf8_to_ascii(get_bloginfo('name'))).$sep.$file_name;			break;
		endswitch;
	return $file_name;
}

/* apply out changes to the real file while it's being moved to its destination */
// $array( 'file' => $new_file, 'url' => $url, 'type' => $type );
function upp_rename($array){ 
global $action;
	$current_name = upp_find_filename($array['file']);
	$current_name = urldecode($current_name);
	$new_name = upp_mangle_filename($current_name);		
	$lpath = str_replace($current_name, "", urldecode($array['file']));
	$wpath = str_replace($current_name, "", urldecode($array['url']));
	$lpath_new = $lpath . $new_name;
	$wpath_new = $wpath . $new_name;
	if( @rename($array['file'], $lpath_new) )
	return array(
		'file' => $lpath_new,
		'url' => $wpath_new,
		'type' => $array['type']
		);
	return $array;
}

#add_action( 'admin_menu', 'upp_add_mangle_options_page' );	// add option page
add_action('wp_handle_upload', 'upp_rename');				// apply our modifications














/* init admin panel (now in options/misc) */
function upp_options_init_api() {
	add_settings_section('upp_options_section', 'Upload+ Plugin', 'upp_options_intro', 'misc');

	add_settings_field('uploadplus_cleanlevel', 'Cleaning options', 'upp_options_box_cleanlevel', 'misc', 'upp_options_section');
	add_settings_field('uploadplus_case', 'Case options', 'upp_options_box_case', 'misc', 'upp_options_section');
	add_settings_field('uploadplus_prefix', 'Prefix', 'upp_options_box_prefix', 'misc', 'upp_options_section');
	add_settings_field('uploadplus_utf8toascii', 'Transcription', 'upp_options_box_utf8toascii', 'misc', 'upp_options_section');

	register_setting('misc', 'uploadplus_cleanlevel');
	register_setting('misc', 'uploadplus_case');
	register_setting('misc', 'uploadplus_prefix');
	register_setting('misc', 'uploadplus_utf8toascii');
}
add_action('admin_init', 'upp_options_init_api');

/* admin panel intro */
function upp_options_intro() {
	$test_string1 = "WordPress Manual (for dummies, experts and pro's) 2.2nd Edition.pdf";
	#$test_string1 = "نرحب بكم في الموقع الرسمي لبرنامج ووردبريس المعرب،.pdf";
	$demo_string1 = upp_mangle_filename($test_string1);
	echo "<blockquote><p>This plugin allows you to rename every file you upload, and in this page you can define this behaviour. ";
	echo("
	<p><small>You can choose to <em>convert spaces and underscores into dashes</em>, <em>strip all dashes/underscores/spaces</em>, or <em>convert every spaces into an underscore</em>. Also, you can choose to <em>lowercase</em> the file name or leave it with mixed case, and finally you can define a custom prefix to prepend, either a fixed one (like the name of your blog) or a date-based one. Feel free to play with the settings and save them, because you can check in this page what kind of transformation will be applied.</small></p><p> <code>".$test_string1."</code>  &raquo;  <strong><code>".$demo_string1."</code></strong>	</p>
</blockquote>
	");
}


function upp_options_box_cleanlevel(){
	if(get_option('uploadplus_cleanlevel'))	$actual = get_option('uploadplus_cleanlevel');
	$styles = array(
		"1" => array('label'=>'Convert spaces and underscores into dashes', 'demo'=>'wordpress-manual.pdf'), 
		"2" => array('label'=>'Strip all spaces/dashes/underscores', 'demo'=>'wordpressmanual.pdf'), 
		"3" => array('label'=>'Convert spaces into underscores (dashes allowed)', 'demo'=>'wordpress-manual.pdf'), 
		);
	foreach($styles as $key=>$info):
		if($actual[0]==$key)	$flag = 'checked="checked"';	else $flag = '';
		echo '
		<p><input type="radio" name="uploadplus_cleanlevel[]" id="uploadplus_style-'.$key.'" '.$flag.' value="'.$key.'"/>
		'.$info['label'].' <small>like in:</small> <code> '.$info['demo'].' </code></p>
		';
	endforeach;
}

function upp_options_box_case(){
	if(get_option('uploadplus_case'))	$case = get_option('uploadplus_case');
	$cases = array(
	"0"	=> "Leave it whatever it is", 
	"1"	=> "Make all lowercase", 
	"2"	=> "Make all UPPERCASE"
	);
	foreach($cases as $ca=>$se):
		if( $case[0] == $ca): $flag = 'checked="checked"'; else: $flag = ""; endif;
		echo '<p><input type="radio" name="uploadplus_case[]" id="uploadplus_lettercase-'.$ca.'" value="'.$ca.'" '.$flag.'/>'.$se.'</p>';
	endforeach;
}

function upp_options_box_prefix(){
$clean = get_option('uploadplus_cleanlevel');
$sep = ($clean[0]=='1') ? "-" : "";
if(!$sep) $sep = ($clean[0]=='3') ? "_" : "";

$prefix = get_option('uploadplus_prefix');

$datebased = array(
	"0" => 'dd (like: '.date('d').$sep.')',
	"1" => 'mmdd (like: '.date('md').$sep.')',
	"2" => 'yymmdd (like: '.date('ymd').$sep.')"',
	"3" => 'yyyymmdd (like: '.date('Ymd').$sep.')',
	"4" => 'yyyymmddhhmm (like: '.date('YmdHi').$sep.')',
	"5" => 'yyyymmddhhmmss (like: '.date('YmdHis').$sep.')',
 	"6" => 'unix timestamp (like: '.date('U').$sep.')',
	);

$otherstyles = array(
	"7" => '[random (mt-rand)] '.mt_rand().$sep,
	"8" => '[random md5(mt-rand)] '.md5(mt_rand()).$sep,
 	"9" => '[blog name] '.str_replace( array(".", " ", "-", "_") ,$sep,strtolower(get_bloginfo('name'))).$sep,
 	"A" => '[short blog name] '.str_replace( array(".","_","-"," "),"",strtolower(get_bloginfo('name'))).$sep
	);

$nullval = ($prefix=="") ? 'selected="selected"' : "";
echo '
	<select name="uploadplus_prefix" id="uploadplus_prefix">	
	<option value="" label="No Prefix" '.$nullval.'>No Prefix</option>
	<optgroup label="Date Based">
	';
	$flag = $oflag = "";
	foreach($datebased as $key=>$val):
		$flag = ($prefix==$key && $nullval=="") ? 'selected="selected"' : "";
		echo '<option value="'.$key.'" label="'.$val.'" '.$flag.'>'.$val.'</option>
		';
	endforeach;
	echo'
	</optgroup>
	<optgroup label="Other Styles">
	';
	foreach($otherstyles as $okey=>$oval):
		$oflag = ($prefix==$okey && $nullval=="") ? 'selected="selected"' : "";
		echo '<option value="'.$okey.'" label="'.$oval.'" '.$oflag.'>'.$oval.'</option>
		';
	endforeach;
	
	echo '
	</optgroup>	
	</select>
	<br/>
	<small>Prefix will follow the other rules, so if you choose dashes, it will use dashes.</small>
';
}


function upp_options_box_utf8toascii(){
	if(get_option('uploadplus_utf8toascii'))	$utf8ornot = get_option('uploadplus_utf8toascii');
	$options = array(
	"0"	=> "Don't convert <code>(safe mode)</code>", 
	"1"	=> "Yes, please, convert utf8 characters into ASCII"
	);
	foreach($options as $uk=>$uv):
		if( $utf8ornot[0] == $uk): $flag = 'checked="checked"'; else: $flag = ""; endif;
		echo '<input type="radio" name="uploadplus_utf8toascii[]" id="uploadplus_utf8toascii-'.$uk.'" value="'.$uk.'" '.$flag.'/>'.$uv.' &nbsp; ';
	endforeach;
	echo "<br/><small>(Learn more about transcription on <a href='http://en.wikipedia.org/wiki/Transcription_(linguistics)'>Wikipedia</a>).</small>";
}


?>
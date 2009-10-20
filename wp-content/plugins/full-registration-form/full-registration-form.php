<?php
/*
Plugin Name: Full Registration Form
Plugin URI: http://www.horttcore.de/wordpress/full-registration-form/
Description: Full Registration Form for WP 2.3.3
Author: Ralf Hortt
Version: 0.9
Author URI: http://www.horttcore.de/
*/ 

add_action('register_form', 'frf_user_profil'); // Remove "#" if user can fill out the form on registration.
add_filter('pre_user_url', 'frf_url'); // Remove "#" if user can fill out the form on registration.
add_filter('pre_user_nickname', 'frf_nickname'); // Remove "#" if user can fill out the form on registration.
add_filter('pre_user_first_name', 'frf_first_name'); // Remove "#" if user can fill out the form on registration.
add_filter('pre_user_last_name', 'frf_last_name'); // Remove "#" if user can fill out the form on registration.
add_filter('pre_user_description', 'frf_description'); // Remove "#" if user can fill out the form on registration.


//======================================
// Description: 
Function frf_url($url) {
	$url = ($_POST['user_url']) ? $_POST['user_url'] : $url;
	return $url;
}

//======================================
// Description: 
Function frf_nickname($nickname) {
	$nickname = ($_POST['nickname']) ? $_POST['nickname'] : $nickname;
	return $nickname;
}

//======================================
// Description: 
Function frf_first_name($first_name) {
	$first_name = ($_POST['first_name']) ? $_POST['first_name'] : $first_name;
	return $first_name;
}

//======================================
// Description: 
Function frf_last_name($last_name) {
	$last_name = ($_POST['last_name']) ? $_POST['last_name'] : $last_name;
	return $last_name;
}

//======================================
// Description: 
Function frf_description($description) {
	$description = ($_POST['description']) ? $_POST['description'] : $description;
	return $description;
}


//======================================
// Description: The form to extend the profile
Function frf_user_profil() {?>	
	<p><label for="user_url"><?php _e('Website') ?></label><br />
	<input type="text" name="user_url" size="39" id="user_url" value="" /></p>

	<p><label for="first_name"><?php _e('First Name')?></label><br />
	<input name="first_name" size="39" id="first_name" value="" /></p>
	
	<p><label for="last_name"><?php _e('Last Name')?></label><br />
	<input name="last_name" size="39" id="last_name" value="" /></p>
	
	<p><label for="nickname"><?php _e('Nickname')?></label><br />
	<input name="nickname" size="39" id="nickname" value="" /></p>
	<!-- 
	<p><label for="aim"><?php _e('AIM')?></label><br />
	<input name="aim" id="aim"  style="width: 100%" /></p>
	
	<p><label for="yim"><?php _e('Yahoo IM')?></label><br />
	<input name="yim" id="yim"  style="width: 100%" /></p>
	
	<p><label for="jabber"><?php _e('Jabber / Google Talk')?></label><br />
	<input name="jabber" id="jabber"  style="width: 100%" /></p>
	-->
	<p><label for="description"><?php _e('Biography')?></label><br />
	<textarea name="description" cols="37" id="description"></textarea></p><?
}
?>
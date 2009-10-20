<?php

/*
Plugin Name: Translucent image slideshow gallery
Plugin URI: http://gopi.coolpage.biz/demo/2009/08/21/translucent-image-slideshow-gallery/
Description: Translucent image slideshow gallery ,Don't just display images, showcase them in style using this Translucent image slideshow gallery plugin.
Author: Gopi.R
Version: 1.0
Author URI: http://gopi.coolpage.biz/demo/about/
Donate link: http://gopi.coolpage.biz/demo/2009/08/21/translucent-image-slideshow-gallery/
*/

function TISG_slideshow() 
{
	echo "<div style='padding:3px;'></div>";

	$TISG_width = get_option('TISG_width');
	$TISG_height = get_option('TISG_height');
	$TISG_delay = get_option('TISG_delay');
	$TISG_speed = get_option('TISG_speed');
	$TISG_dir = get_option('TISG_dir');
	$TISG_link = get_option('TISG_link');
	$siteurl_link = get_option('siteurl') . "/";

	if($TISG_link <> ""){
		$TISG_str_link = $TISG_link;
	}
	else{
		$TISG_str_link = "";
	}
	
	$TISG_dirHandle = opendir($TISG_dir);
	$TISG_count = 0;
	while ($TISG_file = readdir($TISG_dirHandle)) 
	{
	  if(!is_dir($TISG_file) && (strpos($TISG_file, '.jpg')>0 or strpos($TISG_file, '.gif')>0)) 
	  {
		 if($TISG_link == "" )
		 {
		 	$TISG_str =  '["' . $siteurl_link . $TISG_dir . $TISG_file . '", "", ""]';
		 }
		 else
		 {
		 	$TISG_str =  '["' . $siteurl_link . $TISG_dir . $TISG_file . '", "'. $TISG_link .'", "_new"]';
		 }
		 $TISG_returnstr = $TISG_returnstr . "TISG_content[$TISG_count]=$TISG_str; ";
	  	 $TISG_count++;
	  }
	} 
	
    
	closedir($TISG_dirHandle)
	
	?>
		<script type="text/javascript">

		var TISG_width='<?php echo $TISG_width; ?>px' //slideshow width
		var TISG_height='<?php echo $TISG_height; ?>px' //slideshow height
		var TISG_pause=<?php echo $TISG_delay; ?> //SET TISG_pause BETWEEN SLIDE (3000=3 seconds)
		var TISG_degree=<?php echo $TISG_speed; ?> //animation speed. Greater is faster.
		
		var TISG_content=new Array()
		
		<?php
		echo $TISG_returnstr;
		?>
		
		
		////-------------------------------------------------/////////////
		
		var TISG_bgcolor=''
		
		var TISG_imgholder=new Array()
		for (TISG_i=0;TISG_i<TISG_content.length;TISG_i++){
		TISG_imgholder[TISG_i]=new Image()
		TISG_imgholder[TISG_i].src=TISG_content[TISG_i][0]
		}
		
		var ie4=document.all
		var dom=document.getElementById&&navigator.userAgent.indexOf("Opera")==-1
		
		if (ie4||dom)
		document.write('<div style="position:relative;width:'+TISG_width+';height:'+TISG_height+';overflow:hidden"><div id="TISG_canvas0" style="position:absolute;background-color:'+TISG_bgcolor+';width:'+TISG_width+';height:'+TISG_height+';left:-'+TISG_width+';filter:alpha(opacity=20);-moz-opacity:0.2;"></div><div id="canvas1" style="position:absolute;background-color:'+TISG_bgcolor+';width:'+TISG_width+';height:'+TISG_height+';left:-'+TISG_width+';filter:alpha(opacity=20);-moz-opacity:0.2;"></div></div>')
		else if (document.layers){
		document.write('<ilayer id=tickernsmain visibility=hide width='+TISG_width+' height='+TISG_height+' bgColor='+TISG_bgcolor+'><layer id=tickernssub width='+TISG_width+' height='+TISG_height+' left=0 top=0>'+'<img src="'+TISG_content[0][0]+'"></layer></ilayer>')
		}
		
		var TISG_curpos=TISG_width*(-1)
		var TISG_curcanvas="TISG_canvas0"
		var TISG_curindex=0
		var TISG_nextindex=1
		
		function TISG_getslide(theslide){
		var slidehtml=""
		if (theslide[1]!="")
		slidehtml='<a href="'+theslide[1]+'" target="'+theslide[2]+'">'
		slidehtml+='<img src="'+theslide[0]+'" border="0">'
		if (theslide[1]!="")
		slidehtml+='</a>'
		return slidehtml
		}
		
		function moveslide(){
		if (TISG_curpos<0){
		TISG_curpos=Math.min(TISG_curpos+TISG_degree,0)
		tempobj.style.left=TISG_curpos+"px"
		}
		else{
		clearInterval(dropslide)
		if (TISG_crossobj.filters)
		TISG_crossobj.filters.alpha.opacity=100
		else if (TISG_crossobj.style.MozOpacity)
		TISG_crossobj.style.MozOpacity=1
		nextcanvas=(TISG_curcanvas=="TISG_canvas0")? "TISG_canvas0" : "canvas1"
		tempobj=ie4? eval("document.all."+nextcanvas) : document.getElementById(nextcanvas)
		tempobj.innerHTML=TISG_getslide(TISG_content[TISG_curindex])
		TISG_nextindex=(TISG_nextindex<TISG_content.length-1)? TISG_nextindex+1 : 0
		setTimeout("TISG_rotateslide()",TISG_pause)
		}
		}
		
		function TISG_rotateslide(){
		if (ie4||dom){
		resetit(TISG_curcanvas)
		TISG_crossobj=tempobj=ie4? eval("document.all."+TISG_curcanvas) : document.getElementById(TISG_curcanvas)
		TISG_crossobj.style.zIndex++
		if (TISG_crossobj.filters)
		document.all.TISG_canvas0.filters.alpha.opacity=document.all.canvas1.filters.alpha.opacity=20
		else if (TISG_crossobj.style.MozOpacity)
		document.getElementById("TISG_canvas0").style.MozOpacity=document.getElementById("canvas1").style.MozOpacity=0.2
		var temp='setInterval("moveslide()",50)'
		dropslide=eval(temp)
		TISG_curcanvas=(TISG_curcanvas=="TISG_canvas0")? "canvas1" : "TISG_canvas0"
		}
		else if (document.layers){
		TISG_crossobj.document.write(TISG_getslide(TISG_content[TISG_curindex]))
		TISG_crossobj.document.close()
		}
		TISG_curindex=(TISG_curindex<TISG_content.length-1)? TISG_curindex+1 : 0
		}
		
		function jumptoslide(which){
		TISG_curindex=which
		TISG_rotateslide()
		}
		
		function resetit(what){
		TISG_curpos=parseInt(TISG_width)*(-1)
		var TISG_crossobj=ie4? eval("document.all."+what) : document.getElementById(what)
		TISG_crossobj.style.left=TISG_curpos+"px"
		}
		
		function startit(){
		TISG_crossobj=ie4? eval("document.all."+TISG_curcanvas) : dom? document.getElementById(TISG_curcanvas) : document.tickernsmain.document.tickernssub
		if (ie4||dom){
		TISG_crossobj.innerHTML=TISG_getslide(TISG_content[TISG_curindex])
		TISG_rotateslide()
		}
		else{
		document.tickernsmain.visibility='show'
		TISG_curindex++
		setInterval("TISG_rotateslide()",TISG_pause)
		}
		}
		
		if (window.addEventListener)
		window.addEventListener("load", startit, false)
		else if (window.attachEvent)
		window.attachEvent("onload", startit)
		else if (ie4||dom||document.layers)
		window.onload=startit
		
		</script>
	<?php
	echo "<div style='padding:3px;'></div>";
}

function TISG_install() 
{
	add_option('TISG_title', "Slide Show");
	add_option('TISG_width', "200");
	add_option('TISG_height', "155");
	add_option('TISG_delay', "3000");
	add_option('TISG_speed', "10");
	add_option('TISG_dir', "wp-content/plugins/translucent-image-slideshow-gallery/gallery/");
	add_option('TISG_link', "");
}

function TISG_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('TISG_title');
	echo $after_title;
	TISG_slideshow();
	echo $after_widget;
}

function TISG_control() 
{
	$TISG_title = get_option('TISG_title');
	$TISG_width = get_option('TISG_width');
	$TISG_height = get_option('TISG_height');
	$TISG_delay = get_option('TISG_delay');
	$TISG_speed = get_option('TISG_speed');
	$TISG_dir = get_option('TISG_dir');
	$TISG_link = get_option('TISG_link');
	
	if ($_POST['TISG_submit']) 
	{
		$TISG_title = stripslashes($_POST['TISG_title']);
		$TISG_width = stripslashes($_POST['TISG_width']);
		$TISG_height = stripslashes($_POST['TISG_height']);
		$TISG_delay = stripslashes($_POST['TISG_delay']);
		$TISG_speed = stripslashes($_POST['TISG_speed']);
		$TISG_dir = stripslashes($_POST['TISG_dir']);
		$TISG_link = stripslashes($_POST['TISG_link']);
		
		update_option('TISG_title', $TISG_title );
		update_option('TISG_width', $TISG_width );
		update_option('TISG_height', $TISG_height );
		update_option('TISG_delay', $TISG_delay );
		update_option('TISG_speed', $TISG_speed );
		update_option('TISG_dir', $TISG_dir );
		update_option('TISG_link', $TISG_link );
	}
	
	echo '<p">Title:<br><input  style="width: 450px;" maxlength="100" type="text" value="';
	echo $TISG_title . '" name="TISG_title" id="TISG_title" /></p>';
	echo '<table width="490" border="0" cellspacing="0" cellpadding="2">';
	echo '<tr><td colspan="4" style="color:#999900;">';
	echo '<p>For best view, arrange the width perfectly to match with your site side bar. Set the height to the height of the LARGEST image in your slideshow!</p>';
	echo '</td></tr>';
	echo '<tr>';
	echo '<td>Width:</td>';
	echo '<td>Height:</td>';
	echo '<td>Speed:</td>';
	echo '<td>Delay:</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td><input  style="width: 75px;" maxlength="3" type="text" value="' . $TISG_width . '" name="TISG_width" id="TISG_width" />px</td>';
	echo '<td><input  style="width: 75px;" maxlength="3" type="text" value="' . $TISG_height . '" name="TISG_height" id="TISG_height" />px</td>';
	echo '<td><input  style="width: 75px;" maxlength="5" type="text" value="' . $TISG_speed . '" name="TISG_speed" id="TISG_speed" /></td>';
	echo '<td><input  style="width: 75px;" maxlength="5" type="text" value="' . $TISG_delay . '" name="TISG_delay" id="TISG_delay" />ms</td>';
	echo '</tr>';
	echo '</table>';
	echo '<p></p>';
	echo '<p>Image directory:<br><input  style="width: 450px;" type="text" value="';
	echo $TISG_dir . '" name="TISG_dir" id="TISG_dir" /><br>Default: wp-content/plugins/translucent-image-slideshow-gallery/gallery/</p>';
	echo '<p>All image link:<br><input  style="width: 450px;" type="text" value="';
	echo $TISG_link . '" name="TISG_link" id="TISG_link" /></p>';
	echo '<input type="hidden" id="TISG_submit" name="TISG_submit" value="1" />';
}

function TISG_widget_init() 
{
  	register_sidebar_widget(__('Translucent slideshow'), 'TISG_widget');   
	
	if(function_exists('register_sidebar_widget')) 	
	{
		register_sidebar_widget('Translucent slideshow', 'TISG_widget');
	}
	
	if(function_exists('register_widget_control')) 	
	{
		register_widget_control(array('Translucent slideshow', 'widgets'), 'TISG_control',500,500);
	} 
}

function TISG_deactivation() 
{
	delete_option('TISG_title');
	delete_option('TISG_width');
	delete_option('TISG_height');
	delete_option('TISG_delay');
	delete_option('TISG_speed');
	delete_option('TISG_dir');
	delete_option('TISG_link');
}

add_action("plugins_loaded", "TISG_widget_init");
register_activation_hook(__FILE__, 'TISG_install');
register_deactivation_hook(__FILE__, 'TISG_deactivation');
add_action('init', 'TISG_widget_init');
?>

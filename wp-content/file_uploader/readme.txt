=== File Uploader 1.0 Beta ===
Contributors: Linish 
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=8466J4XKK523J&lc=IN&item_name=Deepak&item_number=D100&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Author URL: http://www.altd.in
Tags: file uploader, categorized
Requires at least: wordpress 2.6
Tested up to: wordpress 2.7
Stable tag: 1.0
	
An accessible categorized file uploader plugin

== Description ==

 The File Uploader can be used for categorized uploading of files.  
Especially pdf files.The feature  of the File Uploader are listed below  
  
* Ease of use  
* Configurable Admin  
* Can assign icon to main category folders  
* Download counter for files  
* Shows files in each category  
* Paginated  
  
Tesed, and working, in Wordpress 2.7
	
== Installation ==

Download the plugin, Extract it and upload to your Wordpress plugins directory and activate.  
The plugin will create 2 tables and the tables will be deleted on deactivation  
of the plugin.  
  
copy the folder upload_files to your wordpress folder eg: inside http://localhost/wordpress  
  
copy the .htaccess file to your root folder or add this line in your .htaccess  
  
php\_value upload\_max_filesize 100M
== Frequently Asked Questions ==

= What is File Uploader? =  
  
File Uploader is a plugin that can be used for uploading pdf files in a categorized fashion.  
  
= How will i assign icons to categories? =  
  
You can do this while you creating the category from the add category menu  
  
= Can pagination controlled in user side and admin side? =  
  
Yes, the pagination properties can be managed from the admin side inside the "Settings" option  
  
= Is it FREE? =  
  
Yes
== Screenshots ==
1. Administrator screen shot

2. Main page screen shot

== Changelog ==

= version 1.0 Beta =

 - Fixed small bug of inserting icons to the category table

 - Enabled Permalink Support

== Usage ==  
  
To show categories in the sidebar paste the following code in sidebar.php  
  
&lt;?php echo show_files("SIDEBAR"); ?&gt;
  
To show the categories and randowm files in pages paste this code  
  
[fileuploader ALL]


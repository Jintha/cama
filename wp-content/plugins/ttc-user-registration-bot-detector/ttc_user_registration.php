<?php

/*
Plugin Name: TimesToCome Stop Bot Registration
Version: 1.8
Plugin URI:  http://herselfswebtools.com/2008/06/wordpress-plugin-to-prevent-bot-registrations.html
Description: Stop bots from registering as users
Author: Linda MacPhee-Cobb
Author URI: http://timestocome.com
*/


	
		
		// log all requests to register on our blog
		function ttc_add_to_log( $user, $error)
		{
		
			global $wpdb;
			$registration_log_table_name = $wpdb->prefix . "ttc_user_registration_log";
			$request_time = $_SERVER['REQUEST_TIME'];
			$http_accept = $_SERVER['HTTP_ACCEPT'];
			$http_user_agent = $_SERVER['HTTP_USER_AGENT'];
			$http_remote_addr = $_SERVER['REMOTE_ADDR'];


			if($wpdb->get_var("show tables like '$registration_log_table_name'") != $registration_log_table_name) {	
				ttc_wp_user_registration_install();
			}
					
			// wtf? accept statements coming in at over 255 chars?  Prevent sql errors and any funny business
			// by shortening anything from user to 200 chars if over 255 
			if ( strlen($email) > 200 ){ $email = substr ($email, 0, 200 ); }
			if ( strlen($http_accept ) > 200 ) { $http_accept = substr ( $http_accept, 0, 200 ); }
			if ( strlen($http_user_agent ) > 200 ) { $http_user_agent = substr ( $http_user_agent, 0, 200 ); }
			
			$sql = "INSERT INTO " . $registration_log_table_name . " ( ip, email, problem, accept, agent, day ) 
				VALUES ( '$http_remote_addr', '$user', '$error', '$http_accept', '$http_user_agent', NOW() )";
			$result = $wpdb->query( $sql );
		}



		// add an email to our email blacklist if we decide it is an bot
		function ttc_add_to_blacklist( $email )
		{
			global $wpdb;
			$blacklist_table_name = $wpdb->prefix . "ttc_user_registration_blacklist";
			

			if($wpdb->get_var("show tables like '$blacklist_table_name'") != $blacklist_table_name) {	
				ttc_wp_user_registration_install();
			}
			
			if ( strlen($email) > 200 ){ $email = substr ($email, 0, 200 ); }
					
			$sql = "INSERT INTO " . $blacklist_table_name . " ( blacklisted ) VALUES ( '$email' )";
			$result = $wpdb->query( $sql );
			
		}


		// add an ip to our ip blacklist if we decide it is a bot
		function ttc_add_to_ip_blacklist( $ip )
		{
			global $wpdb;
			$ip_table_name = $wpdb->prefix . "ttc_ip_blacklist";
			

			if($wpdb->get_var("show tables like '$ip_table_name'") != $ip_table_name) {	
				ttc_wp_user_registration_install();
			}
					
			$sql = "INSERT INTO " . $ip_table_name . " ( ip ) VALUES ( '$ip' )";
			$result = $wpdb->query( $sql );
		}


		//install tables if they are not already there to our wordpress db
		// and use to store black listed users and log what we are doing
		function ttc_wp_user_registration_install()
		{
					
			global $wpdb;
			$blacklist_table_name = $wpdb->prefix . "ttc_user_registration_blacklist";
			$registration_log_table_name = $wpdb->prefix . "ttc_user_registration_log";
			$ip_table_name = $wpdb->prefix . "ttc_ip_blacklist";
			$new_table = 0;


			if($wpdb->get_var("SHOW TABLES LIKE '$blacklist_table_name'") != $blacklist_table_name ) {
				
				$sql = "CREATE TABLE ". $blacklist_table_name ." (
					blacklisted varchar(255) UNIQUE
				);";


				$new_table = 1;
			}


			if($wpdb->get_var("SHOW TABLES LIKE '$registration_log_table_name'") != $registration_log_table_name) {
				
				$sql = "CREATE TABLE " . $registration_log_table_name . " (
					ip varchar(16),
					email varchar(255),
					problem int(3),
					accept varchar(255),
					agent varchar(255),
					day datetime
				);";

      			$new_table = 1;
			}



			if( $wpdb->get_var("SHOW TABLES LIKE '$ip_table_name'") != $ip_table_name ){
					
					$sql = "CREATE TABLE ". $ip_table_name ." (
						ip varchar(255) UNIQUE
					);";


				$new_table = 1;
			}



			if ( $new_table ){
				require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
	      		dbDelta($sql);
			}

		
		}





		// check out the email address and ip number of user requesting an account
		function ttc_user_check()
		{
		
		

			global $wpdb;
			$blacklisted = 0;
			$new_user = $_POST['user_email'];
		
			// check our email blacklist
			if ( $blacklisted == 0 ){
				$table = $wpdb->prefix . "ttc_user_registration_blacklist";
				$sql = "SELECT blacklisted FROM $table";
				$black_list = $wpdb->get_results( $sql );
						
				foreach ( $black_list as $blacklisted_user_email ){
					$bad_email = $blacklisted_user_email->blacklisted;
				
					// check full email
					if ( strcasecmp( $new_user, $bad_email ) == 0 ){

						$blacklisted = 1;

					// check parts of email address
					}else {
				
						$new_user_domain = explode( '@', $new_user);
						$new_user_domain = $new_user_domain[1];
	
						// check domain name
						if( strcasecmp ( $new_user_domain, $bad_email ) == 0){ 
							$blacklisted = 2;
						}
		
						// check tld
						$new_user_endswith = strrchr( $new_user, '.' );
							if( strcasecmp ( $new_user_domain, $bad_email ) == 0){ 
								$blacklisted = 3;
							}
					}
				}		
			}
			
			

			// check our ip blacklist
			if ( $blacklisted == 0 ){
				$ip_table = $wpdb->prefix . "ttc_ip_blacklist";
				$sql = "SELECT ip FROM $ip_table";
				$ip_black_list = $wpdb->get_results( $sql );
				$http_remote_addr = $_SERVER['REMOTE_ADDR'];
			
						
				foreach ( $ip_black_list as $blacklisted_ip ){
					$bad_ip = $blacklisted_ip->ip;				
					if ( strcasecmp( $http_remote_addr, $bad_ip ) == 0 ){

						$blacklisted = 16;

					}
				}
			}
			
			

			// check for multiple registrations from same ip address
			if ( $blacklisted == 0 ){
				$registration_table = $wpdb->prefix . "ttc_user_registration_log";
				$sql = "SELECT ip FROM $registration_table";
				$already_registered = $wpdb->get_results( $sql );
				foreach ( $already_registered as $duplicate_ip ){
			
					$dup_ip = $duplicate_ip->ip;			

					if ( strcasecmp( $http_remote_addr, $dup_ip ) == 0 ){
					
						$blacklisted = 17;
					}
				}
			}
			
			
			
			//  if it walks like a bot....
			if ( $blacklisted == 0 ){
			
				$http_accept = $_SERVER['HTTP_ACCEPT'];
				$http_accept = trim ( $http_accept );
				
				if ( strcasecmp( $http_accept, '*/*' ) == 0 ){
					$blacklisted = 18;
				}
			
			}
			
			
		
			/*
		    // most hosting companies don't allow file_get_contents - need alternative!
			// but if you can use it, do so.
			// check forumspam
			if ( $blacklisted == 0 ){
				$check = file_get_contents ( "http://www.stopforumspam.com/api?email=$new_user" );
				$test = "<appears>yes</appears>";
			
				if ( strpos( $check, $test) > 0 ) {
					$blacklisted = 13;
				}
			}
			*/


	/*      // uncomment this if you wish to check the spamhaus blacklist.
			// check spamhaus
			if ( $blacklisted == 0 ){
				$spam_haus  = 'zen.spamhaus.org';

				// valid query format is: ][reversed ip].zen.spamhaus.org

				$reverse_ip = array_reverse(explode('.', $http_remote_addr));
				$lookup = implode('.', $reverse_ip) . '.' . $spam_haus;

		
				if ($lookup == "127.0.0.2" ){ // direct ube sources, verified spam services and rokso spammers
					$blacklisted = 14;
				}else if (( $lookup == "127.0.0.4" )||( $lookup == "127.0.0.5" ) || ( $lookup == "127.0.0.6" )){ // 3rd party exploits
					$blacklisted = 15;
				}
			}
	*/

		
		
		
			//  -----  done checking now register or bounce application ------
			// if not black listed allow registration
			if ( $blacklisted == 0 ){
				
				ttc_add_to_log( $new_user, $blacklisted );
				
				// do nothing else wp registration will finish things up
							
			}else if ( $blacklisted < 10 ){							// already blacklisted here add to log
				
				//  add to log
				ttc_add_to_log(  $new_user, $blacklisted );
				
				// print error page
				print "<html>\n";
				print "<head><title>Restricted email address</title></head>\n";
				print "<body>\n";
				print "<h2> The email address you entered has been banned from registering at this site </h2>\n";
				print "</body>\n";
				print "</html>\n";
		    
				exit();

			}else{		// add to our blacklist and add to log
			
				// add to log
				ttc_add_to_log(  $new_user, $blacklisted );

				// add to our email blacklist and to our ip blacklist
				ttc_add_to_blacklist( $new_user );
				ttc_add_to_ip_blacklist( $http_remote_addr );
			
				//print  error page
				print "<html>\n";
				print "<head><title>Restricted</title></head>\n";
				print "<body>\n";
				print "<h2> You have been restricted from registering at this site </h2>\n";
				print "</body>\n";
				print "</html>\n";
		    
				exit();

			}

		}

		
		
		
		
		
		// wp manage page  -------------------------------------------------------------------------------
		function ttc_add_user_blacklist_menu_page()
		{	
			if ( function_exists('add_management_page')){
				add_management_page( 'Registration logs', 'Registration logs', 8, 'Registration Logs', 'ttc_add_user_registration_menu');
			}
		}
		
		
		// allow user to easily edit ( add or remove ) from blacklist
		// allow user to easily read what we've done and to purge log files
		function ttc_add_user_registration_menu()
		{
				global $wpdb;
				
				// how many log entries do we want?
				print "<table><tr><td>";
				print "<form method=\"post\">";
				print "Number of log entries to view: ";
				print "</td><td><input type=\"text\" name=\"log_lines\" maxlength=\"4\" size=\"4\">";
				print "</td><td><input type=\"submit\" value=\"Show Entries\">";
				print "<td><input type=\"hidden\" name=\"submit_check\" value=\"1\"></td>";
				print "</form>";
				print "</td></tr></table>";
				
				$log_count = 25;
				
				if ( $_POST['submit_check'] == 1 ){
					$log_count = $_POST['log_lines'];
				}
			
				
				$registration_log_table_name = $wpdb->prefix . "ttc_user_registration_log";
				$blacklist_table_name = $wpdb->prefix . "ttc_user_registration_blacklist";
				$ip_table_name = $wpdb->prefix . "ttc_ip_blacklist";

								
				// create tables if they don't already exist
				if($wpdb->get_var("SHOW TABLES LIKE '$blacklist_table_name'") != $blacklist_table_name ) {
						ttc_wp_user_registration_install();
				}
				if($wpdb->get_var("SHOW TABLES LIKE '$ip_table_name'") != $ip_table_name ) {
						ttc_wp_user_registration_install();
				}
				if($wpdb->get_var("show tables like '$registration_log_table_name'") != $registration_log_table_name) {	
						ttc_wp_user_registration_install();
				}

				// clean out logs and remove entries older than 8 days
				$sql = "DELETE FROM $registration_log_table_name WHERE day < (CURRENT_DATE - INTERVAL 8 DAY )";
				$deleted = $wpdb->get_results ( $sql );


				//fetch log information
				$sql = "SELECT ip, email, problem, accept, agent, date_format( day, '%M %d %Y %H:%i:%s') AS time_stamp FROM $registration_log_table_name ORDER BY day DESC LIMIT $log_count";
				$log = (array)$wpdb->get_results ( $sql );

				// print log files to the admin
				print "<br><br><br>Most recent log entries<br><br>";
			
				foreach ( $log as $log_entry ){
			
					$code = "";
					
					if( $log_entry->problem == 0 ){
						$code = "<font color=\"blue\">Registered: No known problems</font>";
					}else if( $log_entry->problem  == 1 ){
						$code = "<font color=\"red\"> Banned: Blacklisted email address</font>";
					}else if ( $log_entry->problem == 2 ){
						$code = "<font color=\"red\"> Banned: Blacklisted domain</font>";
					}else if ( $log_entry->problem == 3 ){
						$code = "<font color=\"red\"> Banned: Blacklisted email extension</font>";
					}else if ( $log_entry->problem == 13 ){
						$code = "<font color=\"red\"> Banned: Stop forum spam listed</font>";
					}else if ( $log_entry->problem == 14 ){
						$code = "<font color=\"red\"> Banned: Spamhaus verified spammer</font>";
					}else if ( $log_entry->problem == 15 ){
						$code = "<font color=\"red\"> Banned: Spamhaus known exploiter</font>";
					}else if ( $log_entry->problem == 16 ){
						$code = "<font color=\"red\"> Banned: Blacklisted ip address</font>";
					}else if ( $log_entry->problem == 17 ){
						$code = "<font color=\"red\"> Banned: Multiple registrations from same ip</font>";
					}else if ( $log_entry->problem == 18 ){
						$code = "<font color=\"red\"> Banned: Looks like a bot</font>";
					}
					
	
					print "<br>Email: <font color=\"darkblue\">$log_entry->email</font>";
					print "&nbsp;&nbsp;&nbsp;IP: <font color=\"olive\">$log_entry->ip</font>";
					print "<br>Accept: <font color=\"darkgreen\">$log_entry->accept</font>";
					print "<br>Agent: $log_entry->agent";
					print "<br>$code";
					print "&nbsp; &nbsp; &nbsp; <font color=\"olive\">$log_entry->time_stamp</font>";
					print "<br><hr>";
				}

				print "<br><br><br>";
				print "<table border=6><tr><td>";
				
				// print the email black list for editing and review to admin
				if ( isset( $_POST['ttc_blacklist_update'])){
					if( $emailblacklist = $_POST['emailblacklist'] ){
					
						$wpdb->query ( "DELETE FROM $blacklist_table_name WHERE 1=1" );
						$emailblacklist = explode( "\n", $emailblacklist );
					
						foreach ( $emailblacklist as $email ){
							$email = trim ( $email );
							if ( $email != "" ){
								$sql = "INSERT INTO " . $blacklist_table_name . " ( blacklisted ) VALUES ( '$email' ) ";
								$wpdb->query ( $sql );
							}
						}
					
					}
				}
				
				print "<form method=\"post\">";
				print "<table border=1><th>This is your email banished list:  <br>Add or remove emails as you wish<br>One per line <br>.info<br>@googlemail.com<br>muraskiken@gmail.com</th>";
				print "<tr><td><textarea name='emailblacklist' cols='30' rows='21' >";
				
				$sql = "SELECT blacklisted FROM $blacklist_table_name ORDER BY blacklisted";
				$blacklisted = (array)$wpdb->get_results( $sql );
				
				foreach( $blacklisted as $emails ){
					echo  $emails->blacklisted . "\n";
				}
				
				print "</textarea></td></tr><td>";
				print "<input type=\"submit\" name=\"ttc_blacklist_update\" value=\"Update blacklist\">";
				print "</form>";
				print "</td></tr></table>";
			
				if ( isset( $_POST['ttc_blacklist_update'])){
					if( $emailblacklist = $_POST['emailblacklist'] ){
					
						$wpdb->query ( "DELETE FROM $blacklist_table_name WHERE 1=1" );
						$emailblacklist = explode( "\n", $emailblacklist );
					
						foreach ( $emailblacklist as $email ){
							$email = trim ( $email );
							if ( $email != "" ){
								$sql = "INSERT INTO " . $blacklist_table_name . " ( blacklisted ) VALUES ( '$email' ) ";
								$wpdb->query ( $sql );
							}
						}
					
					}
				}
				

				print "</td><td>";
				
				
				
				// print the ip black list for editing and review to admin
				if( $ipblacklist = $_POST['ipblacklist'] ){
					$wpdb->query ( "DELETE FROM $ip_table_name WHERE 1=1" );
					$ipblacklist = explode( "\n", $ipblacklist );
					
					foreach ( $ipblacklist as $ip ){
						$ip = trim ( $ip );
						if( $ip != "" ){
							$sql = "INSERT INTO " . $ip_table_name . " ( ip ) VALUES ( '$ip' ) ";
							$wpdb->query ( $sql );
						}
					}
				}
				
				print "<form method=\"post\">";
				print "<table border=1><th>This is your ip banished list:  <br>Add or remove ips as you wish <br> One per line<br>77.10.106.4<br>78.129.208.100<br>10.10.255.255</th>";
				print "<tr><td><textarea name='ipblacklist' cols='30' rows='21' >";
				
				$sql = "SELECT ip FROM $ip_table_name ORDER BY ip";
				$blacklisted_ips = (array)$wpdb->get_results( $sql );
				
				foreach( $blacklisted_ips as $ips ){
					echo  $ips->ip . "\n";
				}
				
				print "</textarea></td></tr><td>";
				
				print "<input type=\"submit\" name=\"ttc_ip_blacklist_update\" value=\"Update IP blacklist\">";
				print "</form>";
				print "</td></tr></table>";

				print "</td></tr></table>";

			}			
									
		
		
		

		
		

add_action( 'register_post', 'ttc_user_check' );					// calls ttc_check_user when a new user registers
add_action( 'admin_menu', 'ttc_add_user_blacklist_menu_page' );		// add admin menu to user what we are doing

?>
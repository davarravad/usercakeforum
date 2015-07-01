<?php
////////////////////////////////////
//   UserCake Forum by DaVaR
//   http://www.thedavar.net
//   Version 1.0.1
//   Forum for User Cake 2.0.2
////////////////////////////////////

// Forum Functions

// Gets user_name or display_name from $ID02
// Set to send to var
function get_user_name_2($ID02){

	global $mysqli,$db_table_prefix;
	
	$stmt = $mysqli->prepare("SELECT 
		user_name, display_name
		FROM ".$db_table_prefix."users WHERE id=?");

	$stmt->bind_param("i", $ID02);
	$stmt->execute();

	$stmt->bind_result($print_user_name, $print_user_display_name);
	
	$stmt->fetch();
	$stmt->close();
	
	// Displays users user_name if display_name is not set
	if(!empty($print_user_display_name)){
		return $print_user_display_name;
	}else{
		return $print_user_name;
	}
	unset($print_user_display_name, $print_user_name);
}

// Gets user_name or display_name from $ID02
// Set to send to var
function get_user_email($ID02){

	global $mysqli,$db_table_prefix;
	
	$stmt = $mysqli->prepare("SELECT 
		email
		FROM ".$db_table_prefix."users WHERE id=?");

	$stmt->bind_param("i", $ID02);
	$stmt->execute();

	$stmt->bind_result($print_user_email);
	
	$stmt->fetch();
	$stmt->close();
	
	// Displays users user_name if display_name is not set
	if(!empty($print_user_email)){
		return $print_user_email;
	}
	unset($print_user_email);
}

// Total Topics Display Functions
function total_topics_display($forum_id){
	global $mysqli, $site_url_link, $site_forum_title, $db_table_prefix;

	// Get all Categories from database
	$query = "SELECT * FROM ".$db_table_prefix."forum_posts WHERE `forum_id`='$forum_id' ";
	
	if ($result = $mysqli->query("$query")) {

		/* determine number of rows result set */
		$row_cnt = $result->num_rows;

		printf("Topics:  %d \n", $row_cnt);

		/* close result set */
		$result->close();
	}

}

// Total Topic Replys Display Functions
function total_topic_replys_display($forum_id){
	global $mysqli, $site_url_link, $site_forum_title, $db_table_prefix;

	// Get all Categories from database
	$query = "SELECT * FROM ".$db_table_prefix."forum_posts_replys WHERE `fpr_id`='$forum_id' ";
	
	if ($result = $mysqli->query("$query")) {

		/* determine number of rows result set */
		$row_cnt = $result->num_rows;

		printf("Replys:  %d \n", $row_cnt);

		/* close result set */
		$result->close();
	}

}

// Total Topic Replys Per Topic Display Functions
function total_topic_replys_display_a($forum_post_id){
	global $mysqli, $site_url_link, $site_forum_title, $db_table_prefix;

	// Get all Categories from database
	$query = "SELECT * FROM ".$db_table_prefix."forum_posts_replys WHERE `fpr_post_id`='$forum_post_id' ";
	
	if ($result = $mysqli->query("$query")) {

		/* determine number of rows result set */
		$row_cnt = $result->num_rows;

		printf("Replys:  %d \n", $row_cnt);

		/* close result set */
		$result->close();
	}

}

// Function for displaying pages within a page
function display_pages_in_pages($dir, $page, $default, $load_cat, $load_id){
		// Setup page stuff
		$pee = $page;
		$pee1 = "${dir}/";
		$pee2 = ".php";
		$pee_file = "${pee1}${pee}${pee2}";
		if(!empty($pee)){
			if(!empty($pee)){
				if(file_exists($pee_file)) {
					require "$pee_file";
				} else {
					echo "
						<center>
						The page <font color=red>$pee</font> does NOT exist!<br>
						<br>
						Go back or go <a href='../'>Home</a></center>
					";
				}
			} else {
				echo "<br><center>Please select one of the above links corresponding to what you would like to do.</center><br>";
			}
		} else {
			$pee_file_2 = "${pee1}${default}${pee2}";
			require "$pee_file_2";
		}
}

// Page style functions 
// Lets make things a little easier for future additions

// Style for top of page content
// Sets the title and description of the page
function style_header_content($stc_page_title, $stc_page_description){

	// Title and Description
	//echo "<title>$stc_page_title</title>";
	echo "<meta name=\"description\" content=\"$stc_page_description\">";
	
	// Start the top of the content
	echo "<center>";
	echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td class='forum_main_head'>";
    
	// Display title of the page in header of page table
	echo "<h3>$stc_page_title</h3>"; 
	//echo "(Test Header: This page is using the new style setup!)";

	echo "</td></tr>";
	echo "<tr><td class='forum_main_body' align=center>";

}

// Style for bottom of page content
function style_footer_content(){

	// Close out the table
	//echo "(Test Footer: This page is using the new style setup!)";
	echo "</td></tr></table>";
	echo "</center>";

}

//START - Token valid function check
function is_valid_token()
{
	//Token Script by David (DaVaR) Sargent
	//davarravad@gmail.com
	//This script enables tokens within forms on a web site
	//I designed it to create a large random code
	//To use tokens withn in the form include this page
	//Then tell the script which part of the code goes where
	
	//The submition page
	//Use on pages where information is added to a database

	//Top of Submit Pages	

	//Unset the token just in case another one is on same page
	//unset($ses_taz_token_num);
	
	if(isset($_POST['session_token_num'])){
		$pos_taz_token_num = $_POST['session_token_num'];
		if(isset($_SESSION['user_token'][$pos_taz_token_num]['FT'])){
			$ses_taz_token_num = $_SESSION['user_token'][$pos_taz_token_num]['FT']; 
			global $debug_website;
			if($debug_website == 'TRUE'){ echo "<br> - DEBUG SITE ON - <BR>"; 
				echo $_SESSION['user_token'][$pos_taz_token_num]['FT'];
				echo " - $pos_taz_token_num - $ses_taz_token_num - <br>";
			}
		}
	}else{

		if(isset($_SESSION['user_token'][0]['FT'])){
			$ses_taz_token = $_SESSION['user_token'][0]['FT'];
		}else{
			$ses_taz_token = "NoSesToken";
		}
	
		$pos_taz_token_num = "NoSesTokenNUM";
	}

	if(isset($_POST['user_token'])){
		$pos_taz_token = $_POST['user_token'];
	}else{
		$pos_taz_token = "NoPosToken";
	}

	//Setup site validation and stuff
	//Make sure user is only using this website
	
	//Get the site url setting from config file
	global $websiteUrl;
	
	//Get the site url from server
	$site_url_server = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
	$site_url_server .= $_SERVER['SERVER_NAME'];
	$site_url_server .= "/";
	
	//Send the site url to session
	if(isset($_SESSION['websiteUrl'])){ $site_url_SES = $_SESSION['websiteUrl']; }else{ $site_url_SES = ""; }
	if(isset($_SESSION['site_url_server'])){ $site_url_server_SES = $_SESSION['site_url_server']; }else{ $site_url_server_SES = ""; }		

	
	//Ends the token session for better security	
	unset($_SESSION['user_token']);
	unset($_SESSION['websiteUrl']);
	unset($_SESSION['site_url_server']);

	//TESTING Echo!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! 
	if(!isset($ses_taz_token)){ $ses_taz_token = "NoSesTazToken"; }if(!isset($ses_taz_token_num)){ $ses_taz_token_num = "NoSesTazTokenNum"; }
	global $debug_website;
	if($debug_website == 'TRUE'){ 
		echo "<br> - DEBUG SITE ON - <BR>"; 
		echo "<Br><font color=purple size=0.1><br> - Ses: $ses_taz_token <br> Pos: $pos_taz_token <br> Num: $pos_taz_token_num <br> SesNum: $ses_taz_token_num - </font><br>";	
	}
	
	if(($ses_taz_token == $pos_taz_token || $ses_taz_token_num == $pos_taz_token) && ($websiteUrl == $site_url_SES && $site_url_server == $site_url_server_SES) && ($site_url_SES == $site_url_server_SES)) {
		return 1;
	}else{
		return 0;
	}

}
//END - Token valid function check

//START - Token form function <form>

//Creats a random string of chars.
function genRandomString() {
	$length = 15;
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	$maxrnd = strlen($characters)-1;
	$string = str_repeat('0', $length);
	for ($p = $length; $p--;) {
		$string[$p] = $characters[mt_rand(0, $maxrnd)];
	}
	return $string;
}
//Creats a random string of chars.
function genRandomString2() {
	$length = 15;
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	$maxrnd = strlen($characters)-1;
	$string = str_repeat('0', $length);
	for ($p = $length; $p--;) {
		$string[$p] = $characters[mt_rand(0, $maxrnd)];
	}
	return $string;
}

//Setup session form_token for array
			if(!isset($_SESSION['user_token'])){
				$_SESSION['user_token'] = array();
			}


//Token form token function
function form_token()
{

	//The form page
	//use with your submition form pages

	//Top of Form Pages	

	//setting the token for this submition

		//Create md5 string for even more security
		$token_md5 = md5(uniqid(rand(), true));
	
		// create unique token
		$form_token = uniqid();
 
			//making token extra better.
		
			srand ((double) microtime( )*1000000);
			$random_number = rand( );

			$ran_tk = genRandomString2();
			$ranimgname = genRandomString();
			
			//Sets generated randoms strings together	
			$ran_extoken = $ranimgname.$random_number;
			$form_token = $ran_tk.$form_token.$ran_extoken.$token_md5;
 
		// commit token to session
		//$_SESSION['user_token'] = $form_token;

		global $session_token_num;

		if(isset($session_token_num)){
				//echo "Session: $session_token_num";
			$_SESSION['user_token'][$session_token_num] = array('FT' => $form_token);
				//echo $_SESSION['user_token'][$session_token_num]['FT'];
			echo "<input type=\"hidden\" name=\"session_token_num\" value=\"$session_token_num\" />";
				global $debug_website;
				if($debug_website == 'TRUE'){ 
					echo "<br> ($session_token_num) ";
				}
			unset($session_token_num);
		}else{
			$_SESSION['user_token'][0] = array('FT' => $form_token);
			$session_token_num = "";				
		}

		//Setup site validation and stuff
		//Make sure user is only using this website
		
		//Get the site url setting from config file
		global $websiteUrl;
		
		//Get the site url from server
		$site_url_server = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		$site_url_server .= $_SERVER['SERVER_NAME'];
		$site_url_server .= "/";
		
		//Send the site url to session
		$_SESSION['websiteUrl'] = $websiteUrl;
		$_SESSION['site_url_server'] = $site_url_server;
		
		//TESTING Echo!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		global $debug_website;
		if($debug_website == 'TRUE'){ 
			echo " - DEBUG SITE ON - ";
			echo " $websiteUrl - $site_url_server ";
			echo "<font color=purple size=0.1> - $form_token - </font><br>";  //testing
		}
			
	echo "<input type=\"hidden\" name=\"user_token\" value=\"$form_token\" />";	

	//End of Token Process
}
//END - Token form function

// Function to display total number of views
function total_topic_views($view_id, $view_sec_id, $view_sub, $view_location){

	global $mysqli, $site_url_link, $site_forum_title, $userIdme;

	// Display and update view count for topic
	//Start View
	$addview = "";  //Enables adding views to post
	$view_location = "$view_location"; //Location on site where sweet is
	$view_id = "$view_id";  //Post Id number
	$view_userid = $_SERVER['REMOTE_ADDR'];  //User's Id
	$view_url = "${site_url_link}${site_forum_title}/display_topic/${view_id}/";
	$view_owner_userid = "$userIdme";  //Post owners userid
	require "models/views.php";
	//End View 
	
}

// Get users status from database based on id
function get_up_info_mem_status($get_info_id){

	// echo "Getting user info ($get_info_id)";

	// Get info from users
		
	global $mysqli,$db_table_prefix;
	
	$stmt = $mysqli->prepare("SELECT title FROM ".$db_table_prefix."users WHERE id=? LIMIT 1");

	$stmt->bind_param("i", $get_info_id);
	$stmt->execute();

	$stmt->bind_result($val);
	
	$stmt->fetch();
	$stmt->close();
	
	// Clean up the data
	$val = stripslashes($val);
	
	return "$val";
	
	unset($val);

}

?>
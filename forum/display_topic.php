<?php
////////////////////////////////////
//   UserCake Forum by DaVaR
//   http://www.thedavar.net
//   Version 1.0.3
//   Forum for User Cake 2.0.2
////////////////////////////////////

	// Main Page for forum
	//echo "Welcome to the forum for forum stuff. ($load_cat)($load_id)<hr>";
	
	// Check database for sections
	
	global $mysqli, $site_url_link, $site_forum_title, $userIdme, $db_table_prefix, $websiteName;
	global $session_token_num, $site_forum_main;

	//Get user subscription information
	$query_get_subcribe_info = "
		SELECT * FROM (
			 (
			 SELECT subcribe_email AS F_SUBCR, fpr_timestamp AS F_SUBCR_TS
			 FROM ".$db_table_prefix."forum_posts_replys 
			 WHERE fpr_post_id = '$load_cat' 
			 AND fpr_user_id = '$userIdme'
			 )
			 UNION ALL
			 (
			 SELECT subcribe_email AS F_SUBCR, forum_timestamp AS F_SUBCR_TS
			 FROM ".$db_table_prefix."forum_posts
			 WHERE forum_post_id = '$load_cat'
			 AND forum_user_id = '$userIdme'
			 ) 
		) AS uniontable
		
		ORDER BY `F_SUBCR_TS` DESC LIMIT 1
	";
	
			if($result_get_subcr = $mysqli->query($query_get_subcribe_info)){
				while ($row_get_subcr = $result_get_subcr->fetch_assoc()) {
					$usr_email_subcribe = $row_get_subcr['F_SUBCR']; 
					//echo "<br>-($usr_email_subcribe)-<br>";
				}
			}
			// Check to see if there was any data pulled from database for usr_email_subcribe
			if(empty($usr_email_subcribe)){ $usr_email_subcribe = ""; }
	
	// Get main post from database
	$query23 = "SELECT * FROM ".$db_table_prefix."forum_posts WHERE `forum_post_id`='$load_cat' LIMIT 1";
	$result23 = $mysqli->query($query23);
	while ($row23 = $result23->fetch_assoc()) {
		$f_p_id = $row23['forum_post_id'];
		$f_p_id_cat = $row23['forum_id'];
		$f_p_title = $row23['forum_title'];
		$f_p_content = $row23['forum_content'];
		$f_p_edit_date = $row23['forum_edit_date'];
		$f_p_timestamp = $row23['forum_timestamp'];
		$f_p_user_id = $row23['forum_user_id'];
		$f_p_status = $row23['forum_status'];
		$f_p_user_name = get_user_name_2($f_p_user_id);
		
		$f_p_title = stripslashes($f_p_title);
		$f_p_content = stripslashes($f_p_content);

		// Page title
		$stc_page_title = "$websiteName - Forum - $f_p_title";
		// Page Description
			// If year make model engine are set show them
			if(!empty($f_p_year) && !empty($f_p_make) && !empty($f_p_model) && !empty($f_p_engine)){
				$veh_info = "$f_p_year $f_p_make $f_p_model $f_p_engine - ";
			}else{
				$veh_info = "";
			}
		$stc_page_description = "${veh_info}${f_p_content}";
		// Run Top of page func
		style_header_content($stc_page_title, $stc_page_description);
		// Which database do we use
		$stc_page_sel = "forum";
		
			// Get all Category from database
			$stmt = $mysqli->prepare("SELECT 
				forum_cat, forum_des
				FROM ".$db_table_prefix."forum_cat WHERE forum_id=?");

			$stmt->bind_param("i", $f_p_id_cat);
			$stmt->execute();
			$stmt->bind_result($forum_cat, $forum_des);
			$stmt->fetch();
			$stmt->close();
			
				$f_cat = $forum_cat;
				$f_des = $forum_des;
				$f_id = $f_p_id_cat;

				$f_cat = stripslashes($f_cat);
				$f_des = stripslashes($f_des);
		
		// Display Link of where we are at on the forum
		echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td>";
			echo "<a href='${site_url_link}${site_forum_main}'>Forum Home</a> / ";
			echo "<a href='${site_url_link}${site_forum_main}?1=forum_display&2=$f_cat&3=$f_id/'>$f_cat</a> / ";
			echo "<a href=''>$f_p_title</a>";
			// Display Locked Message if Topic has been locked by admin
			forumTopicStatus('display_note', $f_p_status, NULL);
		echo "</td></tr></table>";
		
		echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td class=forum_title_head colspan=3>";
		echo "<strong>$f_p_title</strong><br>";
		echo "</td></tr><tr><td class='forum_title_body' width='100' valign='top'>";
			echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr>";
			echo "<td align='center' width='100px'>";
				// Show user main pic
				global $site_dir, $userIdme;
				$ID02 = $f_p_user_id;
				require('forum/userimage_small.php');
				echo "<br><a href='${site_url_link}member/$f_p_user_id/'>$f_p_user_name</a> ";

				//Show user's membership status
				$up_get_mem_status = get_up_info_mem_status($ID02);
				echo "<br> $up_get_mem_status ";
				
				// Display how long ago this was posted
				$timestart = "$f_p_timestamp";  //Time of post
				require_once "models/timediff.php";
				echo "<br><font color=green> " . dateDiff("now", "$timestart", 1) . " ago</font> ";
				echo "<br>";
			echo "</td></tr></table>";
		echo "</td><td class='forum_title_body' valign='top' width=''>";
		
				//Format the content with bbcode
				require_once('models/bbParser.php');
				$parser = new bbParser();
				$f_p_content_bb = $parser->getHtml($f_p_content);
		
		echo "<pre class='forum'>$f_p_content_bb</pre>";
		
		echo "</td><td class='forum_title_body' valign='top' width='100px'>";
		
			echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td align='center' valign='top'>";
			
				if($f_p_edit_date != NULL){
					// Display how long ago this was posted
					$timestart = "$f_p_edit_date";  //Time of post
					require_once "models/timediff.php";
					echo "<font color=red>Edited</font><br><font color=red> " . dateDiff("now", "$timestart", 1) . " ago</font> <br><br>";
				}

			echo "</td></tr><tr><td align='center' valign='bottom'>";
					
					
				// If user owns this content show forum buttons for edit and delete
				if(isUserLoggedIn()){
					global $userIdme;
					if($f_p_user_id == $userIdme || userCheckForumAdmin() || userCheckForumMod()){

						echo "</td></tr><tr><td align='center' valign='bottom'><br>";
					
							echo "<form enctype=\"multipart/form-data\" action=\"${site_url_link}${site_forum_main}?1=new_topic&2=${f_p_id_cat}/\" method=\"POST\" onsubmit=\"submitmystat.disabled = true; return true;\" class='sweetform' >";
								
								//Setup token in form
								// create multi sessions
								if(isset($session_token_num)){
									$session_token_num = $session_token_num + 1;
								}else{
									$session_token_num = "1";
								}
								form_token();

								echo "<input type=\"hidden\" name=\"id\" value=\"$f_p_id\" />";
								echo "<input type=\"hidden\" name=\"forum_post_id\" value=\"$f_p_id\" />";
								echo "<input type=\"hidden\" name=\"forum_id\" value=\"$f_p_id_cat\" />";
								echo "<input type=\"hidden\" name=\"edit_forum_topic\" value=\"TRUE\" />";

								echo "<input type=\"submit\" value=\"Edit\" name=\"Edit\" class=\"sweet\" onClick=\"this.value = 'Please Wait....'\" />";
							echo "</form>";
							
					} // End user own check
				} // End login check
			echo "</td></tr></table>";				
		echo "</td></tr></table>";
		

		// Start Get Page Number Stuff
		function getPagerData($numHits, $limit, $pnum) 
		{ 
			   $numHits  = (int) $numHits; 
			   $limit    = max((int) $limit, 1); 
			   $pnum     = (int) $pnum; 
			   $numPages = ceil($numHits / $limit); 

			   $pnum = max($pnum, 1); 
			   $pnum = min($pnum, $numPages); 

			   $offset = ($pnum - 1) * $limit; 

			   $ret = new stdClass; 

			   $ret->offset   = $offset; 
			   $ret->limit    = $limit; 
			   $ret->numPages = $numPages; 
			   $ret->pnum     = $pnum; 

			   return $ret; 
		} 

		// get pnum no from user to move user defined pnum    
		if(isset($_GET['pnum'])){ $pnum = $_GET['pnum']; }else{ $pnum = ""; } 
		
		// no of elements per page 
		$limit = 10; 

		// Query to get total num of mem being displayed
		$queryPN = "SELECT * FROM ".$db_table_prefix."forum_posts_replys WHERE `fpr_post_id`='$f_p_id' ORDER BY id ASC";
		
		// simple query to get total no of entries
		if ($result = $mysqli->query("$queryPN")) {

			/* determine number of rows result set */
			$total = $result->num_rows;

			// printf("Topics:  %d \n", $total);

			/* close result set */
			$result->close();
		}

		// work out the pager values 
		$pager  = getPagerData($total, $limit, $pnum); 
		$offset = $pager->offset; 
		$limit  = $pager->limit; 
		$pnum   = $pager->pnum; 
		
		// Global link to this page for page nums
		$cur_page_url_link = "Forum.php?1=display_topic&2=$f_p_id";
		
		// End Get Page Number Stuff
		
		
		// Get reply posts from database
		$query3 = "SELECT * FROM ".$db_table_prefix."forum_posts_replys WHERE `fpr_post_id`='$f_p_id' ORDER BY id ASC LIMIT $offset, $limit";
		if($result3 = $mysqli->query($query3)){
			
			
				// Display page count and links
				if($total > $limit){
					echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td align='center' valign='top' class='forum_title_head'>";
						echo "<center><table width=100%><tr><td align=left width='25%'>";
						// use $result here to output page content 

						// output paging system (could also do it before we output the page content) 
						if ($pnum == 1) // this is the first page - there is no previous page 
							; 
						else            // not the first page, link to the previous page 
							echo " < <a href=\"${site_url_link}${cur_page_url_link}&pnum=".($pnum - 1)."\">Previous</a> | "; 

						if ($pnum == $pager->numPages) // this is the last page - there is no next page 
							; 
						else            // not the last page, link to the next page 
							echo " <a href=\"${site_url_link}${cur_page_url_link}&pnum=".($pnum + 1)."\">Next</a> > "; 

						echo "</td><td align=center width='50%'>";
						
						// Setup page links display
						// Max Num Of Page Links
						$max = "5";
						if($pnum < $max){
							$sp = 1;
						}elseif($pnum >= ($pager->numPages - floor($max / 2)) ){
							$sp = $pager->numPages - $max + 1;
						}elseif($pnum >= $max){
							$sp = $pnum  - floor($max/2);
						}
						
						// Display page num links
						
						// Display link for first page if not currently viewing it
						if($pnum >= 2){
							echo " &lt;  <a href=\"${site_url_link}${cur_page_url_link}&pnum=1\">First</a>  ";
						}
						
						// If page 1 is not shown then show it here
						if($pnum >= $max){
							echo "<a href=\"${site_url_link}${cur_page_url_link}&pnum=1\" class='epboxa'>1</a>...";
						}

						if($pager->numPages > $max){
							// If greater than max display links
							// Show pages close to current page
							for ($i = $sp; $i <= ($sp + $max - 1); $i++) { 
								if ($i == $pager->pnum) 
									echo "<font color=green class='epbox'><strong>$i</strong></font>"; 
								else 
									echo "<a href=\"${site_url_link}${cur_page_url_link}&pnum=$i\" class='epboxa'>$i</a>"; 
							}
						}else{
							// If less than max display links
							for ($i = 1; $i <= $pager->numPages; $i++) { 
								if ($i == $pager->pnum) 
									echo "<font color=green class='epbox'><strong>$i</strong></font>"; 
								else 
									echo "<a href=\"${site_url_link}${cur_page_url_link}&pnum=$i\" class='epboxa'>$i</a>"; 
							}
						}
						
						// Show last two pages if not close to them
						if($pnum < ($pager->numPages - floor($max / 2))){
							echo "...<a href=\"${site_url_link}${cur_page_url_link}&pnum=$pager->numPages\" class='epboxa'>$pager->numPages</a>";
						}
						
						// Show last page link if not on it
						if($pnum < $pager->numPages){
							echo "  <a href=\"${site_url_link}${cur_page_url_link}&pnum=$pager->numPages\">Last</a> &gt;  ";
						}
						
						echo "</td><td align=right width='25%'>";
						$thetotal = ($offset + $limit);
						if($thetotal > $total){
							$thetotal2 = ($thetotal - $total);
							$thetotal3 = ($thetotal - $thetotal2);
							$thetotal = "$thetotal3";
						}
						echo "Showing $offset-$thetotal of $total Replys";
						echo "</td></tr></table>";
					echo "</td></tr></table>";
				} // End of pages check

			
			while ($row3 = $result3->fetch_assoc()) {
				$rf_p_main_id = $row3['id'];
				$rf_p_id = $row3['fpr_post_id'];
				$rf_p_id_cat = $row3['fpr_id'];
				$rf_p_content = $row3['fpr_content'];
				$rf_p_edit_date = $row3['fpr_edit_date'];
				$rf_p_timestamp = $row3['fpr_timestamp'];
				$rf_p_user_id = $row3['fpr_user_id'];
				$rf_p_user_name = get_user_name_2($rf_p_user_id);
				
				$rf_p_content = stripslashes($rf_p_content);
					echo "<a class='anchor' name='topicreply$rf_p_main_id'></a>";
					echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
					echo "<tr><td class='forum_title_body_b' width='100' valign='top'>";
						echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr>";
						echo "<td align='center'>";
							// Show user main pic
							$ID02 = $rf_p_user_id;
							echo "<br><a href='${site_url_link}member/$rf_p_user_id/'>$rf_p_user_name</a> ";

							//Show user's membership status
							$up_get_mem_status = get_up_info_mem_status($rf_p_user_id);
							echo "<br> $up_get_mem_status ";
							
							// Display how long ago this was posted
							$timestart = "$rf_p_timestamp";  //Time of post
							require_once "models/timediff.php";
							echo "<br><font color=green> " . dateDiff("now", "$timestart", 1) . " ago</font> ";
													
						echo "</td></tr></table>";
					echo "</td><td class='forum_title_body' valign='top'>";
					
					//Format the content with bbcode
					require_once('models/bbParser.php');
					$parser = new bbParser();
					$rf_p_content_bb = $parser->getHtml($rf_p_content);
					echo "<pre class='forum'>$rf_p_content_bb</pre>";
					
					echo "</td><td class='forum_title_body' valign='top' width='100'>";
					
						echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td align='center' valign='top'>";
						
							if($rf_p_edit_date != NULL){
								// Display how long ago this was posted
								$timestart = "$rf_p_edit_date";  //Time of post
								require_once "models/timediff.php";
								echo "<font color=red>Edited</font><br><font color=red> " . dateDiff("now", "$timestart", 1) . " ago</font> <br><br>";
							}

						echo "</td></tr><tr><td align='center' valign='bottom'>";
							
								
							// If user owns this content show forum buttons for edit and delete
							if(isUserLoggedIn()){
								global $userIdme;
								if($rf_p_user_id == $userIdme || userCheckForumAdmin() || userCheckForumMod()){

									echo "</td></tr><tr><td align='center' valign='bottom'><br>";
								
										echo "<form enctype=\"multipart/form-data\" action=\"${site_url_link}${site_forum_main}?1=new_topic&2=${f_p_id_cat}/\" method=\"POST\" onsubmit=\"submitmystat.disabled = true; return true;\" class='sweetform' >";
											
											//Setup token in form
											// create multi sessions
											if(isset($session_token_num)){
												$session_token_num = $session_token_num + 1;
											}else{
												$session_token_num = "1";
											}
											form_token();

											echo "<input type=\"hidden\" name=\"id\" value=\"$rf_p_main_id\" />";
											echo "<input type=\"hidden\" name=\"pnum\" value=\"$pnum\" />";
											echo "<input type=\"hidden\" name=\"forum_post_id\" value=\"$rf_p_id\" />";
											echo "<input type=\"hidden\" name=\"forum_id\" value=\"$rf_p_id_cat\" />";
											echo "<input type=\"hidden\" name=\"edit_forum_reply\" value=\"TRUE\" />";

											echo "<input type=\"submit\" value=\"Edit\" name=\"Edit\" class=\"sweet\" onClick=\"this.value = 'Please Wait....'\" />";
										echo "</form>";
										
								} // End user own check
							} // End login check
							
						echo "</td></tr></table>";
							
					echo "</td></tr></table>";
				
			} // End of replys display
		
			
				// Display page count and links
				if($total > $limit){
					echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td align='center' valign='top' class='forum_title_head'>";
						echo "<center><table width=100%><tr><td align=left width='25%'>";
						// use $result here to output page content 

						// output paging system (could also do it before we output the page content) 
						if ($pnum == 1) // this is the first page - there is no previous page 
							; 
						else            // not the first page, link to the previous page 
							echo " < <a href=\"${site_url_link}${cur_page_url_link}&pnum=".($pnum - 1)."\">Previous</a> | "; 

						if ($pnum == $pager->numPages) // this is the last page - there is no next page 
							; 
						else            // not the last page, link to the next page 
							echo " <a href=\"${site_url_link}${cur_page_url_link}&pnum=".($pnum + 1)."\">Next</a> > "; 

						echo "</td><td align=center width='50%'>";
						
						// Setup page links display
						// Max Num Of Page Links
						$max = "5";
						if($pnum < $max){
							$sp = 1;
						}elseif($pnum >= ($pager->numPages - floor($max / 2)) ){
							$sp = $pager->numPages - $max + 1;
						}elseif($pnum >= $max){
							$sp = $pnum  - floor($max/2);
						}
						
						// Display page num links
						
						// Display link for first page if not currently viewing it
						if($pnum >= 2){
							echo " &lt;  <a href=\"${site_url_link}${cur_page_url_link}&pnum=1\">First</a>  ";
						}
						
						// If page 1 is not shown then show it here
						if($pnum >= $max){
							echo "<a href=\"${site_url_link}${cur_page_url_link}&pnum=1\" class='epboxa'>1</a>...";
						}

						if($pager->numPages > $max){
							// If greater than max display links
							// Show pages close to current page
							for ($i = $sp; $i <= ($sp + $max - 1); $i++) { 
								if ($i == $pager->pnum) 
									echo "<font color=green class='epbox'><strong>$i</strong></font>"; 
								else 
									echo "<a href=\"${site_url_link}${cur_page_url_link}&pnum=$i\" class='epboxa'>$i</a>"; 
							}
						}else{
							// If less than max display links
							for ($i = 1; $i <= $pager->numPages; $i++) { 
								if ($i == $pager->pnum) 
									echo "<font color=green class='epbox'><strong>$i</strong></font>"; 
								else 
									echo "<a href=\"${site_url_link}${cur_page_url_link}&pnum=$i\" class='epboxa'>$i</a>"; 
							}
						}
						
						// Show last two pages if not close to them
						if($pnum < ($pager->numPages - floor($max / 2))){
							echo "...<a href=\"${site_url_link}${cur_page_url_link}&pnum=$pager->numPages\" class='epboxa'>$pager->numPages</a>";
						}
						
						// Show last page link if not on it
						if($pnum < $pager->numPages){
							echo "  <a href=\"${site_url_link}${cur_page_url_link}&pnum=$pager->numPages\">Last</a> &gt;  ";
						}
						
						echo "</td><td align=right width='25%'>";
						$thetotal = ($offset + $limit);
						if($thetotal > $total){
							$thetotal2 = ($thetotal - $total);
							$thetotal3 = ($thetotal - $thetotal2);
							$thetotal = "$thetotal3";
						}
						echo "Showing $offset-$thetotal of $total Replys";
						echo "</td></tr></table>";
					echo "</td></tr></table>";
				} // End of pages check
		
		} // End of sql resutls check
		
		// Check to see if Topic is locked. 
		// If Locked then disable the reply_topic
		if($f_p_status != "2"){
			// Display reply textarea
			require("forum/reply_topic.php");
		}else{
			echo "This Topic has been locked!<Br><br>";
		}
		
		// Display message that tells current user if they are subscribed to the current topic
		if($usr_email_subcribe == "NO"){
			echo "You are NOT subscribed to receive E-Mail notifications on this topic.";
		}
		if($usr_email_subcribe == "YES"){
			echo "You are subscribed to receive E-Mail notifications on this topic.";
			
			echo "<form enctype=\"multipart/form-data\" action=\"${site_url_link}${site_forum_main}&1=save_topic&2=${f_p_id_cat}\" method=\"POST\" onsubmit=\"submitmystat.disabled = true; return true;\" >";
				
				// create multi sessions
				if(isset($session_token_num)){
					$session_token_num = $session_token_num + 1;
				}else{
					$session_token_num = "1";
				}
				form_token();				
			
				echo "<input type=\"hidden\" name=\"forum_id\" value=\"$f_p_id_cat\" />";
				echo "<input type=\"hidden\" name=\"forum_post_id\" value=\"$f_p_id\" />";
				echo "<input type=\"hidden\" name=\"unsubscribe_topic\" value=\"TRUE\" />";
				echo "<input type=\"submit\" value=\"UnSubscribe\" name=\"UnSubscribe\" class=\"unsweet\" onClick=\"this.value = 'Please Wait....'\" />";
				
			echo "</form>";
			echo "<br>";
		}
		
		// Display and update view count for topic
		//Start View
		$addview = "yesaddview";  //Enables adding views to post
		$view_location = "forum"; //Location on site where sweet is
		$view_id = "$f_p_id";  //Post Id number
		$view_userid = $_SERVER['REMOTE_ADDR'];  //User's Id
		$view_url = "${site_url_link}${site_forum_main}?1=display_topic&2=${f_p_id}/";
		$view_owner_userid = "$userIdme";  //Post owners userid
		require "models/views.php";
		//End View 
		
		// Check to see if admin would like to lock or unlock this topic
		forumTopicStatus('admin_topic', $f_p_status, $f_p_id);
		
		// Run Footer of page func
		style_footer_content();	
			
	}

	


?>
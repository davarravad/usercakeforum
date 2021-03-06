<?php
////////////////////////////////////
//   UserCake Forum by DaVaR
//   http://www.thedavar.net
//   Version 1.0.3
//   Forum for User Cake 2.0.2
////////////////////////////////////

// Setup to be included on your home page.
// Use this on your home page if you like.

// Add Forum Functions
require("forum/forum_funcs.php");

// Header Default display
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("models/header.php");

	// Forum Recent Post for Right Side Bar

	// Check database for sections
	
	global $mysqli, $site_url_link, $db_table_prefix;
	
		// Which database do we use
		$stc_page_sel = "forum";
		$site_forum_title = "Forum";
		$site_forum_main = "Forum.php";
		
		echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td class='main_tbl_nav_links_title2'>";
		echo "<strong>Forum Recent Posts</strong><br>";
		echo "</td></tr><tr><td class='main_tbl_nav_links_sub'>";

			// Recent forum post limits
			if(isUserLoggedIn()){
				// How many recent posts to show if user is logged in
				$rp_limit = 10;
			}else{
				// How many recent posts to show if user is not logged in
				$rp_limit = 5;
			}
		
			// Get all Sub Categories for current category
			//$query = "SELECT * FROM ".$db_table_prefix."forum_posts WHERE `forum_id`='$f_id' ORDER BY forum_timestamp DESC";
			$query = "
				SELECT sub.*
				FROM
				(SELECT 
					fp.forum_post_id as forum_post_id, fp.forum_id as forum_id, 
					fp.forum_user_id as forum_user_id, fp.forum_title as forum_title, 
					fp.forum_content as forum_content, fp.forum_edit_date as forum_edit_date,
					fp.forum_timestamp as forum_timestamp, fpr.id as id,
					fpr.fpr_post_id as fpr_post_id, fpr.fpr_id as fpr_id,
					fpr.fpr_user_id as fpr_user_id, fpr.fpr_title as fpr_title,
					fpr.fpr_content as fpr_content, fpr.fpr_edit_date as fpr_edit_date,
					fpr.fpr_timestamp as fpr_timestamp,		
					GREATEST(fp.forum_timestamp, COALESCE(fpr.fpr_timestamp, '00-00-00 00:00:00')) AS tstamp
					FROM ".$db_table_prefix."forum_posts fp
					LEFT JOIN ".$db_table_prefix."forum_posts_replys fpr
					ON fp.forum_post_id = fpr.fpr_post_id
					ORDER BY tstamp DESC
				) sub
				
				GROUP BY forum_post_id
				ORDER BY tstamp DESC
				LIMIT $rp_limit
			";
			
			if($result = $mysqli->query($query)){
				while ($row2 = $result->fetch_assoc()) {
					$f_p_id = $row2['forum_post_id'];
					$f_p_id_cat = $row2['forum_id'];
					$f_p_title = $row2['forum_title'];
					$f_p_timestamp = $row2['forum_timestamp'];
					$f_p_user_id = $row2['forum_user_id'];
					$tstamp = $row2['tstamp'];
					$f_p_user_name = get_user_name_2($f_p_user_id);
					
					$f_p_title = stripslashes($f_p_title);

					//Reply information
					$rp_user_id2 = $row2['fpr_user_id'];
					$rp_timestamp2 = $row2['fpr_timestamp'];
					
					// Set the incrament of each post
					if(isset($vm_id_a)){ $vm_id_a++; }else{ $vm_id_a = '1'; };
					//echo "$vm_id_a";
					
					// Sets style between posts
					// Don't style first post
					if($vm_id_a == '1'){
						echo "<div class=''>";
					}else{
						echo "<div class='comt_cnt2'>";
						echo "</td></tr><tr><td class='main_tbl_nav_links_sub'>";
					}
					
					
					$f_p_title = strlen($f_p_title) > 19 ? substr($f_p_title, 0, 19) . ".." : $f_p_title;
					
					//If no reply show created by
					if($rp_timestamp2 == NULL){
						echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td valign='top' width='100%' class=''>";
							echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td align='left'>";
								//echo "($tstamp)"; // Test timestamp
								echo "$f_p_user_name created.. <br>";
								echo "<strong>";
								echo "<a href='${site_url_link}${site_forum_main}?1=display_topic&2=$f_p_id/' title='$f_p_title' ALT='$f_p_title'>$f_p_title</a>";
								echo "</strong>";
								echo "<br>";
								//Display how long ago this was posted
								$timestart = "$f_p_timestamp";  //Time of post
								require_once "models/timediff.php";
								echo " <font color=green> " . dateDiff("now", "$timestart", 1) . " ago</font> ";
								//echo "($f_p_timestamp)"; // Test timestamp					
							echo "</td></tr></table>";
						echo "</td></tr></table>";
					}else{
						$rp_user_name2 = get_user_name_2($rp_user_id2);
						//If reply show the following
						echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td valign='top' width='100%' class=''>";
							echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td align='left'>";
								//echo "($tstamp)"; // Test timestamp
								echo "$rp_user_name2 posted on.. <br>";
								echo "<strong>";
								echo "<a href='${site_url_link}${site_forum_main}?1=display_topic&2=$f_p_id/' title='$f_p_title' ALT='$f_p_title'>$f_p_title</a>";
								echo "</strong>";
								//Display how long ago this was posted
								$timestart = "$rp_timestamp2";  //Time of post
								require_once "models/timediff.php";
								echo "<br><font color=green> " . dateDiff("now", "$timestart", 1) . " ago</font> ";
								//echo "($rp_timestamp2)"; // Test timestamp
								unset($timestart, $rp_timestamp2);
							echo "</td></tr></table>";
						echo "</td></tr></table>";
					}// End reply check
					
					// End style for posts
					echo "</div>";
					
				} // End query
			} // End query check
				
		echo "</td></tr></table>";
			


?>
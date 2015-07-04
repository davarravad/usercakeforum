<?php
////////////////////////////////////
//   UserCake Forum by DaVaR
//   http://www.thedavar.net
//   Version 1.0.0
//   Forum for User Cake 2.0.2
////////////////////////////////////

	global $site_forum_title;
	// Page title
	$stc_page_title = $site_forum_title;
	// Page Description
	$stc_page_description = "Welcome to the Forum.  Ask questions and get answers from fellow Forum members.";
	// Run Top of page func
	style_header_content($stc_page_title, $stc_page_description);

	// Main Page for forum
	
	// Check database for sections
	
	global $mysqli, $site_url_link, $site_forum_title, $db_table_prefix;
	global $session_token_num, $stc_page_sel;
	
	// Make sure all forum titles are in order
	forumCleanOrderTitle();
	
	// Get all Categories from database
	$query_title = "SELECT * FROM ".$db_table_prefix."forum_cat WHERE `forum_name`='$stc_page_sel' GROUP BY forum_title ORDER BY `".$db_table_prefix."forum_cat`.`forum_order_title` ASC ";
	$result_title = $mysqli->query($query_title);
	while ($row_title = $result_title->fetch_assoc()) {
		$f_title = $row_title['forum_title'];
		$f_id = $row_title['forum_id'];
		$f_order_title = $row_title['forum_order_title'];
		
		// If Admin or mod is logged in check to 
		// see if categories are in order.
		// If not then fix it.
		forumCleanOrderCat($f_title);
		
		echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td>";
		echo "<table width='100%' border='0'  class='forum_title_head'><tr><td>";
		
		//Display title or edit title field if mod and requested
		forumEditTitleCheck($f_title);
		
		// Display Forum Title Edit Funcs.
		if(userCheckForumAdmin() || userCheckForumMod()){
			// Show admin feature if is admin
			// Display current order id
			echo "</td><td align='right'>";
			//echo "Order Id: $f_order_title ";
			echo "<table border='0'><tr><td width='140px' align='right'>";
				//Display Move Buttons
				forumMoveTitleOrder($f_order_title);
			echo "</td><td width='100px' align='right'>";
				//Display Edit/Delete Buttons
				forumEditTitle($f_title);
			echo "</td></tr></table>";
		}
		
		echo "</td></tr></table>";
		echo "</td></tr><tr><td>";

			$f_title_2 = addslashes($f_title);
		
			// Get all Sub Categories for current category
			$query = "SELECT * FROM ".$db_table_prefix."forum_cat WHERE `forum_title`='$f_title_2' GROUP BY forum_cat ORDER BY forum_order_cat";
			$result = $mysqli->query($query);
			while ($row2 = $result->fetch_assoc()) {
				$f_cat = $row2['forum_cat'];
				$f_des = $row2['forum_des'];
				$f_id2 = $row2['forum_id'];
				$cat_order_id = $row2['forum_order_cat'];
				
				$f_des = stripslashes($f_des);
				$f_cat = stripslashes($f_cat);
				
				echo "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='forum_title_body'><tr><td>";
					forumEditCatCheck($f_cat,$f_des,$f_id2);
				echo "</td><td width='75'>";
					// Display total number of topics for this category
					total_topics_display($f_id2);
					echo "<br>";
					// Display total number of topic replys for this category
					total_topic_replys_display($f_id2);
					// Display Edit, Delete, and Move Buttons
					forumCatEdit($f_id2,$f_title,$cat_order_id,$f_cat,$f_des,$f_id2);
				echo "</td></tr></table>";
			}
			// Display Create New Category Form
			forumCatNew($f_title);
			
		echo "</td></tr></table><br>";
			
	}
	forumCreateNewTopic();

// Run Footer of page func
style_footer_content();

?>
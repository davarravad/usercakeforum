<?php

// Forum for User Cake 2.0.2
// This forum allows users to chat about issues with their vehicles

// Debug Setting TRUE=ON FALSE=OFF
$debug_website = "FALSE";

// Add Forum Functions
require("forum/forum_funcs.php");

// Run page content

// Header Default display
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("models/header.php");

// Get current user id
if(isUserLoggedIn())
{
	$userIdme = $loggedInUser->user_id;
}

// CSS for Forum Tables
echo "
<STYLE type='text/css'>
table{
border: 0px;
padding: 0px;
}
td{
border: 0px;
padding: 0px;
}
.forum_main_head {
background-color: #999;
font-family:Verdana, Geneva, sans-serif;
font-size:12px;
font-weight:700;
color:#000;
border:1px solid #000000;
padding:4px;
}
.forum_main_body {
font-family:Verdana, Geneva, sans-serif;
font-size:12px;
color:#000;
border-color:#000000;
border-style:solid;
border-width:0px 1px 1px 1px;
padding:4px;
}
.forum_title_head {
background-color: #999;
font-family:Verdana, Geneva, sans-serif;
font-size:12px;
font-weight:700;
color:#000;
border:1px solid #000000;
padding:4px;
}
.forum_title_body {
font-family:Verdana, Geneva, sans-serif;
font-size:12px;
background-color: #CCC;
border-color:#000000;
border-style:solid;
border-width:0px 1px 1px 1px;
color:#000;
padding:4px;
}
.forum_title_body_b {
font-family:Verdana, Geneva, sans-serif;
font-size:12px;
background-color: #E9E9E9;
border-color:#000000;
border-style:solid;
border-width:0px 1px 1px 1px;
color:#000;
padding:4px;
}
.forum_title_body A:link {
text-decoration: none;
color: #333;
}
.forum_title_body A:visited {
text-decoration: none;
color: #666;
}
.forum_title_body A:hover {
text-decoration: underline;
color: #6A0000;
}
.forum_edit_buttons {
border:0px;
}
.forum_new_cat {
background-color: #E9E9E9;
font-family:Verdana, Geneva, sans-serif;
font-size:12px;
font-weight:700;
color:#000;
border-color:#000000;
border-style:solid;
border-width:0px 1px 1px 1px;
padding:4px;
}
.forum_new_title {
background-color: #E9E9E9;
font-family:Verdana, Geneva, sans-serif;
font-size:12px;
font-weight:700;
color:#000;
border:1px solid #000000;
padding:4px;
}
.forum_footer {
font-family:Verdana, Geneva, sans-serif;
font-size:12px;
border-color:#000000;
border-style:solid;
border-width:0px 1px 1px 1px;
color:#000;
padding:4px;
}
.forum_topic_list{
font-family:Verdana, Geneva, sans-serif;
font-size:12px;
background-color: #E9E9E9;
border-color:#000000;
border-style:solid;
border-width:1px 1px 1px 1px;
color:#000;
padding:2px;
margin-top: 2px;
}
pre.forum {
font-family:Verdana, Geneva, sans-serif;
font-size:11px;
width:700px;
}
pre.code {
font-family:Verdana, Geneva, sans-serif;
font-size:11px;
overflow:scroll;
}
.sweetform {
display:inline;
margin:0;
}
.sweet {
border:1px solid #7C8A95;
background-color:#FFF;
color:#360;
font-family:Verdana, Geneva, sans-serif;
font-size:11px;
cursor:pointer;
padding:0;
}
.unsweet {
border:1px solid #7C8A95;
background-color:#FFF;
color:red;
font-family:Verdana, Geneva, sans-serif;
font-size:11px;
cursor:pointer;
padding:0;
}
</style>
";

echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<h1>DaVaR Fish Tank Controller</h1>
<h2>Account</h2>
<div id='left-nav'>";

include("left-nav.php");

echo "
</div>
<div id='main'>
";

// Get which page user is requesting
if(isset($_REQUEST['1'])){ $load_page = $_REQUEST['1']; }else{ $load_page = ""; }
if(isset($_REQUEST['2'])){ $load_cat = $_REQUEST['2']; }else{ $load_cat = ""; }
if(isset($_REQUEST['3'])){ $load_id = $_REQUEST['3']; }else{ $load_id = ""; }

// Sets forum title
$site_forum_title = "Forum";
// set main forum file
$site_forum_main = "Forum.php";
// Set the dir where pages are loaded from
$load_page_dir = "forum";
// Set the page requested
$load_page_req = $load_page;
// Set the default page
$load_page_def = "main";
// Set the name of forum according to database uc_forum_cat.forum_name
$stc_page_sel = "forum";

// Add Forum Admin Functions
require("forum/forum_admin_funcs.php");

// Run the page function
display_pages_in_pages($load_page_dir, $load_page_req, $load_page_def, $load_cat, $load_id);

// Show Current Forum Permissions
forumDisplayUserPerms();

// Footer Default Display
echo "
</div>
<div id='bottom'></div>
</div>
</body>
</html>";
?>
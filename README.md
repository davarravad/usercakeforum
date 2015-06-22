# usercakeforum
<pre>
UserCake compatable forum.  Easy to use and lightweight.  

Compatable with User Cake 2.0.2
http://www.usercake.com

Designed by DaVaR
http://www.thedavar.net

Thanks to any and all contrabutions.

Install
1. Copy all files to your usercake website root folder.
2. Add Table to your MySQL database.
3. Configure the settings in the Forum.php file.
4. Start using the forum on your website.

Add the following to your MySQL database.
Note: Make sure to change the table prefix uc_ to match your database settings.
--
-- Table structure for table `uc_forum_cat`
--

CREATE TABLE IF NOT EXISTS `uc_forum_cat` (
  `forum_id` int(20) NOT NULL AUTO_INCREMENT COMMENT 'id of form thingy',
  `forum_name` varchar(255) NOT NULL COMMENT 'name of the full forum',
  `forum_title` varchar(255) NOT NULL COMMENT 'title of the forum sections',
  `forum_cat` varchar(255) NOT NULL COMMENT 'title of forum category',
  `forum_des` text NOT NULL COMMENT 'forum section description',
  `forum_perm` int(20) NOT NULL DEFAULT '1' COMMENT 'user permissions',
  `forum_order_title` int(11) NOT NULL,
  `forum_order_cat` int(11) NOT NULL,
  PRIMARY KEY (`forum_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Table structure for table `uc_forum_posts`
--

CREATE TABLE IF NOT EXISTS `uc_forum_posts` (
  `forum_post_id` int(20) NOT NULL AUTO_INCREMENT,
  `forum_id` int(20) NOT NULL,
  `forum_user_id` int(20) NOT NULL,
  `forum_title` varchar(255) NOT NULL,
  `forum_content` text NOT NULL,
  `forum_edit_date` varchar(20) DEFAULT NULL,
  `forum_year` varchar(255) NOT NULL,
  `forum_make` varchar(255) NOT NULL,
  `forum_model` varchar(255) NOT NULL,
  `forum_engine` varchar(255) NOT NULL,
  `subcribe_email` varchar(10) NOT NULL,
  `forum_timestamp` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`forum_post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Table structure for table `uc_forum_posts_replys`
--

CREATE TABLE IF NOT EXISTS `uc_forum_posts_replys` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `fpr_post_id` int(20) NOT NULL,
  `fpr_id` int(20) NOT NULL,
  `fpr_user_id` int(20) NOT NULL,
  `fpr_title` varchar(255) NOT NULL,
  `fpr_content` text NOT NULL,
  `subcribe_email` varchar(10) NOT NULL,
  `fpr_edit_date` varchar(20) DEFAULT NULL,
  `fpr_timestamp` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

Enjoy!
</pre>

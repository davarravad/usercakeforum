<?php
////////////////////////////////////
//   UserCake Forum by DaVaR
//   http://www.thedavar.net
//   Version 1.0.3
//   Forum for User Cake 2.0.2
////////////////////////////////////

global $websiteName, $websiteUrl, $emailAddress;

//Disables Email to user
$email_setting = "ON";  //ON or OFF


if($email_setting == 'OFF'){ echo "<br> - EMAIL OFF - <BR>"; }else{


	$sitemsg = "	<br>
					<br>
						This email was sent from $websiteName as a form of notification.
					<Br>
					<br>
						Visit and Login to $websiteUrl for more information!
					<br>
					<br>
						Please do not reply to this email.
					<br>
						Thank you and enjoy $websiteName
				";


	$body = "$usermsg <br><br> $usermsg2 <br><br> $sitemsg";

	$email = $adminmail;
	$email = $username . " <" . $email . ">\r\n";
	$subject = $usersub;
	$message = $body;
	
	// Send the email	
	$header = "MIME-Version: 1.0\r\n";
	$header .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$header .= "From: ". $websiteName . " <" . $emailAddress . ">\r\n";
	
	$message = wordwrap($message, 70);
	
	return mail($email,$subject,$message,$header);

}
?>


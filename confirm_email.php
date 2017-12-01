<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	//Default values for confirm_id and full_token. Confirm_id and full_token are drawn from the url of confirmation email
	$confirm_id = (isset($_GET["confirm_id"])) ? $_GET["confirm_id"] : 0;
	$full_token = (isset($_GET["full_token"])) ? trim($_GET["full_token"]) : "";
	
	if($confirm_id == 0 || $full_token == "")
	{
		header("Location: join_us.php");
		exit;
	}
	else {
			//Connect to the database
			require_once('Server_Includes/visitordbaccess.php');
			
			function this_member_has_pending_confirmation($data1, $data2)
			{
				global $db;
				$query = 'SELECT member_id FROM gv_email_change_confirmation WHERE (email_change_id = ' . mysql_real_escape_string($data1, $db) . ') AND (email_change_full_token = "' . mysql_real_escape_string($data2, $db) . '")';
				$result = mysql_query($query, $db) or die (mysql_error($db));
				extract($row);
				return $member_id;
			}
			
			$member_id = this_member_has_pending_confirmation($confirm_id, $full_token);
			
			if($member_id == null)
			{
				$query = 'DELETE FROM gv_email_change_confirmation WHERE email_change_id = ' . mysql_real_escape_string($confirm_id, $db) . ' AND email_change_full_token = "' . mysql_real_escape_string($full_token, $db) . '"';
				mysql_query($query, $db) or die (mysql_error($db));
				
				$_SESSION["errand_title"] = "Congratulations!";
				$_SESSION["errand"] = "Your email address is now verified.";
				$_SESSION["errand"] .= '<br><a href="services.php">Click here to continue.</a>';
				header("Location: issues.php");
				exit;
				mysql_close();
			}
			else {
					$_SESSION["errand_title"] = "Sorry. There's a problem.";
					$_SESSION["errand"] = "Your credentials are invalid.<br/>";
					$_SESSION["errand"] .= "Log in to change your email address.";
					$_SESSION["errand"] .= '<br><a href="services.php">Click here to continue.</a>';
					header("Location: issues.php");
					exit;	
					mysql_close();
			}
	}
?>
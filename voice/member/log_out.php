<?php

	//Get custom error function script 
	require_once('../../Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	if(!isset($_SESSION["logged_in"]))
	{
		//Take this visitor to the log in page because he's not logged in
		header("Location: ../log_in.php");
		exit;
	}
	
	//Connect to the database		
	require_once('../../Server_Includes/visitordbaccess.php');
	
	//Save current datetime as the last login property
	$query = 'UPDATE gv_members SET last_login = NOW() WHERE member_id = ' . $_SESSION["member_id"];
	mysql_query($query, $db) or die(mysql_error($db));
	mysql_close();
	
	//This applies to log out procedure not involving a logout button
	//First of all, empty all variables that have been set
	$_SESSION = array();
	
/*	//Invalidate all the cookies
	if(isset($_SESSION[session_name]))
	{
		setcookie(session_name(), '', time()-86400, '/');
	}
*/	
	//End this session and redirect the member away from secure pages
	session_destroy();
	header("Location: ../home.php");
exit;
?>
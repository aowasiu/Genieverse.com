<?php

	$year = date('Y') + 1;
	$cookie_expiration = mktime(0,0,0,1,1,$year);

/*
	if(!isset($_SESSION['logged_in']))
	{
		//This visitor is a guest because he's not logged in
		setcookie("username","",$cookie_expiration);
		setcookie("country_name","Nigeria",$cookie_expiration);
		setcookie("state","Lagos",$cookie_expiration);
		setcookie("city","Ikeja",$cookie_expiration);
		setcookie("favourite_service","Mall",$cookie_expiration);
	}
	else {
			//In this case $_SESSION['logged_in'] == 1, the visitor is logged in. Therefore, he's a member
			$cookie_username 			= $_COOKIE['username'];
			$cookie_country_name 		= $_COOKIE['country_name'];
			$cookie_country_id	 		= $_COOKIE['country_id'];
			$cookie_state				= $_COOKIE['state'];
			$cookie_city 				= $_COOKIE['city'];
			$cookie_favourite_service	= $_COOKIE['favourite_service'];
	}
*/
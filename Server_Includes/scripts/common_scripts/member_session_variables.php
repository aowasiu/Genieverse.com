<?php

	//Default SESSION values for non-logged-in member
	$_SESSION['username']			= $row['username'];
	$_SESSION['logged_in']			= 1;
	$_SESSION['country_id']			= $row['country_id'];
	$_SESSION['privilege']			= $row['privilege'];
	$_SESSION['member_id']			= $row['member_id'];
//	$_SESSION['state_name']			= $row['state_name'];
//	$_SESSION['city_name']			= $row['city_name'];
//	$_SESSION['firstname']			= $row['firstname'];
//	$_SESSION['favourite_service']	= $row['favourite_service'];

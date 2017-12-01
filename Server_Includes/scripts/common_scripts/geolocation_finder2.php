<?php

	require_once('../Server_Includes/scripts/common_scripts/ip_locator.php');

	$ip_number = sprintf("%u", ip2long($remote));
			
	$query = 'SELECT cc_id, country_name FROM geoipwhois_ip NATURAL JOIN geoipwhois_cc WHERE ' . $ip_number . ' BETWEEN number_start AND number_end';
	$result = mysql_query($query, $db) or die(mysql_error($db));
		
	$row = mysql_fetch_assoc($result);
	
	if(mysql_num_rows($result) > 0)
	{
		$country_name = $row['country_name'];
		$country_id = $row['cc_id'];
	}
	else{
			$country_name = 'Nigeria';
			$country_id = 72;
	}
	
	$_SESSION['session_country_name'] = $country_name;
	$_SESSION['session_country_id'] = $country_id;
	
	//$country_name;
	//$country_id;
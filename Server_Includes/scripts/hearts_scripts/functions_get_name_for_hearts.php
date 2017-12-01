<?php

	//This function gets the name of a category
	function category_name($data)
	{
		global $db;
		$query = 'SELECT hearts_category_name FROM gv_hearts_category WHERE hearts_category_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $hearts_category_name;
	}

	//Define country_name function
	function country_name($data)
	{
		global $db;
		$query = 'SELECT country_name FROM geoipwhois_cc WHERE cc_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $country_name;
	}

	function hearts_location($data)
	{
		global $db;		
		$query = 'SELECT date_of_birth, state_name, city_name, cc_id FROM gv_member_profile WHERE profile_id = ' . $data;
		$resultLocation = mysql_query($query) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $date_of_birth . '@' . $city_name . '@' . $state_name . '@' $cc_id;
	}

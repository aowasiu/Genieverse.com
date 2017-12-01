<?php

	//This script is an embodiment of ID-aqcuiring functions
	//This function gets the ID of a member
	function member_country_id($data)
	{
		global $db;
		$query = 'SELECT cc_id FROM gv_member_profile WHERE member_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $cc_id;
	}
	
	function category_id($data1, $data2, $data3, $data)
	{
		global $db;
		$query = 'SELECT '. $data1 . ' FROM ' . $data2 . ' WHERE ' . $data3 . ' = "' . $data . '"';
		$result = mysql_query($query, $db) or die (mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $data1;
		
		//USAGE =>  category_id(mall_category_id, gv_mall_category, mall_category_name, $data);
	}
	
	function country_name_id($data)
	{
		global $db;
		$query = 'SELECT cc_id FROM geoipwhois_ip WHERE country_name = "' . $data . '"';
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $cc_id;
		
		//USAGE =>  country_name_id("Nigeria");
	}
<?php

	//This script is an embodiment of name-aqcuiring functions
	//This function gets the name of a category
	function category_name($column_name, $table_name, $column_id, $data)
	{
		global $db;
		$query = 'SELECT ' . $column_name . ' FROM ' . $table_name . ' WHERE ' . $column_id . ' = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		$the_answer = 	extract($row);
		return $the_answer;
		
		/*//USAGE =>
		category_name('mall_category_name', 'gv_mall_category', 'mall_category_id', $row["mall_category_id"])
		*/
	}

	//This function gets the name of a member
	function member_username($data)
	{
		global $db;
		$query = 'SELECT username FROM gv_members WHERE member_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $username;
	}
	
	function recipients_username($data)
	{
		global $db;
		$query = 'SELECT username FROM gv_members WHERE member_id = ' . mysql_real_escape_string($data, $db);
		$result = mysql_query($query, $db) or die(mysql_error($db));
		if($result)
		{
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_assoc($result);
				extract($row);
				$name = $username;
			}
			else {
				$name = "";
			}
		}
		return $name;
	}
	
	function firstname($data)
	{
		global $db;
		$query = 'SELECT firstname FROM gv_member_profile WHERE member_id = ' . $data;
		$result = mysql_query($query, $db) or die (mysql_error($db));
		if($result = mysql_query($query))
		{
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_assoc($result);
				extract($row);
				$name = $firstname;
			}
		}
		return $name;
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
	
	//Define continent_name function
	function continent_name($data)
	{
		global $db;
		$query = 'SELECT continent_name FROM gv_continent WHERE continent_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $continent_name;
	}

	function service_name($data)
	{
		global $db;
		$query = 'SELECT service_name FROM gv_service WHERE service_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $service_name;
	}

	function favourite_service_name($data)
	{
		global $db;
		$query = 'SELECT service_name FROM gv_service WHERE service_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $service_name;
	}

	function the_username($data)
	{
		global $db;
		$query = 'SELECT username FROM gv_members WHERE member_id = ' . mysql_real_escape_string($data, $db);
		$result = mysql_query($query, $db) or die(mysql_error($db));
		
		if(mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_assoc($result);
			extract($row);
			$name = $username;
		}
		if(mysql_num_rows($result) < 1)
		{
			$name = "";
		}
		return $name;
	}
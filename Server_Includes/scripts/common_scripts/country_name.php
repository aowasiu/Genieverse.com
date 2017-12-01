<?php

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

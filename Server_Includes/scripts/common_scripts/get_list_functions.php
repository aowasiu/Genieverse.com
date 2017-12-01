<?php

	function country_list()
	{
		global $db;
		//Get list of countries
		$query = 'SELECT cc_id, country_name FROM geoipwhois_cc WHERE (is_country = 1) AND (is_activated = 1) ORDER BY country_name';
		$result = mysql_query($query, $db) or die(mysql_error($db));
	
		//$row = mysql_fetch_assoc($result);
		
		echo '<select class="form-control" name="country_id">' . '<option value="">Select country</option>';
		
		while($row = mysql_fetch_assoc($result))
		{
			echo  "\r" . '<option value="' .$row['cc_id'] . '">' . $row['country_name'] . '</option>';
		}
	
		echo "\r" . '</select>';
	}

	function service_list()
	{
		global $db;
		//Get list of services
		$query = 'SELECT service_id, service_name FROM gv_service WHERE (is_active = 1)';
		$result = mysql_query($query, $db) or die(mysql_error($db));
	
		//$row = mysql_fetch_assoc($result);
		
		echo '<select class="form-control" name="service_id">' . '<option value="">Select service</option>';
		
		while($row = mysql_fetch_assoc($result))
		{
			echo  "\r" . '<option value="' .$row['service_id'] . '">' . $row['service_name'] . '</option>';
		}
	
		echo "\r" . '</select>';
	}

/*	function favourite_service_list()
	{
		global $db;
		//Get list of favourite services
		$query = 'SELECT service_id, service_name FROM gv_service WHERE is_active = 1 ORDER BY service_name';
		$result = mysql_query($query, $db) or die(mysql_error($db));
	
		$row = mysql_fetch_assoc($result);
		
		echo '<select class="form-control" name="favourite_service_id">';
		
		while($row = mysql_fetch_assoc($result))
		{
			echo  "\r" . '<option value="' .$row['service_id'] . '">' . $row['service_name'] . '</option>';
		}
	
		echo "\r" . '</select>';
	}
*/
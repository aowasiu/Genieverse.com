<?php

	function currency_code($data, $data1)
	{
		global $db;
		$query = 'SELECT currency_code FROM geoipwhois_cc WHERE cc_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		if($data1 !== "")
		{
			extract($row);
			$code = $currency_code;
		}
		else{
				$code = "";
		}
		return $code;
	}
	
	//Example: echo currency_code($row['country_id'], $category_id);
<?php

	//This script details information on required currency
	//This function gets the symbol for a currency
	function currency_symbol($data)
	{
		$query = 'SELECT currency_symbol FROM geoipwhois_cc WHERE country_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $currency_symbol;
	}
	
	function currency_code($data)
	{
		$query = 'SELECT currency_code FROM geoipwhois_cc WHERE country_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $currency_code;
	}
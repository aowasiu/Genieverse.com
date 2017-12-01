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

<?php

	//This function gets the name of a category
	function category_name($data)
	{
		global $db;
		$query = 'SELECT spotlight_category_name FROM gv_spotlight_category WHERE spotlight_category_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $spotlight_category_name;
	}

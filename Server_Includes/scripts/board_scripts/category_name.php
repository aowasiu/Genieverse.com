<?php

	//This function gets the name of a category
	function category_name($data)
	{
		global $db;
		$query = 'SELECT board_category_name FROM gv_board_category WHERE board_category_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $board_category_name;
	}

<?php

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

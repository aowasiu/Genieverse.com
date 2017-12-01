<?php

	//The total view incrementing function definition
	function total_view_incrementing($data, $table_name, $count_column, $column_targeted_for_data)
	{
		global $db;
		$query = 'UPDATE ' . $table_name . ' SET  ' . $count_column . '=' . $count_column . ' + 1 WHERE ' . $column_targeted_for_data . ' = ' . mysql_real_escape_string($data);
		mysql_query($query, $db) or die(mysql_error());
		//total_view_incrementing($data, $table_name, $count_column, $column_targeted_for_data)
	}

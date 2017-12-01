<?php

	function posters_total_post_count($data, $table_name, $column_to_count, $data_replacement)
	{
		global $db;
		$query = 'SELECT ' . $column_to_count . ' AS totalCount FROM ' . $table_name . ' WHERE ' . $data_replacement . ' = ' . mysql_real_escape_string($data);
		$result = mysql_query($query, $db);
		$numberOfRows = mysql_num_rows($result);
		return $numberOfRows;
		//posters_total_post_count($row["member_id"], 'gv_voice_post', 'voice_post_id', 'member_id')
	}

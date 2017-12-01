<?php

	//This function gets the name of a category
	function heartsLocation($data)
	{
		global $db;
		$query = 'SELECT cc_id, state_name, city_name FROM gv_member_profile WHERE member_profile_delete = 0 AND profile_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		
		//Get functions
		require_once('../Server_Includes/scripts/common_scripts/get_name_functions.php');
		$heartsLocation = '							<p>' . $row["city_name"] . ', ' . $row["state_name"] . '</p>
							<p>' . country_name($row["cc_id"]) . '.</p>';
		echo $heartsLocation;
	}
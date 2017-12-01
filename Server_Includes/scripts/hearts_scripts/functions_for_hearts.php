<?php

	//Gets category_id for profile from hearts profile table
	function hearts_category_id($data)
	{
		global $db;
		$query = 'SELECT hearts_category_id FROM gv_hearts_profile WHERE hearts_profile_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $hearts_category_id;
	}

	//Total Featured profiles
	function featured_profiles_number($data)
	{
		global $db;
		$query = 'SELECT hearts_profile_id FROM gv_hearts_profile WHERE (hearts_member = 1 AND hearts_profile_block = 0) AND (hearts_featured_flag = 1)';
		$result = mysql_query($query, $db) or die(mysql_error($db));

		if(mysql_num_rows($result) > 0)
		{
			$number_of_profiles = mysql_num_rows($result);
		}
		if(mysql_num_rows($result) < 1)
		{
			$number_of_profiles = 0;
		}
		//$number_of_profiles = mysql_num_rows($result);
		return $number_of_profiles;
	}

	//Total profiles view
	function my_profile_visit_total($data)
	{
		global $db;
		$query = 'SELECT hearts_profile_view_count FROM gv_hearts_profile WHERE (hearts_profile_block = 0 AND hearts_member = 1) AND (member_id = ' . mysql_real_escape_string($data) . ')';
		$result = mysql_query($query, $db) or die(mysql_error($db));
		if(mysql_num_rows($result) > 0)
		{
			$number_of_views = mysql_num_rows($result);
		}
		if(mysql_num_rows($result) < 1)
		{
			$number_of_views = 0;
		}
		//$number_of_profiles = mysql_num_rows($result);
		return $number_of_views;
	}
		
	//In case this member is a moderator, fetch his moderator points from the database
	function moderation_points($data)
	{
		global $db;
		$query = 'SELECT mod_points FROM gv_mod_points WHERE member_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		if(mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_assoc($result);
			extract($row);
			$points = $mod_points;
		}
		if(mysql_num_rows($result) == 0)
		{
				$points = 0;
		}
		return $points;
	}

	//Check if the provided category exists
	function check_if_category_name_exists($data)
	{
		global $db;
		$query = 'SELECT hearts_category_name FROM gv_hearts_category WHERE hearts_category_id = ' . $data;
		$result = mysql_query($query, $db) or die (mysql_error($db));
		if(mysql_num_rows($result) < 1)
		{
			$the_text = "Dude you're being hacked";
		}
		else{
				$row = mysql_fetch_assoc($result);
				$the_text = $row["hearts_category_name"];
		}
		return $the_text;
	}

	//Check if the provided category exists
	function does_category_name_exist($data)
	{
		global $db;
		$query = 'SELECT hearts_category_id FROM gv_hearts_category WHERE hearts_category_name = "' . $data . '"';
		$result = mysql_query($query, $db) or die (mysql_error($db));
		if(mysql_num_rows($result) < 1)
		{
			$the_number = 0;
		}
		else{
				$row = mysql_fetch_assoc($result);
				$the_number = $row["hearts_category_id"];
		}
		return $the_number;
	}
	
	//Check if post requested is this member's post
	function is_profile_id_members_own($data)
	{
		global $db;
		$query = 'SELECT member_id FROM gv_hearts_profile WHERE hearts_profile_block = 0 AND hearts_profile_id = ' . mysql_real_escape_string($data, $db);
		$result = mysql_query($query, $db) or die (mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $member_id;
	}

<?php

	//Gets category_id for post from Mall post table
	function mall_category_id($data)
	{
		global $db;
		$query = 'SELECT mall_category_id FROM gv_mall_post WHERE mall_post_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $mall_category_id;
	}

	//Total Active posts checker
	function active_posts_number($data)
	{
		global $db;
		$query = "SELECT mall_post_id FROM gv_mall_post WHERE mall_post_block = 0 AND mall_post_activation = 1 AND member_id = $data";
		$result = mysql_query($query, $db) or die(mysql_error($db));
		if(mysql_num_rows($result) > 0)
		{
			$number_of_posts = mysql_num_rows($result);
		}
		if(mysql_num_rows($result) < 1)
		{
			$number_of_posts = 0;
		}
		//$number_of_posts = mysql_num_rows($result);
		return $number_of_posts;
	}
	
	//Total Inactive posts checker
	function inactive_posts_number($data)
	{
		global $db;
		$query = "SELECT mall_post_id FROM gv_mall_post WHERE (mall_post_block = 0 AND mall_post_activation = 0) AND member_id = $data";
		$result = mysql_query($query, $db) or die(mysql_error($db));
		if(mysql_num_rows($result) > 0)
		{
			$number_of_posts = mysql_num_rows($result);
		}
		if(mysql_num_rows($result) < 1)
		{
			$number_of_posts = 0;
		}
		//$number_of_posts = mysql_num_rows($result);
		return $number_of_posts;
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
		$query = 'SELECT mall_category_name FROM gv_mall_category WHERE mall_category_id = ' . $data;
		$result = mysql_query($query, $db) or die (mysql_error($db));
		if(mysql_num_rows($result) < 1)
		{
			$the_text = "Dude you're being hacked";
		}
		else{
				$row = mysql_fetch_assoc($result);
				$the_text = $row["mall_category_name"];
		}
		return $the_text;
	}

	//Check if the provided category exists
	function does_category_name_exist($data)
	{
		global $db;
		$query = 'SELECT mall_category_id FROM gv_mall_category WHERE mall_category_name = "' . $data . '"';
		$result = mysql_query($query, $db) or die (mysql_error($db));
		if(mysql_num_rows($result) < 1)
		{
			$the_number = 0;
		}
		else{
				$row = mysql_fetch_assoc($result);
				$the_number = $row["mall_category_id"];
		}
		return $the_number;
	}
	
	//Check if post requested is this member's post
	function is_post_id_members_own($data)
	{
		global $db;
		$query = 'SELECT member_id FROM gv_mall_post WHERE mall_post_block = 0 AND mall_post_id = ' . mysql_real_escape_string($data, $db);
		$result = mysql_query($query, $db) or die (mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $member_id;
	}

?>
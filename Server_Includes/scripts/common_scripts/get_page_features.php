<?php

	function profile_image_caption($data)
	{
		global $db;
		$query = 'SELECT profile_image_caption FROM gv_profile_image WHERE member_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		if(mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_assoc($result);
			extract($row);
			$the_caption = $profile_image_caption;
		}
		if(mysql_num_rows($result) == 0)
		{
				$the_caption = "No caption.";
		}
		return $the_caption;
	}
	
	//Get calculated age based on given server date
	function age($data)
	{
		global $db;
		$string = $data;
		$year_of_birth = explode(" ", $string, 3);
		$current_year = date('Y');
		$age = $current_year - $year_of_birth[2];	
		return $age;
	}

	//Moderator's alias for mod's security
	function moderator_alias($data)
	{
		global $db;
		$query = 'SELECT moderator_alias FROM gv_moderator_alias WHERE member_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		if($result = mysql_query($query))
		{
			if(mysql_num_rows($result))
			{
				$row = mysql_fetch_assoc($result);
				extract($row);
				$moderator_alias = $moderator_alias;
			}
			else {
					//No data is found
					$moderator_alias = "";
			}
		}
		else {
				//The query failed
		}
		return $moderator_alias;
	}
	
	//Profile/Member's profile photo
	function members_image($data)
	{
		global $db;
		$query = 'SELECT profile_image_filename FROM gv_profile_image WHERE member_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		if(mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_assoc($result);
			extract($row);
			$the_image = $profile_image_filename;
		}
		else{
				$the_image = "default.png";
		}
		return $the_image;
	}
	
	//Member's email address
	function email_address($data)
	{
		global $db;
		$query = 'SELECT email_address FROM gv_members WHERE member_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $email_address;
	}
	
	//Modify title to fit in url for Search Engine Optimization
	function modified_for_url($data, $added)
	{
		$data_splitter = explode(' ', $data);
		$data_packer = array_splice($data_splitter, 0);
		$modified_data = $added . implode('-', $data_packer);
		return $modified_data;
	
		//$added => "&category="
	}

	//Get list of newest members
	function get_newest_members()
	{
		global $db;
		$query = 'SELECT member_id, username FROM gv_members WHERE (deactivated = 0 AND banned = 0) AND suspended = 0 ORDER BY member_id ASC LIMIT 20';
		$resultNewest = 
		mysql_query($query, $db) or die(mysql_error($db));
	
		if(mysql_num_rows($resultNewest) !== 0)
		{
			while($newestMember = mysql_fetch_assoc($resultNewest))
			{
				extract($newestMember);
				echo ' <a href="member_profile.php?id=' . $newestMember["member_id"] . '">' . ucfirst($newestMember["username"]) . '</a> |';
			}
		}
		else{ echo " No user yet "; }
	}

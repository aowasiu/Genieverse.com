<?php

	//Get list of newest members
	function get_newest_members($docLevelUrlPrefix)
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
				echo ' <a href="' . $docLevelUrlPrefix . 'member_profile.php?id=' . $newestMember["member_id"] . '">' . ucfirst($newestMember["username"]) . '</a> |';
			}
		}
		else{ echo " No user yet "; }
	}

<?php

	//First photo of a post
	function post_image($data)
	{
		global $db;
		$query = 'SELECT voice_image_filename FROM gv_voice_post_image WHERE (voice_post_id = ' . $data . ') AND (voice_image_block = 0) ORDER BY voice_image_id ASC LIMIT 1';
		$result = mysql_query($query, $db) or die(mysql_error($db));

		if(mysql_num_rows($result) !== 0)
		{
			$row = mysql_fetch_assoc($result);
			extract($row);
			$the_image = $voice_image_filename;
		}
		if(mysql_num_rows($result) == 0)
		{
				$the_image = "default.png";
		}
		return $the_image;
	}
	
	//Get title of a post
	function get_post_title($data)
	{
		global $db;
		$query = 'SELECT voice_post_title FROM gv_voice_post WHERE (voice_post_id = ' . $data . ') AND (voice_post_block = 0 AND voice_member = 1)';
		$result = mysql_query($query, $db) or die (mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $voice_post_title;
	}
	
	//Email protection
	function Protected_Email($email)
	{
		if($email !== "")
		{
			$t1 = strpos($email, '@');
			$t2 = strpos($email, '.', $t1);
			if(!$t1 || !$t2) return FALSE;
			$e1 = substr($email, 0, $t1);
			$e2 = substr($email, $t1, $t2 - $t1);
			$e3 = substr($email, $t2);
			return "<script>
						e1='$e1';
						e2='$e2';
						e3='$e3';
						document.write" . "('<a href=\'mailto:' + e1 + e2 + e3 + '\'>' + e1 " . "+ e2 + e3 + '</a>');
						</script>";
		}
		else{
				return "";
		}
	}

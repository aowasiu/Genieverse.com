<?php

	//First photo of a post
	function post_image($data)
	{
		global $db;
		$query = 'SELECT spotlight_image_filename FROM gv_spotlight_post_image WHERE (spotlight_post_id = ' . $data . ') AND (spotlight_image_block = 0) ORDER BY spotlight_image_id ASC LIMIT 1';
		$result = mysql_query($query, $db) or die(mysql_error($db));

		if(mysql_num_rows($result) !== 0)
		{
			$row = mysql_fetch_assoc($result);
			extract($row);
			$the_image = $spotlight_image_filename;
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
		$query = 'SELECT spotlight_post_title FROM gv_spotlight_post WHERE (spotlight_post_id = ' . $data . ') AND (spotlight_post_block = 0 AND spotlight_member = 1)';
		$result = mysql_query($query, $db) or die (mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $spotlight_post_title;
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

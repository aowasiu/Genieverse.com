<?php

	//First photo of a post
	function post_image($data)
	{
		global $db;
		$query = 'SELECT mall_image_filename FROM gv_mall_post_image WHERE (mall_post_id = ' . $data . ') AND (mall_image_block = 0) ORDER BY mall_image_id ASC LIMIT 1';
		$result = mysql_query($query, $db) or die(mysql_error($db));

		if(mysql_num_rows($result) !== 0)
		{
			$row = mysql_fetch_assoc($result);
			extract($row);
			$the_image = $mall_image_filename;
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
		$query = 'SELECT mall_post_title FROM gv_mall_post WHERE (mall_post_id = ' . $data . ') AND (mall_post_block = 0 AND mall_member = 1)';
		$result = mysql_query($query, $db) or die (mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $mall_post_title;
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

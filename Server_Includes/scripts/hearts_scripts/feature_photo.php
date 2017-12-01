<?php

	//First photo of a post
	function featured_image($data)
	{
		global $db;
		$query = 'SELECT featured_image_filename FROM gv_hearts_featured_profile_image WHERE featured_image_id = ' . $data . ' AND featured_image_block = 0 AND featured_image_delete = 0';
		$result = mysql_query($query, $db) or die(mysql_error($db));

		if(mysql_num_rows($result) !== 0)
		{
			$row = mysql_fetch_assoc($result);
			extract($row);
			$the_image = $featured_image_filename;
		}
		if(mysql_num_rows($result) == 0)
		{
				$the_image = "default.png";
		}
		return $the_image;
	}

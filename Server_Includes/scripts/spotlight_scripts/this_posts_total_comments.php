<?php

	//Count comments for this post
	function this_posts_total_comments($data)
	{
		global $db;
		$query = 'SELECT spotlight_comment_id AS totalCount FROM gv_spotlight_post_comments WHERE (spotlight_post_id = ' . mysql_real_escape_string($data) . ' AND spotlight_comment_delete = 0) AND (spotlight_member = 1 AND spotlight_comment_block = 0)';
		$result = mysql_query($query, $db);
		$numberOfRows = mysql_num_rows($result);
		return $numberOfRows;
		//posters_total_post_count($row["member_id"], 'gv_spotlight_post', 'spotlight_post_id', 'member_id')
	}


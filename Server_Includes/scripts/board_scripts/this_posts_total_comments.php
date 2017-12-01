<?php

	//Count comments for this post
	function this_posts_total_comments($data)
	{
		global $db;
		$query = 'SELECT board_comment_id AS totalCount FROM gv_board_post_comments WHERE (board_post_id = ' . mysql_real_escape_string($data) . ' AND board_comment_delete = 0) AND (board_member = 1 AND board_comment_block = 0)';
		$result = mysql_query($query, $db);
		$numberOfRows = mysql_num_rows($result);
		return $numberOfRows;
		//posters_total_post_count($row["member_id"], 'gv_board_post', 'board_post_id', 'member_id')
	}


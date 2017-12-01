<?php

	//Count comments for this post
	function this_posts_total_comments($data)
	{
		global $db;
		$query = 'SELECT voice_comment_id AS totalCount FROM gv_voice_post_comments WHERE (voice_post_id = ' . mysql_real_escape_string($data) . ' AND voice_comment_delete = 0) AND (voice_member = 1 AND voice_comment_block = 0)';
		$result = mysql_query($query, $db);
		$numberOfRows = mysql_num_rows($result);
		return $numberOfRows;
		//posters_total_post_count($row["member_id"], 'gv_voice_post', 'voice_post_id', 'member_id')
	}


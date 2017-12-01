<?php

	//Total of unread instant messages
	function unread_general_messages()
	{
		global $db;
		$query = 'SELECT gv_gen_read FROM gv_general_messages WHERE (gv_gen_read = 0 AND gv_gen_blocked = 0) AND (gv_gen_message_to = "' . $_SESSION["member_id"] . '")';
		$result = mysql_query($query, $db) or die(mysql_error($db));
		if(mysql_num_rows($result) > 0)
		{
			$number_of_messages = mysql_num_rows($result);
		}
		if(mysql_num_rows($result) < 1)
		{
			$number_of_messages = 0;
		}
		//$number_of_posts = mysql_num_rows($result);
		return $number_of_messages;
	}
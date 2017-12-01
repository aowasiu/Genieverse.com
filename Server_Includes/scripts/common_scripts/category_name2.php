<?php

	function hearts_category_name($data)
	{
		global $db;
		$query = 'SELECT hearts_category_name FROM gv_hearts_category WHERE hearts_category_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $hearts_category_name;
	}

	function spotlight_category_name($data)
	{
		global $db;
		$query = 'SELECT spotlight_category_name FROM gv_spotlight_category WHERE spotlight_category_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $spotlight_category_name;
	}

	function board_category_name($data)
	{
		global $db;
		$query = 'SELECT board_category_name FROM gv_board_category WHERE board_category_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $board_category_name;
	}

	function voice_category_name($data)
	{
		global $db;
		$query = 'SELECT voice_category_name FROM gv_voice_category WHERE voice_category_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $voice_category_name;
	}

	function mall_category_name($data)
	{
		global $db;
		$query = 'SELECT mall_category_name FROM gv_mall_category WHERE mall_category_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return $mall_category_name;
	}

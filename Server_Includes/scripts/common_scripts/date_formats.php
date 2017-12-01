<?php

	//This script details various date formats

	function change_date_short($data)
	{
		$time = strtotime($data);
		$date_format = date('d-M-Y. g:ia', $time);
		return $date_format;
	}
	
	function change_date_medium($data)
	{
		$time = strtotime($data);
		$date_format = date("M d, Y. g:ia", $time);
		return $date_format;
	}

	function change_date_long($data)
	{
		$time = strtotime($data);
		$date_format = date("l, d F, Y. g:ia", $time);
		return $date_format;
	}
	
	function custom_date($date, $data_format)
	{
		$time = strtotime($date);
		$date_format = date($data_format, $time);
		return $date_format;
	}
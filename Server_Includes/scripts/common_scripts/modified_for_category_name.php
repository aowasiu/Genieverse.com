<?php

	function modified_for_category_name($data, $to_remove, $to_replace_with)
	{
		$data_splitter = explode($to_remove, $data);
		$data_packer = array_splice($data_splitter, 0);
		$modified_data = $added_data . implode($to_replace_with, $data_packer);
		return $modified_data;
	
		//example => modified_for_category_name($data, '-', ' ')
	}

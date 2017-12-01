<?php

	//Modify title to fit in url for Search Engine Optimization
	function modified_for_url($data, $to_remove, $to_replace_with, $added_data)
	{
		$data_splitter = explode($to_remove, $data);
		$data_packer = array_splice($data_splitter, 0);
		$modified_data = $added_data . implode($to_replace_with, $data_packer);
		return $modified_data;
	
		//example => modified_for_url($data, ' ', '-', '&Category')
	}

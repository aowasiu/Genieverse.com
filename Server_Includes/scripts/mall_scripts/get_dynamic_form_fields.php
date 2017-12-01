<?php

	//This script focuses on generating dynamic form fields
	function dynamic_age($data)
	{
		if($data = 21 || $data = 25)
		{
			$dynamic_age = "";
		}
		elseif($data = 10 || $data = 17)
		{
			$dynamic_age = "";
		}
		else {
				//This applies to all other category_id 
				$dynamic_age = 'Age';
		}
		
		return $dynamic_age;
	}

	//This function creates a dynamic field for 'Barter' in Mall post form
	function dynamic_barter($category_id)
	{
		if($data = 21 || $data = 25)
		{
			$dynamic_barter = "";
		}
		elseif($data = 10 || $data = 17)
		{
			$dynamic_barter = "";
		}
		elseif($data = 16)
		{
			$dynamic_barter = "";
		}
		else {
				//This applies to all other category_id 
				$dynamic_barter = 'Barter';
		}
		
		return $dynamic_barter;
	}

	function dynamic_condition($data)
	{
		if($data == 16)
		{
			$dynamic_condition = 'Status';
		}
		elseif($data == 10)
		{
			$dynamic_condition = 'Duration';
		}
		else{
				$dynamic_condition = 'Condition';
		}
		return $dynamic_condition;
	}

	function dynamic_price($data)
	{
		if($data == 17)
		{
			$dynamic_price = 'Charge';
		}
		elseif($data == 10)
		{
			$dynamic_price = 'Salary';
		}
		else{
				$dynamic_price = 'Price';
		}
		return $dynamic_price;
	}
	

<?php

	//Reduce the length of text
	function truncate_text($text, $text_length, $emptiness_statement)
	{
		$text = $text . " ";
		$text = substr($text, 0, $text_length);
		$text = substr($text, 0, strripos($text, ' '));
		if($text == false || $text == "")
		{
			$text_return = $emptiness_statement;
		}
		else {
				$text_return = $text . " ...";
		}
		return $text_return;
	}//End of truncate_text()

	//Sample: echo truncate_text($title, 220, "No text to display");

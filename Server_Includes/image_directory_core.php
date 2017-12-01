<?php

	/* On a Linux system or server, the above will look thus:
		$image_directory_core = '/home/username/public_html_or_www';
		
	   On a Windows system or server, the above will look thus:
		$image_directory_core = 'C:/xampp/htdocs/test123'
	*/

	if($_SERVER['HTTP_HOST'] == 'www.genieverse.com')
	{
		// On a Linux system or server, the above will look thus:
		$image_directory_core = '/home/genixxym/public_html';	
	}
	else {
			//Path to images directory core
			$image_directory_core = "C:/xampp/htdocs/GenieVerse.com";
	}
?>
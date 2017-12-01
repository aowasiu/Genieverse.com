<?php
	
	if(!isset($_SESSION["logged_in"]))
	{
		$_SESSION["errand_title"] = 'Log in issue';
		$_SESSION ["errand"] = 'You must be logged in to view member area';
		//Take this visitor to the log in page because he's not logged in
		header('Location: m_log_in.php');
		exit;
	}

?>
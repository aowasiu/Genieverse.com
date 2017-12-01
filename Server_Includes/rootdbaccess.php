<?php	
//sha1('Hcbh3GqNH' . sha1('Hcbh3GqNH' . sha1('Li9Nu?x#Ub7Un5tU$!7!7')))
	if($_SERVER['HTTP_HOST'] == 'www.genieverse.com')
	{
		//Connect to the database
		$host = 'localhost';
		$user = 'genixxym_dbvisit'; //Change Genieverse web server password to the password in the Includes access file
		$password = '1982AqPass123';
		$database_name = 'genixxym_genieverseworld_db';
	}
	else {
			//Connect to the database
			$host = 'localhost';
			$user = 'root'; //Change Genieverse web server password to the password in the Includes access file
			$password = '1977AqPass123';
			$database_name = 'genieverseworld_db';
	}

	function db_redirect()
	{
//		$_SESSION["db_issue"] = 'Inaccessible database';
		header ("Location: www.genieverse.com/issues_db.php");
		exit;
	}


	//Establish a connection with MySQL
	$db = mysql_connect($host, $user, $password) or db_redirect ();

/*	 
	$db = mysql_connect($host, $user, $password) or
	 die(
			$_SESSION["db_issue"] = "No database";
			header("Location: issue_db.php");
			exit;
		);
*/
	//Ensure the correct database is accessed
	mysql_select_db($database_name, $db) or die(mysql_error($db));


?>
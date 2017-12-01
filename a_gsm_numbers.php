<?php

	//Connect to the database
	$host = 'localhost';
	$user = 'root';
	$password = '1982AqPass123';
	$database_name = 'genieverseworld_db';

	//Establish a connection with MySQL
	$db = mysql_connect($host, $user, $password);
	
	//Ensure the correct database is accessed
	mysql_select_db($database_name, $db) or die(mysql_error($db));

	$rev_pre = date('Ymt');

	$query = 'SELECT mpn_id, mpn_name, mpn_phone, mpn_state_name FROM mined_phone_numbers  ORDER BY mpn_id ASC LIMIT 620000, 10000';
	$result = mysql_query($query, $db) or die (mysql_error($db));
	
	$row = mysql_fetch_array($result);
	extract($row);
	
	if($row !== 0)
	{
		while($row = mysql_fetch_assoc($result))
		{
			extract($row);
			echo 'BEGIN:VCARD' . "\n";
			echo 'VERSION:2.1' . "\n";
			echo 'FN: ' . $row["mpn_name"] . "\n";
			echo 'ORG: GenieVerse.com' . "\n";
			echo 'TITLE: Visitors' . "\n";
			echo 'TEL;MOBILE:VOICE: ' . $row["mpn_phone"] . "\n";
			echo 'ADR;HOME:;; ' . $row["mpn_state_name"] . "\n";
			echo 'LABEL;HOME;ENCODING=QUOTED-PRINTABLE: ' . $row["mpn_state_name"] . '=OD=Nigeria' . "\n";
			echo 'REV:' . $rev_pre . $row["mpn_id"]. "\n";
			echo 'END:VCARD' . "\n" . "\n";
		}
	}
	else{
			?>
	<p>Sorry. There're no numbers.</p><?php
	}

	mysql_close();

	echo 'All iz well!';

?>
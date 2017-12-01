<?php

	$errors = array();
	
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Log in")
	{
		if(empty($password) || empty($username))
		{
			$errors[] = "You didn't enter your password or username";
		}
		elseif(strlen($password) > 15)
		{
			$errors[] = "Your password is longer than 15 characters.";
		}
		else {
				//Get check_input function
				require_once('../Server_Includes/scripts/common_scripts/check_input.php');

				$safe_username = check_input($username);
				$salt = '$2a$12$G.tFo2T2W.IiO4hWj1d2KI$';
				$secure_password_hash = crypt($password, $salt);
				
				//Connect to the database
				require_once('../Server_Includes/visitordbaccess.php');
			
				//Open the database to check if the username and password are correct
				$query = 'SELECT member_id, privilege, username FROM gv_members WHERE (username = "' . mysql_real_escape_string($safe_username, $db) . '" OR email_address = "' . mysql_real_escape_string($safe_username, $db) . '") AND password = "' . mysql_real_escape_string($secure_password_hash, $db) . '"';
				$result = mysql_query($query, $db) or die(mysql_error($db));
				
				if(mysql_num_rows($result) < 1)
				{
					$errors[] = "Your username and password don't match any member's detail.";
				}
				else {
						$row = mysql_fetch_assoc($result);
						extract($row);
				}

				if(count($errors) < 1)
				{
					//Set all session variables as required
					$_SESSION["logged_in"]	= 1;
					$_SESSION["member_id"]	= $row["member_id"];
					$_SESSION["privilege"]	= $row["privilege"];
					$_SESSION["username"] 	= $row["username"];
					
					//Get country_name and country_name_id functions
					require_once('../Server_Includes/scripts/common_scripts/get_name_functions.php');
					require_once('../Server_Includes/scripts/common_scripts/get_id_functions.php');

					function set_login_time($data)
					{
						global $db;
						$query = 'UPDATE gv_members SET last_login = NOW() WHERE member_id = ' . $data;
						$result = mysql_query($query, $db) or die(mysql_error($db));
					}
					
					//$remote = $_SERVER["REMOTE_ADDR"];

					$remote = $_SERVER["REMOTE_ADDR"];
					$ip_number = sprintf("%u", ip2long($remote));
					
					//Get member_country_id and country_name
					$_SESSION["country_id"] = member_country_id($_SESSION["member_id"]);
					$_SESSION["country_name"] = country_name($_SESSION["country_id"]);
					
					$log_in_time = set_login_time($row["member_id"]);
					
					mysql_close();
					
					//$redirect = (isset($_REQUEST['location'])) ? $_REQUEST['location'] : "services.php";
					if($previous_location !== "")
					{
						$redirect = $previous_location;
					}
					else {
							$redirect = $default_destination;
					}
					
					header("Location: $redirect");
					exit;
					
				}//End of !isset($_SESSION["errand"]) || !isset($_SESSION["errand_title"]) or if(count($errors) < 1)
		}//End of if(empty($password) || empty($username))
	}//End of if($_SERVER["REQUEST_METHOD"] == "POST")

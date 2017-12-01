<?php

	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Tell my friend")
	{	
	/*	$senders_name;
		$recipients_email;
		$captcha;
		$recipients_phone;
		
		if(isset($_POST['g-recaptcha-response']))
		{
			$captcha = $_POST['g-recaptcha-response'];
		}
		
		if(!$captcha)
		{
			$errors[] = "You didn't respond to the CAPTCHA.";
		}
		
		$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lec2A4TAAAAAP1jR8YS8zOPstD_m20Acn_qNX23&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
	
		if($response['success'] == false)
		{
			$errors[] = "Your response to the CAPTCHA is incorrect.";
		}
		else {
				//The response to ReCAPTCHA is correct. Now execute the rest of the script
	*/			
				if(empty($_POST["senders_name"]) || !isset($_POST["senders_name"]))
				{
					$errors[] = "You didn't enter your full name.";
				}
				elseif(preg_match("/^[a-zA-Z]*$/", $_POST["senders_name"]))
				{
					$errors[] = $_POST["senders_name"] . ' is not a full name.';
				}
				elseif(!preg_match('/\s/', $_POST["senders_name"]))
				{
					$errors[] = 'Your full name is not acceptable.';
				}
				elseif($_POST["senders_name"] == $your_names_default)
				{
					$errors[] = 'Your full name is not acceptable.';
				}
				elseif(strlen($_POST["senders_name"]) > 45)
				{
					$errors[] = 'Your full name is longer than 45 characters.';
				}
				else {
						$senders_name = $_POST["senders_name"];

						if(!isset($_POST["recipients_email"]) || empty($_POST["recipients_email"]))
						{
							$errors[] = "You didn't enter your friend's email address.";
						}
						elseif(strlen($_POST["recipients_email"]) > 50)
						{
							$errors[] = "Your friend's email address is longer than 50 characters.";
						}
						else {
								if(!filter_var($_POST["recipients_email"], FILTER_VALIDATE_EMAIL))
								{
									$errors[] = "Your friend's email address is not valid.";
								}
								else {
										$recipients_email = $_POST["recipients_email"];
										
										function email_exists($data)
										{
											global $db;
											$query = 'SELECT recipients_email FROM gv_tell_a_friend WHERE recipients_email = "' . $data . '"';
											$result = mysql_query($query, $db) or die(mysql_error($db));
											if(mysql_num_rows($result) > 0)
											{
												$found = "Yes";
											}
											else{
													$found = "No";
											}
											return $found;
										}
								
										$email_exists = email_exists($recipients_email);
										
										if($email_exists == "Yes")
										{
											$errors[] = "Thanks for the effort " . $senders_name . " but your friend has already been told.";
										}
								}//End of if(!filter_var($_POST["recipients_email"], FILTER_VALIDATE_EMAIL))
						}//End of if(!isset($_POST["recipients_email"]) || empty($_POST["recipients_email"])) 
				}//End of if(empty($_POST["senders_name"]) || !isset($_POST["senders_name"]))
				
				if(!$errors)
				{
					$query = 'INSERT INTO gv_tell_a_friend (tell_id, told_successfully, senders_name, recipients_email, tell_date) VALUES (NULL, 1, "' . mysql_real_escape_string($senders_name, $db) . '", "' . mysql_real_escape_string($recipients_email, $db) . '", NOW())';
					//Insert this record into database first before sending
					mysql_query($query, $db) or die (mysql_error($db));
					
					$from_address = 'no_reply@genieverse.com';
					$subject = "$senders_name invites you to visit Genieverse.";						
					$boundary = "<br>";							
					$headers = array();
					$headers[] = 'MIME-Version: 1.0';
					$headers[] = 'Content-type: text/html; charset="iso-8859-1"';
					$headers[] = 'Content-Transfer-Encoding: 7bit';
					$headers[] = 'From: ' . $from_address;
					$headers[] = 'To: ' . $recipients_email;

					$message_body = '<html>';
					$message_body .= '<div>';
					$message_body .= '<p>Hello.</p>';
					$message_body .= '<p>' . ucwords($senders_name) . ' is inviting you to <a href="http://www.genieverse.com">Genieverse</a>.</p>';
					$message_body .= '<p>Just click any of the links below to explore <a href="http://www.genieverse.com">Genieverse</a>.</p>';
					$message_body .= '<p><a href="http://www.genieverse.com/mall/home.php">Genieverse Mall</a></p>';
					$message_body .= '<p><a href="http://www.genieverse.com/board/home.php">Genieverse Board</a></p>';
					$message_body .= '<p><a href="http://www.genieverse.com/voice/home.php">Genieverse Voice</p>';
					$message_body .= '<a href="http://www.genieverse.com/offender/home.php">Genieverse Offender</a>';
					$message_body .= '<br/><br/>';
					$message_body .= "<p>'Looking forward to your visit.</p><br/>";
					$message_body .= "<p>Yours sincerely,<br/><br/>";
					$message_body .= "Genieverse team.<br/>";
					$message_body .= "Genieverse.com<br/>";
					$message_body .= date('F d, Y.') . "</p>";
					$message_body .= $boundary;
					$message_body .= '</div>';
					$message_body .= '</html>';
					$mail_sent_successfully = mail($recipients_email, $subject, $message_body, join("\r\n", $headers));
					
					if($mail_sent_successfully)
					{
						$errors[] = "Thanks for spreading the word.";
					}
					else {
							$errors[] = "There's a problem sending the mail but thanks for the attempt.";
							
							$query = 'UPDATE gv_tell_a_friend SET told_successfully=0 WHERE recipients_email = "' . mysql_real_escape_string($recipients_email) . '"';
							mysql_query($query, $db) or die(mysql_error($db));
					}					
				}//End of if(!isset($_SESSION["errand"]) || !isset($_SESSION["errand_title"]))
/*		}//End of if($response['success'] == false)
*/	}//End of if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Tell my friend")

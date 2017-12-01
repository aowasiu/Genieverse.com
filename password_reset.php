<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');

	$reset_submit_button = "Set new password";

	//Filter incoming values
	$post_forgotten_id = (isset($_POST["forgotten_id"])) ? $_POST["forgotten_id"] : 0;
	$post_forgotten_token = (isset($_POST["forgotten_token"])) ? $_POST["forgotten_token"] : "";
	$forgotten_id = (isset($_GET["id"])) ? $_GET["id"] : $post_forgotten_id;
	$forgotten_token = (isset($_GET["token"])) ? $_GET["token"] : $post_forgotten_token;
	$password1 = (isset($_POST["password1"])) ? trim($_POST["password1"]) : "Enter a new password";
	$password2 = (isset($_POST["password2"])) ? trim($_POST["password2"]) : "Enter the new password again";
	
	if(isset($_SESSION["logged_in"]))
	{
		header("Location: password_change.php");
		exit;
	}

	if($forgotten_id == 0 || $forgotten_token == "")
	{
		header("Location: forgotten_pass.php");
		exit;
	}

	//Connect to the database
	require_once('Server_Includes/visitordbaccess.php');
						
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == $reset_submit_button)
	{
		$member_id = (isset($_POST["member_id"])) ? $_POST["member_id"] : 0;
		
		//Define $errors
		$errors	=	array();

		if(empty($password1) || empty($password2))
		{
			$errors[] = "You must enter the same password in both fields.";
		}
		else {
				if($password2 !== $password1)
				{
					$errors[] = "Your passwords do not match.";
				}
				else {
						$salt = '$2a$12$G.tFo2T2W.IiO4hWj1d2KI$';
						$secure_password_hash = crypt($password2, $salt);
						
						$query = 'UPDATE gv_members SET last_login=NOW(), password="' . mysql_real_escape_string($secure_password_hash, $db) . '" WHERE member_id = ' . $member_id;
						mysql_query($query, $db) or die(mysql_error($db));
												
						$query = 'DELETE FROM gv_forgotten_password WHERE forgotten_id = ' . $forgotten_id . ' AND forgotten_token = "' . $forgotten_token . '"';
						mysql_query($query, $db) or die(mysql_error($db));

						mysql_free_result();
						mysql_close();
						
						require_once('Server_Includes/scripts/common_scripts/get_name_functions.php');
						require_once('Server_Includes/scripts/common_scripts/get_page_features.php');
						
						$the_email_address = email_address($member_id);
						$the_username = member_username($member_id);
						
						$from_address = 'no_reply@genieverse.com';
						$subject = 'Genieverse: Password Change.';						
						$boundary = "<br>";
						$headers = array();
						$headers[] = 'MIME-Version: 1.0';
						$headers[] = 'Content-type: text/html; charset="iso-8859-1"';
						$headers[] = 'Content-Transfer-Encoding: 7bit';
						$headers[] = 'From: ' . $from_address;
						$headers[] = 'To: ' . $the_email_address;

						$message_body = '<html>';
						$message_body .= '<div>';
						$message_body .= '<p>Hello <b>' . $the_username . '</b>,</p>';
						$message_body .= "<p>Your password was recently changed at " . date('F d, Y. g:ia') . ".</p>";
						$message_body .= "<p>If this wasn't you, simply log in and change your password.</p>";
						$message_body .= $boundary;
						$message_body .= "<p>If the password was changed by you, just ignore this email.</p>";
						$message_body .= $boundary . $boundary;
						$message_body .= '<p>Yours sincerely,</p><br/>';
						$message_body .= '<p>Genieverse team,</p>';
						$message_body .= '<p><b>genieverse.com</b></p>';
						$message_body .= '<p>' . date('F d, Y.') . '</p>';
						$message_body .= $boundary;
						$message_body .= '</div></html>';
					
						//Now send the email address
						$notification_is_sent = mail($the_email_address, $subject, $message_body, join("\r\n", $headers));
						
						if($notification_is_sent)
						{
							$_SESSION["errand_title"] = 'Successful password change';
							$_SESSION["errand"] = 'Your password has been changed successfully.<br><a href="log_in.php">Log in</a> to continue using <a href="index.php">Genieverse</a>';
							mysql_close();
							header("Location: issues.php");
							exit;
						}
						else {
								$_SESSION["errand_title"] = 'Unsuccessful password change';
								$_SESSION["errand"] = 'Your password could not be changed.<br>Try later by <a href="forgotten_pass.php">clicking here</a>.';
								mysql_close();
								header("Location: issues.php");
								exit;
						}//End of if($notification_is_sent)
				}//End of if($password2 !== $password1)
		}//End of if(empty($password1) || empty($password2))
	}//End of if($_SERVER["REQUEST_METHOD"] == "POST")

	//Check if the provided forgotten_id and forgotten_token exist in the database
	$query = 'SELECT member_id FROM gv_forgotten_password WHERE forgotten_id = ' . $forgotten_id . ' AND forgotten_token = "' . $forgotten_token . '"';
	$result = mysql_query($query, $db) or die(mysql_error($db));
	$number_of_rows = mysql_num_rows($result);
	$row = mysql_fetch_array($result);
	
	if($number_of_rows < 1)
	{
		$_SESSION["errand_title"] = "You're attempting to access restricted web page";
		$_SESSION["errand"] = "If you don't remember your password, <a href='forgotten_pass.php'>click here to set a new password</a>.<br><br>" . 'If you remember your current password, <a href="log_in.php">click here to Log in</a>';
		header("Location: issues.php");
		exit;
	}
	else {
			$member_id = $row["member_id"];
			
			require_once('Server_Includes/scripts/common_scripts/get_name_functions.php');
			
			$the_username = member_username($member_id);
	}	

	//Set extra title
	$extra_title = "Password Reset";

	//Set extra script
	$extra_script = "\n";

	//Set page template name
	$shop_item = "set";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">
    
		<div class="caption-full">
            <h5 class="pull-right"></h5>
			<h3>Password reset</h3>
			<hr>
		</div>
	    
		<div class="caption-full">
            <h5 class="pull-right"></h5>
			<h4>Welcome back <?php echo ucfirst($the_username); ?></h4>
			<hr>
		</div>

		<?php
		if($errors)
		{
			echo '<div class="row">
            <div class="col-lg-12 text-center">
				<div class="alert alert-danger">
					<p>Your password could not be changed';
			if(count($errors) < 2)
			{
				echo ':</p>';
			}
			else {
					echo 's:</p>';
			}
			echo '<ul>';
			foreach($errors as $error)
			{
				echo '<li>' . $error . '</li>';
			}
			echo '</ul>';
			echo '				</div>
			</div>
		</div>';
		}
?>		
		<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<div class="form-group col-lg-12">
            <div class="col-lg-6 col-lg-offset-3 text-center">
				<div>
					<p>Both fields are compulsory</p>
					<input class="form-control" type="password" name="password1" id="password1" placeholder="<?php echo $password1; ?>">
				</div>
			</div>
		</div>

		<div class="form-group col-lg-12">
            <div class="col-lg-6 col-lg-offset-3 text-center">
				<div>
					<input class="form-control" type="password" name="password2" id="password2" placeholder="<?php echo $password2; ?>">
				</div>
			</div>
		</div>

		<div class="form-group col-lg-12">
			<div class="row text-center">
				<div class="col-md-12">
					<span class="pull-right"></span>
					<h4></h4>
					<input type="hidden" name="forgotten_id" value="<?php echo $forgotten_id; ?>">
					<input type="hidden" name="forgotten_token" value="<?php echo $forgotten_token; ?>">
					<input type="hidden" name="member_id" value="<?php echo $member_id; ?>">
					<input class="btn btn-info" type="submit" name="submit" value="<?php echo $reset_submit_button; ?>">
				</div>
			</div>
		</div>

	</form>

    </div>
    <!-- /.container -->

	<div class="container">

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p><?php echo $the_footer; ?></p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

<?php require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_before_body_end.php'); ?>

</body>

</html><?php	mysql_close();	?>
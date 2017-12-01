<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	$submit_value = "Save my new password";
	$current_password = (isset($_POST["current_password"])) ? trim($_POST["current_password"]) : "";
	$new_password1 = (isset($_POST["new_password1"])) ? trim($_POST["new_password1"]) : "";
	$new_password2 = (isset($_POST["new_password2"])) ? trim($_POST["new_password2"]) : "";
	
	if(!isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] !== 1)
	{
		header("Location: password_reset.php");
		exit;
	}
	
	require_once('Server_Includes/visitordbaccess.php');

	//Define $errors
	$errors	=	array();

	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == $submit_value)
	{
		if(empty($current_password))
		{
			$errors[] = "You didn't enter your current password.";
		}
		else {
				if(empty($new_password1) || empty($new_password2))
				{
					$errors[] = "You must enter the new password in both fields.";
				}
				else {
						if($new_password2 !== $new_password1)
						{
							$errors[] = "Your new password does not match in both fields";
						}
						else {
								$salt = '$2a$12$G.tFo2T2W.IiO4hWj1d2KI$';
								$current_password_hash = crypt($current_password, $salt);
								$secure_password_hash = crypt($new_password2, $salt);
							
								function current_password($data)
								{
									global $db;
									$query = 'SELECT password FROM gv_members WHERE member_id = ' . $data;
									$result = mysql_query($query, $db) or die(mysql_error($db));
									$row = mysql_fetch_assoc($result);
									extract($row);
									return $password;
								}
								
								$get_current_password = current_password($_SESSION["member_id"]);
								if($get_current_password !== $current_password_hash)
								{
									$errors[] = "Your current password is incorrect.";
								}
						}//End of if($new_password2 !== $new_password1)
				}//End of if(empty($new_password1) || empty($new_password2))
		}//End of if(empty($current_password))
		
		if(count($errors) < 1)
		{
			$query = 'UPDATE gv_members SET last_login=NOW(), password="' . mysql_real_escape_string($secure_password_hash, $db) . '" WHERE member_id = ' . $_SESSION["member_id"];
			mysql_query($query, $db) or die(mysql_error($db));
			
			//Get email_address functions
			require_once('Server_Includes/scripts/common_scripts/get_page_features.php');
			
			$the_email_address = email_address($_SESSION["member_id"]);
			
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
			$message_body .= "<p>If the password was changed by you, ignore this email.</p>";
			$message_body .= $boundary . $boundary;
			$message_body .= '<p>Yours sincerely,</p><br/>';
			$message_body .= '<p>Genieverse team,</p>';
			$message_body .= '<p><b>genieverse.com</b></p>';
			$message_body .= '<p>' . date('F d, Y.') . '</p>';
			$message_body .= $boundary;
			$message_body .= '</div></html>';
		
			//Now send the email address
			$notification_is_sent = mail($the_email_address, $subject, $message_body, join("\r\n", $headers));
			
			//First of all, empty all SESSION variables that have been set
			$_SESSION = array();
			
			//End this session and redirect the member away from secure pages
			//session_destroy();
			
			if($notification_is_sent)
			{
				$_SESSION["errand_title"] = 'Successful password change';
				$_SESSION["errand"] = 'Your password has been changed successfully.<br><a href="log_in.php">click here to Log in again</a>';
				mysql_close();
				header("Location: issues.php");
				exit;
			}
			else {
					$_SESSION["errand_title"] = 'Unsuccessful password change';
					$_SESSION["errand"] = 'Your password could not be changed.<br>Try later by <a href="log_in.php">clicking here</a>.';
					mysql_close();
					header("Location: issues.php");
					exit;
			}//End of if($notification_is_sent)
		}//End of if(count($errors) < 1)
	}//End of if($_SERVER["REQUEST_METHOD"] == "POST")

	//Set extra title
	$extra_title = "Password change";

	//Set page template name
	$shop_item = "set";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">

		<div class="caption-full">
            <h5 class="pull-right"></h5>
			<h3><?php echo ucfirst($_SESSION["username"]); ?>'s Password change</h3>
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

		<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="application/x-www-form-urlencoded">
		<div class="form-group col-lg-12">
            <div class="col-lg-6 col-lg-offset-3 text-center">
				<div>
					<p>Enter current password</p>
					<input class="form-control password_fields" type="password" name="current_password" id="current_password">
				</div>
			</div>
		</div>

		<div class="form-group col-lg-12">
            <div class="col-lg-6 col-lg-offset-3 text-center">
				<div>
					<p>Enter a new password</p>
					<input class="form-control password_fields" type="password" name="new_password1" id="new_password1">
				</div>
			</div>
		</div>

		<div class="form-group col-lg-12">
            <div class="col-lg-6 col-lg-offset-3 text-center">
				<div>
					<p>Enter a new password again</p>
					<input class="form-control password_fields" type="password" name="new_password2" id="new_password2">
				</div>
			</div>
		</div>

		<div class="form-group col-lg-12">
			<div class="row text-center">
				<div class="col-md-12">
					<span class="pull-right"></span>
					<h4></h4>
					<input class="btn btn-default" type="submit" name="submit" value="<?php echo $submit_value; ?>">
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

</html><?php mysql_close(); ?>
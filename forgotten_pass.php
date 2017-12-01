<?php

	//Get custom error function script
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');

	//Set default email address
	$email_address = (isset($_POST["email_address"])) ? $_POST["email_address"] : "Enter your email address here";
	
	if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == 1)
	{
		header("Location: password_change.php");
		exit;
	}
	
	//Declare errors as array
	$errors = array();

	//Password reset form logic
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == 'Send a password-reset')
	{	
		if(empty($email_address))
		{
			$errors[] = "You didn't enter an email address.";
		}
		else {
				if(!filter_var($email_address, FILTER_VALIDATE_EMAIL))
				{
					$errors[] = "Your email address is not valid.";
				}
				else {
						$safe_email_address = $email_address;
				}
		}
	
		//Connect to the database
		require_once('Server_Includes/visitordbaccess.php');
	
		//Check that the provided email_address is existing in the gv_members table
		$query = 'SELECT member_id FROM gv_members WHERE email_address = "' . $safe_email_address . '"';
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$number_of_rows = mysql_num_rows($result);
		$row = mysql_fetch_array($result);
		if($number_of_rows < 1)
		{
			$errors[] = "You're not a member of Genieverse.";
		}
		else {
				$member_id = $row['member_id'];
		}
		
		if(count($errors) < 1)
		{
			$forgotten_token = md5(time());
				
			//Now insert detail into gv_forgotten_password table
			$query = 'INSERT INTO gv_forgotten_password
					(forgotten_id, member_id, forgotten_date, forgotten_token)
					VALUES
					(NULL, ' . mysql_real_escape_string($member_id, $db) . ', NOW(), "' . mysql_real_escape_string($forgotten_token, $db) . '")';
			mysql_query($query, $db) or die(mysql_error($db));
			
			$forgotten_id = mysql_insert_id($db);
			
			$from_address = 'no_reply@genieverse.com';
			$subject = 'Genieverse: Reset your password.';						
			$boundary = "<br>";
			$headers = array();
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset="iso-8859-1"';
			$headers[] = 'Content-Transfer-Encoding: 7bit';
			$headers[] = 'From: ' . $from_address;
			$headers[] = 'To: ' . $safe_email_address;

			$message_body = '<html>';
			$message_body .= '<div>';
			$message_body .= '<p>Hello.</p>';
			$message_body .= '<p>You recently requested a password reset.</p>';
			$message_body .= '<p>Click the link below to reset your password:</p>';
			$message_body .= '<hr noshade style="color: #EEBC1D;">';
			$message_body .= '<p style="color: #EEBC1D;"> === <a href="http://www.genieverse.com/password_reset.php?id=' . $forgotten_id . '&token=' . $forgotten_token . '">Password Reset</a> === </p>';
			$message_body .= '<hr noshade style="color: #EEBC1D;">';
			$message_body .= '<p>The link above is valid for 24 hours.</p>';
			$message_body .= "<p>If you didn't request a password reset, just ignore this email.</p>";
			$message_body .= $boundary . $boundary;
			$message_body .= '<p>Yours sincerely,</p>' . $boundary;
			$message_body .= '<p>Genieverse team,</p>';
			$message_body .= '<p><b>genieverse.com</b></p>';
			$message_body .= '<p>' . date('F d, Y.') . '</p>';
			$message_body .= $boundary;
			$message_body .= '</div></html>';
		
			//Now send the email address
			$mail_sent_successfully = mail($safe_email_address, $subject, $message_body, join("\r\n", $headers));

			//Start session
			session_start();
			
			if($mail_sent_successfully)
			{
				$_SESSION["errand_title"] = 'Forgotten-password request is successful.';
				$_SESSION["errand"] = 'A link to reset your password has been sent to <b>' . $safe_email_address . '</b><br>Look in your Inbox and Spam folders.';
				mysql_close($db);
				header("Location: issues.php");
				exit;
			}
			else {
					$_SESSION["errand_title"] = 'Forgotten-Password request could not be sent.';
					$_SESSION["errand"] = 'There was a problem while sending email to <b>' . $safe_email_address . '</b><br>Please try later.<br>If this problem persists, <a href="contact_us/correspondence.php">click here to report the problem on our <a href="contact_us/correspondence.php">Contact Us</a> page.';
					mysql_close($db);
					header("Location: issues.php");
					exit;
			}
		}
	}

	//Set extra title
	$extra_title = "Forgotten Password Request";

	//Set page template name
	$shop_item = "set";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">
    
		<div class="caption-full">
            <h5 class="pull-right"></h5>
			<h3>Forgotten password request</h3>
			<hr>
		</div>
	
		<?php
		if($errors)
		{
			echo '<div class="row">
            <div class="col-lg-12 text-center">
				<div class="alert alert-danger">
					<p>Your password request couldn\'t be processed';
			if(count($errors) < 2)
			{
				echo ':</p>';
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
					<input class="form-control" type="text" name="email_address" id="email_address" placeholder="<?php echo $email_address; ?>">
				</div>
			</div>
		</div>
		
		<div class="form-group col-lg-12">
			<div class="row text-center">
				<div class="col-md-12">
					<span class="pull-right"></span>
					<h4></h4>
					<input class="btn btn-info" type="submit" name="submit" value="Send a password-reset">
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

</html>
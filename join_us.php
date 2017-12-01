<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
		
	if(isset($_SESSION["logged_in"]))
	{
		//Take this visitor to the log in page because logged-in members shouldn't be able to join again.
		header("Location: log_out.php");
		exit();	
	}

	//Set submitted email variable
	$submitted_email = isset($_POST['submitted_email']) ? $_POST['submitted_email']: "Enter your email address (50 characters maximum)";
	
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Send a confirmation to my email")
	{
		$submitted_email;
		$captcha;

		if(!isset($submitted_email) || empty($submitted_email))
		{
			$_SESSION["errand_title"] = "Join us issue";
			$_SESSION["errand"] = "You didn't enter an email address in the field.";
		}
		else {
				
			$submitted_email = $_POST['submitted_email'];
		}

		if(isset($_POST['g-recaptcha-response']))
		{
			$captcha = $_POST['g-recaptcha-response'];
		}
		
		if(!$captcha)
		{
			$_SESSION["errand_title"] = "Join us issue";
			$_SESSION["errand"] = "You didn't respond to the CAPTCHA.";
		}

		$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lec2A4TAAAAAP1jR8YS8zOPstD_m20Acn_qNX23&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
		
		if($response['success'] == false)
		{
			$_SESSION["errand_title"] = "Join us issue";
			$_SESSION["errand"] = "Your response to the CAPTCHA is incorrect.";
		}
		else {
				//The response to ReCAPTCHA is correct. Now execute the rest of the script	
				if(!isset($submitted_email) || empty($submitted_email))
				{
					$_SESSION["errand_title"] = "Join us issue";
					$_SESSION["errand"] = "You didn't enter an email address.";
				}

				if(!filter_var($submitted_email, FILTER_VALIDATE_EMAIL))
				{
					$_SESSION["errand_title"] = "Join us issue";
					$_SESSION["errand"] = "Your email address is not acceptable.";
				}
				
				/*This part of the script will be executed after it's been deduced that the email address is set,
				 it's not an empty string, it is devoid of spaces before and after, it is also devoid of slashes 
				 and html special characters.
				*/
										
				//Connect to database
				require_once('Server_Includes/visitordbaccess.php');
										 
				 //Find out if the email address has already been submitted
				$query = 'SELECT confirm_email_address FROM gv_email_confirmation WHERE confirm_email_address = "' . $submitted_email . '"';
				$result = mysql_query($query, $db) or die (mysql_error($db));
				$row = mysql_num_rows($result);
										 
				//Consider the result
				if(mysql_num_rows($result) > 0)
				{
					$_SESSION["errand_title"] = "Join us issue";
					$_SESSION["errand"] = "Sorry, <b>$submitted_email</b> has already been submitted for confirmation.";
					$_SESSION["errand"] .= '<br>If it\'s your email address, check your Inbox or Spam folder to confirm your email address.<br>Or enter a different email address.</a>.';
					mysql_free_result($result);
				}
	
				//Find out if the email address is of an already registered member
				$query = 'SELECT email_address FROM gv_members WHERE email_address = "' . $submitted_email . '"';
				$result = mysql_query($query, $db) or die (mysql_error($db));
				$row = mysql_num_rows($result);
	
				//Consider the result
				if(mysql_num_rows($result) > 0)
				{
					$_SESSION["errand_title"] = "Join us issue";
					$_SESSION["errand"] = "Sorry, <b>$submitted_email</b> is in use by a member.";
					mysql_free_result($result);
				}
									
				//Define variable and set the default value to empty
				if(!isset($_SESSION["errand"]) || !isset($_SESSION["errand_title"]))
				{
					//Free result just incase
					//mysql_free_result($result);
					
					$full_token = md5(time());
					
					//Considering "all-iz-well", begin processing of the submitted email address
					$query = 'INSERT INTO gv_email_confirmation 
								(confirm_id, submission_date, full_token, confirm_email_address) 
							VALUES 
								(NULL, NOW(), "' . mysql_real_escape_string($full_token, $db) . '", "' . mysql_real_escape_string($submitted_email, $db) . '")';
					mysql_query($query, $db) or die (mysql_error($db));
											
					$confirm_id = mysql_insert_id($db);

					$from_address = 'no_reply@genieverse.com';
					$subject = 'Genieverse: Please confirm ' . $submitted_email . ' as your email address.';						
					$boundary = "<br>";							
					$headers = array();
					$headers[] = 'MIME-Version: 1.0';
					$headers[] = 'Content-type: text/html; charset="iso-8859-1"';
					$headers[] = 'Content-Transfer-Encoding: 7bit';
					$headers[] = 'From: ' . $from_address;
					$headers[] = 'To: ' . $submitted_email;

					$message_body = '<html>';
					$message_body .= '<div>';
					$message_body .= '<p>Hello.</p>';
					$message_body .= '<p>Thanks for wanting to join us.</p>';
					$message_body .= '<p>Click the link below to proceed to registration:</p>';
					$message_body .= '<hr noshade style="color: #EEBC1D;">';
					$message_body .= '<p style="color: #EEBC1D;"> === <a href="http://www.genieverse.com/registration.php?confirm_id=' . $confirm_id . '&full_token=' . $full_token . '">Registeration Form</a> === </p>';
					$message_body .= '<hr noshade style="color: #EEBC1D;">';
 
					$message_body .= '<p> Or click the link below:</p>';
					$message_body .= '<p> http://www.genieverse.com/registration.php?confirm_id=' . $confirm_id . '&full_token=' . $full_token . ' </p>';
					$message_body .= '<hr noshade style="color: #EEBC1D;">';
					$message_body .= '<p>The link above is valid for 24 hours.</p>';
					$message_body .= '<p>Once again, thanks for joining us.</p>';
					$message_body .= $boundary . $boundary;
					$message_body .= '<p>Yours sincerely,</p>' . $boundary;
					$message_body .= '<p>Genieverse team,</p>';
					$message_body .= '<p><b>genieverse.com</b></p>';
					$message_body .= '<p>' . date('F d, Y.') . '</p>';
					$message_body .= $boundary;
					$message_body .= '</div></html>';
					
					$mail_sent_successfully = mail($submitted_email, $subject, $message_body, join("\r\n", $headers));
					
					mysql_close();
					
					if($mail_sent_successfully)
					{
						$_SESSION["errand_title"] = 'Thank you.';
						$_SESSION["errand"] = '<br><p>A confirmation email has been sent to ' . $submitted_email . '<br>Check your Inbox or Spam folder.</p>';
						$_SESSION["errand"] .=  '<p>To register, click the "Registeration Form" link inside the email.</p>';
					}
					else {
							//The email wasn't sent successfully
							$_SESSION["errand_title"] = '<h1>Sorry!</h1>';
							$_SESSION["errand"] = '<br><p>A confirmation could not be sent to ' . $submitted_email . '</p><br>';
							$_SESSION["errand"] .= '<p><a href="join_us.php">Click here to try again</a></p>';
							$_SESSION["errand"] .= '<p>If this problem persists, <a href="contact_us/correspondence.php">click here to report the problem on our Contact Us page.</p>';
					}
				}//End of if(!isset($_SESSION["errand"]) || !isset($_SESSION["errand_title"]))
		}//End of if($response['success'] == false)
	}//End of if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Send a confirmation to my email")*/

	//Set page to use reCAPTCHA plugin
	$recaptcha_here = "here";

	//Set extra title
	$extra_title = "Join us";

	//Set page template name
	$shop_item = "set";

	//Set email submission button value
	$submitted_email_default = "Your email address";

	//Set page navigation active link 
	$join_us = "active";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>

<?php require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php'); ?>
    <!-- Page Content -->
    <div class="container">
    
		<div class="caption-full">
            <h5 class="pull-right"></h5>
			<h3>Join <a href="index.php">Genieverse</a></h3>
			<hr>
			<br>
			<br>
			<br>
			<br>
		</div>

		<?php
		if(isset($_SESSION["errand"]) || isset($_SESSION["errand_title"]))
		{
			echo '<div class="row">
            <div class="col-lg-12 text-center">
				<div class="alert alert-danger">
					<p>' . $_SESSION["errand"] . '</p>';
			echo '				</div>
			</div>
		</div>';
		}
?>
		
	<?php
		if(isset($_SESSION["errand"]))
		{
			$submitted_email = $submitted_email_default;
		}	?><form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
		<div class="form-group col-lg-12">
            <div class="col-lg-8 col-lg-offset-2 text-center">
				<div>
					<noscript><label>Your email address</label></noscript>
					<input type="text" class="form-control" name="submitted_email" placeholder="<?php echo $submitted_email; ?>">
				</div>
			</div>
		</div>
		
		<div class="form-group col-lg-12 col-md-12">
            <div class="col-md-12 col-lg-8 col-lg-offset-2 text-center">
<?php /*						<p class="panel-title">reCATPCHA</p>*/
?>								<div class="g-recaptcha" data-sitekey="6Lec2A4TAAAAALqB68TEFCSwZJZLQVK8FnzOhJKK">
					</div>
		</div>

		<div class="form-group col-lg-12">
			<div class="row text-center">
				<div class="col-md-12">
					<span class="pull-right"></span>
					<input style="margin-bottom: 25%;" class="btn btn-info" type="submit" name="submit" value="Send a confirmation to my email">
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

</html><?php 
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
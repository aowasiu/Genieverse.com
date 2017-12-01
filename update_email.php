<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
		
	if(!isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] !== 1)
	{
		$_SESSION["errand_title"] = "Log in issue";
		$_SESSION["errand"] = "You must be logged in to change your email address.";
		//Take this visitor to the log in page because he's not logged in
		header("Location: log_in.php?location=" . urlencode($_SERVER["REQUEST_URI"]));
		exit;
	}

	require_once('Server_Includes/visitordbaccess.php');

	//Filter incoming values
	$email_address1 = (isset($_POST["email_address1"])) ? trim($_POST["email_address1"]) : "";
	$email_address2 = (isset($_POST["email_address2"])) ? trim($_POST["email_address2"]) : "";
	
	//Define errors
	$errors = array();

	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Change email address")
	{
		if(empty($email_address1) || empty($email_address2))
		{
			$errors[] = "You didn't enter an email address in both fields.";
		}
		elseif($email_address2 !== $email_address1)
		{
			$errors[] = "Your email address does not match.";
		}
		elseif(!filter_var($email_address2, FILTER_VALIDATE_EMAIL))
		{
			$errors[] = "Your email address is not acceptable.";
		}
		else {
				$safe_email_address = $email_address2;
				
				function does_email_exist($data)
				{
					global $db;
					$query = 'SELECT member_id FROM gv_members WHERE email_address = "' . $data . '"';
					$result = mysql_query($query, $db) or die(mysql_error($db));
					if($result)
					{
						while($row = mysql_fetch_array($result))
						{
							extract($row);
							return $member_id;
						}
					}
					else {
							return 0;
					}
				}
				
				$member_id = does_email_exist($safe_email_address);
				
				if($member_id == $_SESSION["member_id"])
				{
					$errors[] = "You can't use <b>$safe_email_address</b>.<br>The email address is already registered.";
				}
				if($member_id == $_SESSION["member_id"])
				{
					$errors[] = "This is your current email address.<br>You may change it or leave this page to retain it.";
				}
		}
		
		if(count($errors) < 1)
		{
			$full_token = md5(time());
				
			//Find out first, if the member has changed an email address and is yet to confirm the same email address
			$query = 'SELECT member_id FROM gv_email_change_confirmation WHERE member_id = ' . $_SESSION["member_id"];
			$result = mysql_query($query, $db) or die (mysql_error($db));
			
			if(mysql_num_rows($result) > 0)
			{
				//This executes when a member has changed his email address but hasn't confirmed it 
				$query = 'UPDATE gv_email_change_confirmation SET
						changed_email_address="' . mysql_real_escape_string($safe_email_address, $db) . '", dispatch_date=NOW() WHERE member_id=' . $_SESSION["member_id"];
				mysql_query($query, $db) or die (mysql_error($db));
				
				function get_change_id($data1, $data2)
				{
					global $db;
					$query = 'SELECT email_change_id FROM gv_email_change_confirmation 
						WHERE changed_email_address = "' . $data1 . '" AND member_id = ' . $data2;
					$result = mysql_query($query, $db) or die (mysql_error($db));
					$row = mysql_fetch_assoc($result);
					extract($row);
					return $email_change_id;
				}
				
				$confirm_id = get_change_id($safe_email_address, $_SESSION["member_id"]);
			}
			else {
					//This executes when a member MAY have changed his email address but doesn't have confirmation pending
					$query = 'INSERT INTO gv_email_change_confirmation 
						(email_change_id, member_id, dispatch_date, email_change_full_token, changed_email_address) 
					VALUES 
						(NULL, ' . $_SESSION["member_id"] . ', NOW(), "' . mysql_real_escape_string($full_token, $db) . '", "' . mysql_real_escape_string($safe_email_address, $db) . '")';
					mysql_query($query, $db) or die (mysql_error($db));

					$confirm_id = mysql_insert_id($db);
			}

			function get_update_member_profile($data1, $data2)
			{
				global $db;
				$query = 'UPDATE gv_members SET
						email_address="' . mysql_real_escape_string($$data1, $db) . '" WHERE member_id=' . $data2;
				mysql_query($query, $db) or die (mysql_error($db));
			}
			
			$update_member_profile = get_update_member_profile($safe_email_address, $_SESSION["member_id"]);

			$query = 'UPDATE gv_members SET
						email_address="' . mysql_real_escape_string($safe_email_address, $db) . '" WHERE member_id=' . $_SESSION["member_id"];
			
			mysql_query($query, $db) or die (mysql_error($db));
			
			$from_address = 'no_reply@genieverse.com';
			$subject = 'Genieverse: ' . $_SESSION["username"] . ', please confirm ' . $safe_email_address . ' as your new email address.';	
			$boundary = "<br>";							
			$headers = array();
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset="iso-8859-1"';
			$headers[] = 'Content-Transfer-Encoding: 7bit';
			$headers[] = 'From: ' . $from_address;
			$headers[] = 'To: ' . $safe_email_address;

			$message_body = '<html>';
			$message_body .= '<div>';
			$message_body .= '<p>Hello ' . $_SESSION["username"] . '.</p>';
			$message_body .= '<p>Recently, you changed your email address to ' . $safe_email_address . '.</p>';
			$message_body .= '<p>Click the link below to confirm this email address:</p>';
			$message_body .= '<hr noshade style="color: #EEBC1D;">';
			$message_body .= '<p style="color: #EEBC1D;"> === <a href="http://www.genieverse.com/confirm_email.php?confirm_id=' . $confirm_id . '&full_token=' . $full_token . '">I confirm this email</a> === </p>';
			$message_body .= '<hr noshade style="color: #EEBC1D;">';
			$message_body .= '<p>The link above is valid for 48 hours.</p>';
			$message_body .= '<p>If you do not confirm it within that time you will be blocked from accessing <a href="http://www.genieverse.com">Genieverse</a>.</p>';
			$message_body .= $boundary;
			$message_body .= '<p>Yours sincerely,</p><br/><br/>';
			$message_body .= '<p>Genieverse team,</p>';
			$message_body .= '<p><b>Genieverse.com</b></p>';
			$message_body .= '<p>' . date('F d, Y.') . '</p>';
			$message_body .= $boundary;
			$message_body .= '</div></html>';
			
			$mail_sent_successfully = mail($safe_email_address, $subject, $message_body, join("\r\n", $headers));

			if($mail_sent_successfully)
			{
				$_SESSION["errand_title"] = "Congratulations.";
				$_SESSION["errand"] =  "<br>A confirmation link has been sent to <b>$safe_email_address</b>.<br> Check your Inbox or Spam folder.";
				$_SESSION["errand"] .= '<br>The link is valid for 48 hours. If you do not confirm it within that time, you will be blocked from accessing <b>Genieverse</b>.';
				header("Location: issues.php");
				exit;
			}
			else {
					//The email wasn't sent successfully
					$_SESSION["errand_title"] = 'Sorry!';
					$_SESSION["errand"] = '<br>Your email address may not have been changed.<br>';
					$_SESSION["errand"] .= 'Please, report this problem by clicking <a href="http://www.genieverse.com/correspondence.php">here</a> and selecting Complaint as title or category.';
					header("Location: issues.php");
					exit;
			}
		}//End of if(count($errors) < 1)
	}//End of if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Change email address")

	function get_current_email($data)
	{
		global $db;
		$query = 'SELECT email_address FROM gv_members WHERE member_id = ' . $data;
		$result = mysql_query($query, $db) or die (mysql_error($db));
		if($result = mysql_query($query))
		{
			if(mysql_num_rows($result))
			{
				$row = mysql_fetch_assoc($result);
				extract($row);		
			}
		}
		return $row["email_address"];
	}

	$current_email_address = get_current_email($_SESSION["member_id"]);

	//Set extra title
	$extra_title = ucfirst($_SESSION["username"]) . "'s email address edit";

	//Set page template name
	$shop_item = "set";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">
    
		<div class="caption-full">
            <h2>Email address update</h3>
			<h4>Hello <?php echo ucfirst($_SESSION["username"]); ?></h5>
			<hr>
		</div>
	
		<?php
		if($errors)
		{
			echo '<div class="row">
            <div class="col-lg-12 text-center">
				<div class="alert alert-danger">
					<p>Your email address could not be changed:';
			if(count($errors) < 2)
			{
				echo ':</p>';
			}
			else {
					echo 's:</p>';
			}
			echo '<ul style="margin-left: 3%;">';
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
					<h3>Current email address</h3>
					<p class="form-control"><?php echo $current_email_address; ?></p>
				</div>
			</div>
		</div>

		<div class="form-group col-lg-12">
            <div class="col-lg-6 col-lg-offset-3 text-center">
				<div>
					<h3>Enter new email address</h3>
					<input class="form-control" type="text" name="email_address1" value="<?php echo $email_address1; ?>">
				</div>
			</div>
		</div>
		
		<div class="form-group col-lg-12">
            <div class="col-lg-6 col-lg-offset-3 text-center">
				<div>
					<h3>Enter new email address again</h3>
					<input class="form-control" type="text" name="email_address2" value="<?php echo $email_address2; ?>">
				</div>
			</div>
		</div>
		
		<div class="form-group col-lg-12">
            <div class="col-lg-12 text-center">
				<div>
					<input class="btn btn-info" type="submit" name="submit" value="Change email address">
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

</html><?php  mysql_close(); ?>
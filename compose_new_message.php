<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	if(!isset($_SESSION["logged_in"]))
	{
		$_SESSION["errand"] = "You must be logged in to enter member area.";
		//Take this visitor to the log in page because he's not logged in
		header("Location: log_in.php?location=" . urlencode($_SERVER["REQUEST_URI"]));
		exit;
	}
	
	$recipient_id = (isset($_GET["id"])) ? $_GET["id"]: 0;
	$recipient = (isset($_POST["recipient"])) ? $_POST["recipient"]: $recipient_id;
	$message = (isset($_POST["message"])) ? $_POST["message"]: "";
	$last_id = (isset($_GET["last_id"])) ? $_GET["last_id"]: 0;
	$the_id = (isset($_POST["last_id"])) ? $_POST["last_id"]: $last_id; 
	if(!isset($recipient_id))
	{
		$_SESSION["errand"] = "The member you want to send a message does not exist.";
		header("Location: services.php");
		exit;
	}
	
	//Declare errors
	$errors = array();
	
	//Connect to the database
	require_once('Server_Includes/visitordbaccess.php');
	
	//Get recipients_username function
	require_once('Server_Includes/scripts/common_scripts/get_name_functions.php');
	
	$recipients_username = recipients_username($recipient);

	if($recipients_username == "")
	{
		$_SESSION["errand"] = "The member you want to send a message does not exist.";
		header("Location: services.php");
		exit;
	}
	
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["send"] == "Send message")
	{
		//Ensure the recipient exists
		if(!empty($message))
		{
			$max_length_of_message = 200;
			if(strlen($message) > $max_length_of_message)
			{
				$errors[] = "Your message is longer than $max_length_of_message characters.";
			}
		}
		
		if(count($errors) < 1)
		{
			//Get check_input function
			require_once('Server_Includes/scripts/common_scripts/check_input.php');
			
			$safe_message = check_input($message);
			
			//Now save all in database
			$query = 'INSERT INTO gv_general_messages
						(general_message_id, gv_gen_message_created_on, gv_gen_blocked, gv_gen_replied, gv_gen_read, gv_gen_read_on, gv_gen_message_assessment, gv_gen_message_assessed_on, gv_gen_message_assessed_by, gv_gen_message_body, gv_gen_message_from, gv_gen_message_to)
						VALUES 
						(NULL, NOW(), 0, 0, 0, NULL, 0, NULL, NULL, "' . mysql_real_escape_string(filter_var($safe_message, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '", "' . $_SESSION["member_id"] . '", "' . mysql_real_escape_string($recipient, $db) . '")';
			mysql_query($query, $db) or die(mysql_error($db));
			
			$_SESSION["errand"] = "Message sent to " . ucfirst($recipients_username);
			
			mysql_close($db);
			
			if($the_id == 0)
			{
				header("Location: member_profile.php?id=$recipient");
				exit;
			}
			else {
					header("Location: services.php");
					exit;
			}
		}//End of if(count($errors) < 1)
	}//End of if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["send"] == "Send message")	

	//Set extra title
	$extra_title = "Private message to " . ucfirst($recipients_username);

	//Set page template
	$shop_item = "set";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">
    
		<div class="caption-full">
            <h5 class="pull-right"></h5>
			<h3>Message to <?php echo ucfirst($recipients_username); ?></h3>
			<hr>
		</div>
	
		<?php if(!isset($last_id) || $last_id == 0){ echo ""; } else { echo '<div class="caption-full">
            <h5 class="pull-right btn btn-info"><a href="messages.php">Back to Inbox</a></h5>
			<h5 class="btn btn-info"><a href="message_view.php?id=' . $last_id . '">Back to last message</a></h5>
			<hr>
		</div>'; } ?>
	
		<?php
		if($errors)
		{
			echo '<div class="row">
            <div class="col-lg-12 text-center">
				<div class="alert alert-danger">
					<p>Your message could not be sent:</p>';
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
            <div class="col-lg-12 text-center">
				<div>
					<p>200 characters maximum</p>
				</div>
			</div>
		</div>
		
		<div class="form-group col-lg-12">
            <div class="col-lg-12 text-center">
				<div>
					<textarea name="message" id="message" maxlength=""  class="form-control" ><?php echo $message; ?></textarea>
					<script> CKEDITOR.replace( 'message' ); </script>
				</div>
			</div>
		</div>
		
		<div class="form-group col-lg-12">
			<div class="row text-center">
				<div class="col-md-12">
					<span class="pull-right"></span>
					<h4></h4>
					<input type="hidden" name="last_id" value="<?php echo $the_id; ?>">
					<input type="hidden" name="recipient" value="<?php echo $recipient; ?>">
					<input class="btn btn-info" type="submit" name="send" value="Send message">
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
	mysql_close($db);
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
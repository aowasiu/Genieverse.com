<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	//Filter incoming values
	$id 				= (isset($_GET["id"])) ? $_GET["id"] : "";
	$the_table			= (isset($_GET["db"])) ? $_GET["db"] : "";
	$post_id			= (isset($_POST["id"])) ? $_POST["id"] : $id;
	$post_the_table		= (isset($_POST["table"])) ? $_POST["table"] : $the_table;
	$report_email		= (isset($_POST["report_email"])) ? $_POST["report_email"] : "";
	$report_fullname	= (isset($_POST["report_fullname"])) ? $_POST["report_fullname"] : "";
	$report_comment		= (isset($_POST["report_comment"])) ? $_POST["report_comment"] : "";
	$report_title		= (isset($_POST["report_title"])) ? $_POST["report_title"] : 0;

	//Ensure there is no attempted hack
	if($post_id == 0 || $post_the_table == "")
	{
		header("Location: index.php");
		exit;
	}

	//Set errors as array
	$errors = array();

	//Connect to the database
	require_once('Server_Includes/visitordbaccess.php');

	//Set the table names and column names for filtered variables
	if($post_the_table == 'mall')
	{
		$table_name = 'gv_mall_post';
		$column_name = 'mall_post_id';
	}
	elseif($post_the_table == 'mall_photo')
	{
		$table_name = 'gv_mall_post_image';
		$column_name = 'mall_image_filename';
	}
	elseif($post_the_table == 'voice')
	{
		$table_name = 'gv_voice_post';
		$column_name = 'voice_post_id';
	}
	elseif($post_the_table == 'voice_photo')
	{
		$table_name = 'gv_voice_post_image';
		$column_name = 'voice_image_filename';
	}
	elseif($post_the_table == 'voiceComment')
	{
		$table_name = 'gv_voice_post_comments';
		$column_name = 'voice_comment_id';
	}
	elseif($post_the_table == 'spotlight')
	{
		$table_name = 'gv_spotlight_post';
		$column_name = 'spotlight_post_id';
	}
	elseif($post_the_table == 'spotlight_photo')
	{
		$table_name = 'gv_spotlight_post_image';
		$column_name = 'spotlight_image_filename';
	}
	elseif($post_the_table == 'spotlightComment')
	{
		$table_name = 'gv_spotlight_post_comments';
		$column_name = 'spotlight_comment_id';
	}
	elseif($post_the_table == 'board')
	{
		$table_name = 'gv_board_post';
		$column_name = 'board_post_id';
	}
	elseif($post_the_table == 'board_photo')
	{
		$table_name = 'gv_board_post_image';
		$column_name = 'board_image_filename';
	}
	elseif($post_the_table == 'boardComment')
	{
		$table_name = 'gv_board_post_comments';
		$column_name = 'board_comment_id';
	}
	elseif($post_the_table == 'hearts')
	{
		$table_name = 'gv_hearts_post';
		$column_name = 'hearts_post_id';
	}
	elseif($post_the_table == 'hearts_photo')
	{
		$table_name = 'gv_hearts_post_image';
		$column_name = 'hearts_image_filename';
	}
	elseif($post_the_table == 'heartsComment')
	{
		$table_name = 'gv_hearts_post_comments';
		$column_name = 'hearts_comment_id';
	}
	elseif($post_the_table == 'profile')
	{
		$table_name = 'gv_member_profile';
		$column_name = 'member_id';
	}
	elseif($post_the_table == 'profile_photo')
	{
		$table_name = 'gv_profile_image';
		$column_name = 'profile_image_id';
	}
	else {
		//Do nought
	}
	
	//Ensure the id variable exists in Genieverse database by checking it against the corresponding table
	$query = 'SELECT ' . $column_name . ' FROM ' . $table_name . ' WHERE ' . $column_name . ' = "' . $post_id . '"';
	$result = mysql_query($query, $db) or die(mysql_error($db));
	
	if($result = mysql_query($query))
	{
		if(mysql_num_rows($result) < 1)
		{
			//No data is found
			$_SESSION["errand_title"] = "Non-existent content";
			$_SESSION["errand"] = "The content you intend to report does not exist." . "<br>You can't report content that doesn't exist.";
			$_SESSION["errand"] .= '<br><a href="/genieverse.com/index.php">Click here to continue.</a>';
			header("Location: issues.php");
			exit;
		}
	}
	else {
			//Query failed
			$errors[] = "Check the post again because we've no such post.";
	}

	//Define variable and set the default value to empty
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Send my report")
	{
		$report_email;
		$report_fullname;
		$report_comment;
		$report_title;
		$id;
		$table;
		$captcha;
	
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
			$issue_title = "Join us issue";
			$errors[] = "Your response to the CAPTCHA is incorrect.";
		}
		else {
				//reCAPTCHA is correct and... 
				if($report_title == 0)
				{
					$errors[] = "You didn't select a title.";
				}
				
				if(empty($report_fullname))
				{
					$errors[] = "You didn't enter a full name.";
				}
				else {
						if(!preg_match('/\s/', $report_fullname))
						{
							$errors[] = "Your name is not valid.";
						}
				}
				
				if(empty($report_email))
				{
					$errors[] = "You didn't enter an email address.";
				}
				else {
						if(!filter_var($report_email, FILTER_VALIDATE_EMAIL))
						{
							$errors[] = "Your email address is not valid.";
						}
				}
		
				if(count($errors) < 1)
				{
					function check_input($data)
					{
						$data = trim($data);
						$data = stripslashes($data);
						$data = htmlspecialchars($data);
						return $data;
					}
					
					$clean_comment = check_input($report_comment);

					//Enter the detail in a database
					$query = 'INSERT INTO gv_service_report_message 
							(report_id, report_assessed, report_assessed_by, report_assessed_on, report_category_id, reporter, report_table_name, report_table_number, report_comment, report_date)
							VALUES
							(NULL, 0, NULL, NOW(), ' . mysql_real_escape_string($report_title, $db) . ', "' . mysql_real_escape_string($report_fullname, $db) . '", "' . mysql_real_escape_string($table_name, $db) . '", "' . mysql_real_escape_string($post_id, $db) . '", "' . mysql_real_escape_string($clean_comment, $db) . '", NOW())';
					mysql_query($query, $db) or die(mysql_error($db));
					
					$_SESSION["errand_title"] = "The report has been submitted.";
					$_SESSION["errand"] = 'Thanks for helping us improve <a href="index.php">Genieverse</a>.';
					$_SESSION["errand"] .= '<br>An email will be sent to <b>' . $report_email . '</b> when a decision is reached.';
					header("Location: issues.php");
					exit;
				}//End of if(count($errors) < 1)
			}//End of if($response['success'] == false)	
	}//End of if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Send my report")

	//Set extra title
	$extra_title = "Reporting Content";

	//Set extra script
	$extra_script = "\n" . "<script src='https://www.google.com/recaptcha/api.js'></script>" . "\n";

	//Set page template name
	$business_casual = "Set Biz-casual template";

	//Set CKEditor plugin for use on this page
	$ckeditor = "set";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header2.php');	?>
    <!-- Page Content -->
    <div class="container">
	
		<div class="row">
            <div class="box">
                <div class="col-lg-12">
                    <hr>
                    <h2 class="intro-text text-center">Content Report</h2>
                    <hr>
                    <h4 class="intro-text text-center">Fields marked, *, are mandatory</h4>
                    <hr>
					<?php

					if(count($errors) > 0)
					{
						echo '<p class="alert alert-danger">Your report could not be submitted:</p>' . "\n";
						echo '                    <ul class="alert alert-danger">' . "\n";
						foreach($errors as $error)
						{
							echo '                    	<li>' . $error . '</li>' . "\n";
						}
						echo '                    </ul>' . "\n";
						echo '                    <hr>' . "\n";
					}

?>
                    <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="row">
                            <div class="form-group col-lg-3">
                                <label>Title *</label>
								<select class="form-control" name="report_title">
								   <option value="0">Select Report Title</option>
								   <?php
									$query = 'SELECT report_category_id, report_category_name FROM gv_service_report_category ORDER BY report_category_name';
									$result = mysql_query($query, $db) or die(mysql_error($db));
									
									while($row = mysql_fetch_array($result))
									{ ?>
									<option <?php if(isset($report_title) && $report_title == $row["report_category_id"]){ echo 'selected="selected" ';} ?>value="<?php echo $row["report_category_id"]; ?>"><?php echo $row["report_category_name"]; ?></option>
							<?php
									}
									?>
								   </select>
                            </div>
							<div class="form-group col-lg-6">
                                <label>Comment</label>
								<textarea class="form-control" rows="3" name="report_comment" id="report_comment"/><?php echo $report_comment; ?></textarea>
								<script> CKEDITOR.replace( 'report_comment' ); </script>
                            </div>
							<div class="form-group col-lg-3">
								<label>Full name*</label>
								<?php
								   if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == 1)
								   {
										function member_fullname($data)
										{
											global $db;
											$query = 'SELECT firstname, surname FROM gv_member_profile WHERE member_id = ' . $data;
											$result = mysql_query($query, $db) or die(mysql_error($db));
											$row = mysql_fetch_assoc($result);
											extract($row);
											$fullname = $firstname . ' ' . $surname;
											return $fullname;
										}
										$my_name = member_fullname($_SESSION["member_id"]);
										echo '<p class="form-control"><b>' . $my_name . '</b></p>'; ?><input type="hidden" class="form-control" name="report_fullname" value="<?php echo $my_name; ?>">
							<?php	   }
								   else {
											echo $report_fullname;
							?><input type="text" class="form-control" name="report_fullname" maxlength="40" value="<?php echo $report_fullname; ?>" /><?php } ?>
                            </div>
							<div class="clearfix"></div>
							<div class="form-group col-lg-4">
                                <label>E-mail *</label>
								<?php
								   if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == 1)
								   {
										function member_email($data)
										{
											global $db;
											$query = 'SELECT email_address FROM gv_members WHERE member_id = ' . $data;
											$result = mysql_query($query, $db) or die(mysql_error($db));
											$row = mysql_fetch_assoc($result);
											extract($row);
											return $email_address;
										}
										$my_email = member_email($_SESSION["member_id"]);
										echo '<p class="form-control"><b>' . $my_email . '</b></p>'; ?><input type="hidden" class="form-control" name="report_email" value="<?php echo $my_email; ?>" /></td>
							<?php
								   }
								   else {
							?><input type="text" class="form-control" name="report_email" value="<?php echo $report_email; ?>" /><?php } ?>
							</div>
                            <div class="form-group col-lg-8">
                                <label>Respond to the CAPTCHA</label>
								<div class="g-recaptcha" data-sitekey="6Lec2A4TAAAAALqB68TEFCSwZJZLQVK8FnzOhJKK"></div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-lg-12 text-center">
                                <input type="hidden" name="id" value="<?php echo $post_id; ?>">
								<input type="hidden" name="table" value="<?php echo $post_the_table; ?>">
								<input type="submit" class="btn btn-default" name="submit" value="Send my report">
							</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container -->
	
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p><?php echo $the_footer; ?></p>
                </div>
            </div>
        </div>
    </footer>

<?php require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_before_body_end2.php'); ?>

</body>

</html><?php mysql_close(); ?>
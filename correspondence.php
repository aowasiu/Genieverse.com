<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	//Filter incoming values
	$contact_email		= (isset($_POST["contact_email"])) ? $_POST["contact_email"] : "";
	$contact_fullname	= (isset($_POST["contact_fullname"])) ? $_POST["contact_fullname"] : "";
	$contact_phone		= (isset($_POST["contact_phone"])) ? $_POST["contact_phone"] : "";
	$country_id			= (isset($_POST["country_id"])) ? $_POST["country_id"] : 0;
	$service_id			= (isset($_POST["service_id"])) ? $_POST["service_id"] : 0;
	$contactus_category	= (isset($_POST["contactus_category"])) ? $_POST["contactus_category"] : 0;
	$body				= (isset($_POST["body"])) ? $_POST["body"] : "";

	//Set error as array
	$errors = array();
	
	//Connect to the database
	require_once('Server_Includes/visitordbaccess.php');
	
	//Define variable and set the default value to empty
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$captcha;
		$contact_email;
		$contact_fullname;
		$contact_phone;
		$country_id;
		$service_id;
		$contactus_category;
		$body;
	
		if(isset($_POST['g-recaptcha-response']))
		{
			$captcha = $_POST['g-recaptcha-response'];
		}
		
		if(!$captcha)
		{
			$errors[] = "You didn't respond to the <b>CAPTCHA</b>.";
		}
		
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lec2A4TAAAAAP1jR8YS8zOPstD_m20Acn_qNX23=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
	
		if($response.success==false)
		{
			$errors[] = "Your response to the CAPTCHA is incorrect.";
		}
		else {
				if($service_id == 0)
				{
					$errors[] = "You didn't select a Service name.";
				}
				
				if($contactus_category == 0)
				{
					$errors[] = "You didn't select the nature of this message";
				}

				if(empty($body))
				{
					$errors[] = "The body section of the form is unfilled.";
				}
				else {
						$max_length_body = 1000;
						if(strlen($body) > $max_length_body)
						{
							$errors[] = "The body of the mail must not be more than 1000 characters in length.";
						}
				}
				
				if(empty($contact_fullname))
				{
					$errors[] = "You didn't enter a full name.";
				}
				else {
						if(!preg_match('/\s/', $contact_fullname))
						{
							$errors[] = "Your name is not valid full name.";
						}
						else {
								$max_length_fullname = 30;
								if(strlen($contact_fullname) > $max_length_fullname)
								{
									$errors[] = "Your name should not be more than 30 characters in length.";
								}								
						}
				}
				
				if(empty($contact_email))
				{
					$errors[] = "You didn't enter an email address.";
				}
				else {
						if(!filter_var($contact_email, FILTER_VALIDATE_EMAIL))
						{
							$errors[] = "Your email address is not valid.";
						}
				}				
				
				if($country_id == 0)
				{
					$errors[] = "You didn't select your country.";
				}
				
				if(count($errors) < 1)
				{
					require_once('Server_Includes/scripts/common_scripts/check_input.php');
					
					$clean_body = check_input($body);

					$query = 'INSERT INTO gv_contactus_messages
								(contactus_msg_id, contactus_is_read, contactus_date, cc_id, service_id, contactus_category_id, contactus_msg_body, contactus_sender_name, contactus_sender_email, contactus_sender_phone)
								VALUES
								(NULL, 0, NOW(), ' . mysql_real_escape_string($country_id, $db) . ', ' . mysql_real_escape_string($service_id, $db) . ', ' . mysql_real_escape_string($contactus_category, $db) . ', "' . mysql_real_escape_string(filter_var($clean_body, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '", "' . mysql_real_escape_string($contact_fullname, $db) . '", "' . mysql_real_escape_string($contact_email, $db) . '", "' . mysql_real_escape_string($contact_phone, $db) . '")';
					mysql_query($query, $db) or die(mysql_error($db));
					
					$_SESSION["errand_title"] = "The message has been sent.";
					$_SESSION["errand"] = "Thanks for dropping us a note.";
$_SESSION["errand"] .= " <a href='index.php'>Click here to go to the home page</a>.";
					header("Location: issues.php");
					exit;
				}//End of if(count($errors) < 1)		
		}//End of if($response.success==false)
	}//End of if($_SERVER["REQUEST_METHOD"] == "POST")

	//Set extra title
	$extra_title = "Correspondence | Contact us ";

	//Set extra script
	$extra_script = "\n" . "	<script src='https://www.google.com/recaptcha/api.js'></script>" . "\n";

	//Set page template name
	$business_casual = "Set Biz-casual template";

	//Set page to use ckeditor plugin
	$ckeditor = "set";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head2.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header3.php');	?>
    <!-- Page Content -->
    <div class="container">
	
		<div class="row">
            <div class="box">
                <div class="col-lg-12">
                    <hr>
                    <h2 class="intro-text text-center">Contact us</h2>
                    <hr>
                    <h4 class="intro-text text-center">Fields marked, *, are mandatory</h4>
                    <hr>
					<?php

		if(count($errors) > 0)
		{
			echo '<p class="alert alert-danger text-center">Your message could not be submitted:</p>' . "\n";
			echo '                    <ul class="alert alert-danger text-center">' . "\n";
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
                            <div class="form-group col-lg-12">
                                <h3>About us</h3>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-lg-4">
                                <label>Service *</label>
								<select class="form-control" name="service_id">
									<option value="0">Select service</option>
									<?php
									$query = 'SELECT service_id, service_name FROM gv_service WHERE is_active = 1 ORDER BY service_name';
									$result = mysql_query($query, $db) or die(mysql_error($db));
									
									while($row = mysql_fetch_array($result))
									{ ?><option <?php if(isset($service_id) && $service_id == $row["service_id"]){ echo 'selected="selected" ';} ?>value="<?php echo $row["service_id"]; ?>"><?php echo $row["service_name"]; ?></option>
									<?php } ?>
</select>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Nature of message *</label>
								<select class="form-control" name="contactus_category">
									<option value="0">Select message nature</option>
									<?php
									$query = 'SELECT contactus_category_id, contactus_category_name FROM gv_contactus_category';
									$result = mysql_query($query, $db) or die(mysql_error($db));
									
									while($row = mysql_fetch_array($result))
									{ ?><option <?php if(isset($contactus_category) && $contactus_category == $row["contactus_category_id"]){ echo 'selected="selected" ';} ?>value="<?php echo $row["contactus_category_id"]; ?>"><?php echo $row["contactus_category_name"]; ?></option>
									<?php } ?>
</select>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Message *</label>
								<textarea class="form-control" rows="6" name="body" id="body"><?php echo $body; ?></textarea>
						<script> CKEDITOR.replace( 'body' ); </script>
                            </div>
						<div class="clearfix"></div>
                            <div class="form-group col-lg-12">
                                <h3>About you</h3>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-lg-4">
                                <label>Full name *</label>
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
										echo '<p class="form-control"><b>' . $my_name . '</b></p>'; ?><input type="hidden" name="contact_fullname" value="<?php echo $my_name; ?>">
							<?php	}
								   else { ?><input type="text" class="form-control" maxlength="40" name="contact_fullname" value="<?php echo $contact_fullname; ?>">
							<?php } ?>
</div>
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
										echo '<p class="form-control"><b>' . $my_email . '</b></p>'; ?><input type="hidden" name="contact_email" value="<?php echo $my_email; ?>">

<?php } else { ?><input type="text" class="form-control" name="contact_email" value="<?php echo $contact_email; ?>"><?php } ?>

							</div>
                            <div class="form-group col-lg-4">
                                <label>Phone</label>
								<?php
								   if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == 1)
								   {
										function member_phone($data)
										{
											global $db;
											$query = 'SELECT phone FROM gv_member_profile WHERE member_id = ' . $data;
											$result = mysql_query($query, $db) or die(mysql_error($db));
											$row = mysql_fetch_assoc($result);
											extract($row);
											return $phone;
										}
										$my_phone = member_phone($_SESSION["member_id"]);
										echo '<p class="form-control"><b>' . $my_phone . '</b></p>'; ?><input type="hidden" name="contact_phone" value="<?php echo $my_phone; ?>">
<?php } else { ?><input type="text" class="form-control" name="contact_phone" value="<?php echo $contact_phone; ?>"><?php } ?>

                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-lg-4">
                                <label>Country *</label>
								<?php
								   if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == 1)
								   {
										function member_country($data)
										{
											global $db;
											$query = 'SELECT country_name FROM geoipwhois_cc WHERE cc_id = ' . $data;
											$result = mysql_query($query, $db) or die(mysql_error($db));
											$row = mysql_fetch_assoc($result);
											extract($row);
											return $country_name;
										}
										
										function member_country_id($data)
										{
											global $db;
											$query = 'SELECT cc_id FROM gv_member_profile WHERE member_id = ' . $data;
											$result = mysql_query($query, $db) or die(mysql_error($db));
											$row = mysql_fetch_assoc($result);
											extract($row);
											return $cc_id;
										}
										$my_country = member_country($_SESSION["member_id"]);
										$my_country_id = member_country_id($_SESSION["member_id"]);
										echo '<p class="form-control"><b>' . $my_country . '</b></p>'; ?><input type="hidden" name="country_id" value="<?php echo $my_country_id; ?>"><?php } else { ?><select class="form-control" name="country_id">
									<option value="0">Select country</option>
									<?php
									$query = 'SELECT cc_id, country_name FROM geoipwhois_cc WHERE is_country = 1 ORDER BY country_name';
									$result = mysql_query($query, $db) or die(mysql_error($db));
									
									while($row = mysql_fetch_array($result))
									{ ?><option <?php if(isset($country_id) && $country_id == $row["cc_id"]){ echo 'selected="selected" ';} ?>value="<?php echo $row["cc_id"]; ?>"><?php echo $row["country_name"]; ?></option>
									<?php } ?>
</select><?php } ?>

                            </div>
                            <div class="form-group col-lg-8">
                                <label>Respond to the CAPTCHA</label>
								<div class="g-recaptcha" data-sitekey="6Lec2A4TAAAAALqB68TEFCSwZJZLQVK8FnzOhJKK"></div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-lg-12 text-center">
								<input type="submit" class="btn btn-default" name="submit" value="Send my message">
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
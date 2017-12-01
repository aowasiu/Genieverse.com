<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	if(!isset($_SESSION["logged_in"]))
	{
		//Take this visitor to the log in page because he's not logged in
		header("Location: log_in.php?location=" . urlencode($_SERVER["REQUEST_URI"]));
		exit;
	}

	//Connect to database
	require_once('Server_Includes/visitordbaccess.php');

	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["update"] == "Save Update")
	{
		//Set errors
		$errors = array();

		//Set variables and default values
		$firstname 				= (isset($_POST["firstname"])) ? ucfirst($_POST["firstname"]) : "";
		$middlename 			= (isset($_POST["middlename"])) ? ucfirst($_POST["middlename"]) : "";
		$surname 				= (isset($_POST["surname"])) ? ucfirst($_POST["surname"]) : "";
		$state_name 			= (isset($_POST["state_name"])) ? ucfirst($_POST["state_name"]) : "";
		$city_name		 		= (isset($_POST["city_name"])) ? ucfirst($_POST["city_name"]) : "";
		$address 				= (isset($_POST["address"])) ? ucwords($_POST["address"]) : "";
		$postcode		 		= (isset($_POST["postcode"])) ? $_POST["postcode"] : "";
		$phone 					= (isset($_POST["phone"])) ? $_POST["phone"] : "";
		$about_me		 		= (isset($_POST["about_me"])) ? $_POST["about_me"] : "";
		$hobbies 				= (isset($_POST["hobbies"])) ? $_POST["hobbies"] : "";
		
		$names = array('Firstname' => $firstname, 'Surname' => $surname);
	
		foreach($names as $key => $value)
		{
			if(empty($value))
			{
				$errors[] = "You didn't enter a name as $key";
			}
			elseif(!preg_match("/^[a-zA-Z]*$/",$value))
			{
				$errors[] = 'Your ' . $key . ", <b>$value</b>, is not valid.";
			}
			elseif(preg_match('/\s/', $value))
			{
				$errors[] = "Your $key should not contain spaces.";
			}
			else {
					//Do nought
			}
		}
		if(empty($middlename))
		{
			$middlename = "";
		}
		
		if(empty($state_name) || empty($city_name))
		{
			$errors[] = 'What state and town you are in?';
		}
		
		if(!isset($phone) && !is_int($phone))
		{ 
			$errors[] = "Your phone number is not given";
		}
		
		if(!empty($about_me))
		{
				if(strlen($about_me) > 200)
				{
					$errors[] = "<b>About me</b> must not be more than 200 characters.";
				}
		}
		else {
				$about_me = "";
		}

		if(empty($hobbies))
		{
			$hobbies = "";
		}
		
		//Ensure the given phone number is non-existent
		function checking_phone_number($data)
		{
			global $db;
			$query = 'SELECT phone FROM gv_member_profile WHERE phone = ' . $data;
			$result = mysql_query($query, $db) or die(mysql_error($db));
			$row = mysql_fetch_assoc($result);
			extract($row);
			return $phone;
		}
		
		function checking_phone_number_owner($data)
		{
			global $db;
			$query = 'SELECT member_id FROM gv_member_profile WHERE phone = ' . $data;
			$result = mysql_query($query, $db) or die(mysql_error($db));
			$row = mysql_fetch_assoc($result);
			extract($row);
			return $member_id;
		}
		
		$phone_found = checking_phone_number($phone);
		$phone_found_owner = checking_phone_number_owner($phone);
		
		if($phone_found == $phone && $phone_found_owner !== $_SESSION["member_id"])
		{
			$errors[] = '<b>' . $phone . '</b> is registered to another member. You must provide a diffrent phone number.';
		}
		
		if(count($errors) < 1)
		{
			require_once('Server_Includes/scripts/common_scripts/check_input.php');
			
			//There's no error, proceed with entering data in the database
			$safe_firstname			= check_input($firstname);
			$safe_middlename 		= check_input($middlename);
			$safe_surname 			= check_input($surname);
			$safe_state_name 		= check_input($state_name);
			$safe_city_name 		= check_input($city_name);
			$safe_address 			= check_input($address);
			$safe_postcode 			= check_input($postcode);
			is_int($phone);
			$safe_about_me 			= check_input($about_me);
			$safe_hobbies 			= check_input($hobbies);
			
			//Capitalise the first character or word in each word
			$correct_firstname 		= ucfirst($safe_firstname);
			$correct_middlename		= ucfirst($safe_middlename);
			$correct_surname		= ucfirst($safe_surname);
			$correct_state_name		= ucfirst($safe_state_name);
			$correct_city_name		= ucfirst($safe_city_name);
			$correct_address		= ucfirst($safe_address);
			$correct_about_me		= ucfirst($safe_about_me);
			$correct_hobbies		= ucwords($safe_hobbies);
			
			//Insert the OKAY fields/values in the database
			$query = 'UPDATE gv_member_profile SET 
			state_name="' . mysql_real_escape_string($state_name, $db) . '",  city_name="' . mysql_real_escape_string($city_name, $db) . '", edit_date=NOW(), firstname="' . mysql_real_escape_string($firstname, $db) . '", middlename="' . mysql_real_escape_string($middlename, $db) . '", surname="' . mysql_real_escape_string($surname, $db) . '", address="' . mysql_real_escape_string($correct_address, $db) . '", postcode="' . mysql_real_escape_string($postcode, $db) . '", phone="' . mysql_real_escape_string($phone, $db) . '", about_me="' . mysql_real_escape_string($about_me, $db) . '", hobbies="' . mysql_real_escape_string($hobbies, $db) . '" WHERE member_id = ' . $_SESSION["member_id"];
			mysql_query($query, $db) or die(mysql_error());

			mysql_free_result();
			mysql_close();

			$_SESSION["errand"] = "Editing saved successfully";
			header("Location: profile.php");
			exit;
		}
	}

	$query = 'SELECT 
			username, email_address, continent_id, cc_id, state_name, city_name, firstname, middlename, surname, address, postcode, phone, about_me, hobbies 
			FROM 
			gv_members AS members,
			gv_member_profile AS profile
			WHERE members.member_id = profile.member_id 
			AND username = "' . mysql_real_escape_string($_SESSION["username"], $db) . '"';
	$result = mysql_query($query, $db) or die (mysql_error($db));
	
	if($result = mysql_query($query))
	{
		if(mysql_num_rows($result))
		{
			$row = mysql_fetch_assoc($result);
			extract($row);
			
			$username 			= $row["username"];
			$email_address 		= $row["email_address"];
			$firstname			= $row["firstname"];
			$middlename			= $row["middlename"];
			$surname			= $row["surname"];
			$continent_id		= $row["continent_id"];
			$country_id			= $row["cc_id"];
			$state_name			= $row["state_name"];
			$city_name			= $row["city_name"];
			$address			= $row["address"];
			$postcode			= $row["postcode"];
			$phone				= $row["phone"];
			$about_me			= $row["about_me"];
			$hobbies			= $row["hobbies"];
		}
	}

	//Set extra title
	$extra_title = ucfirst($_SESSION["username"]) . "'s profile edit";

	//Get name functions
	require_once('Server_Includes/scripts/common_scripts/get_name_functions.php');

	//Set page template name
	$business_casual = "Set Biz-casual template";

	//Set CKEditore plugin forthis page
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
                    <h2 class="intro-text text-center">Profile update</h2>
                    <hr>
                    <h4 class="intro-text text-center">Fields marked, *, are mandatory</h4>
                    <hr>
					<?php

		if(count($errors) > 0)
		{
			echo '<p class="alert alert-danger">Your update could not be saved:</p>' . "\n";
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
                            <div class="form-group col-lg-6">
                                <label>Username *</label>
								<?php	echo '<p class="form-control"><b>' . $username . '</b></p>'; ?>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Email Address *	<a href="update_email.php">Change your email</a></label>
                                <?php
								if(isset($email_address) && !empty($email_address))
								{
									//$post_email_address = $email_address
									echo '<p class="form-control"><b>' . $email_address . '</b></p>';
								}
								else {
										echo '<p class="form-control"><b>' . $post_email_address . '</b></p>';
								}					
								?>
								<input type="hidden" name="email_address" value="<?php 
								if(isset($email_address) && !empty($email_address))
								{
									//$post_email_address = $email_address
									echo $email_address;
								}
								else {
										echo $post_email_address;;
								}; ?>">
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-lg-4">
                                <label>First name *</label>
								<input type="text" class="form-control" name="firstname" id="firstname" value="<?php	echo $firstname;	?>">
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Middle name</label>
								<input type="text" class="form-control" name="middlename" id="middlename" value="<?php	echo $middlename;	?>">
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Surname *</label>
								<input type="text" class="form-control" name="surname" id="surname" value="<?php	echo $surname;	?>">
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-lg-4">
                                <label>Country *</label>
								<?php echo '<p class="form-control"><b>' . country_name($row["cc_id"]) . '</b></p>'; ?>
								<input type="hidden" class="form-control" name="country_id" id="country_id" value="<?php	echo $row["cc_id"];	?>">
							</div>
                            <div class="form-group col-lg-4">
                                <label>State *</label>
								<input type="text" class="form-control" name="state_name" id="state_name" value="<?php	echo $state_name;	?>">
                            </div>
                            <div class="form-group col-lg-4">
                                <label>City/Town *</label>
								<input type="text" class="form-control" name="city_name" id="city_name" value="<?php	echo $city_name;	?>">
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-lg-4">
                                <label>Address</label>
								<textarea class="form-control" rows="3" name="address" id="address"><?php	echo $address;	?></textarea>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Postcode</label>
								<input type="text" class="form-control" name="postcode" id="postcode" value="<?php	echo $postcode;	?>">
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Phone *</label>
								<input type="tel" class="form-control" name="phone" id="phone" value="<?php echo $phone; ?>">
                            </div>
                            <div class="clearfix"></div>
							<div class="form-group col-lg-6">
                                <label>About me</label>
								<textarea class="form-control" rows="3" name="about_me" id="about_me"><?php	echo $about_me;	?></textarea>
								<script> CKEDITOR.replace( 'about_me' ); </script>
                            </div>							
							<div class="form-group col-lg-6">
                                <label>Hobbies/Interests</label>
								<input type="text" class="form-control" name="hobbies" id="hobbies" value="<?php	echo $hobbies;	?>">
							</div>
                            <div class="clearfix"></div>
                            <div class="form-group col-lg-12">
								<input type="hidden" name="confirm_id" id="confirm_id" value="<?php	echo $confirm_id;	?>">
								<input type="hidden" name="full_token" id="full_token" value="<?php	echo $full_token;	?>">
								<input type="submit" class="btn btn-default" name="update" value="Save Update">
<?php 
//                               <button type="submit" class="btn btn-default">Submit</button>
?>
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

<?php require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_before_body_end.php'); ?>

</body>

</html><?php	mysql_close(); ?>
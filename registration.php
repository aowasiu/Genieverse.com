<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	//Default values for confirm_id and full_token. Confirm_id and full_token are drawn from the url of confirmation email
	$post_confirm_id = (isset($_POST["confirm_id"])) ? $_POST["confirm_id"] : 0;
	$post_full_token = (isset($_POST["full_token"])) ? trim($_POST["full_token"]) : "";
	$confirm_id = (isset($_GET["confirm_id"])) ? $_GET["confirm_id"] : $post_confirm_id;
	$full_token = (isset($_GET["full_token"])) ? trim($_GET["full_token"]) : $post_full_token;

	if($confirm_id == 0 || $full_token == "")
	{
		header("Location: join_us.php");
		exit;
	}
	
	$registration_button_text = "Register now";

	//Declare errors
	$errors = array();
	
	require_once('Server_Includes/visitordbaccess.php');
	
	//Filter incoming values
	$username 				= (isset($_POST["username"])) ? $_POST["username"] : "Username";
	$post_email_address 	= (isset($_POST["email_address"])) ? trim($_POST["email_address"]) : "";
	$password1 				= (isset($_POST["password1"])) ? trim($_POST["password1"]) : "";
	$password2 				= (isset($_POST["password2"])) ? trim($_POST["password2"]) : "";
	$firstname 				= (isset($_POST["firstname"])) ? ucfirst($_POST["firstname"]) : "";
	$middlename 			= (isset($_POST["middlename"])) ? ucfirst($_POST["middlename"]) : "";
	$surname 				= (isset($_POST["surname"])) ? ucfirst($_POST["surname"]) : "";
	$d						= (isset($_POST["d"])) ? $_POST["d"] : 0;
	$m						= (isset($_POST["m"])) ? $_POST["m"] : "m";
	$y						= (isset($_POST["y"])) ? $_POST["y"] : 0;
	$country_id			 	= (isset($_POST["country_id"])) ? $_POST["country_id"] : "";
	$gender			 		= (isset($_POST["gender"])) ? $_POST["gender"] : "Male";
	$state_name 			= (isset($_POST["state_name"])) ? ucfirst($_POST["state_name"]) : "";
	$city_name		 		= (isset($_POST["city_name"])) ? ucfirst($_POST["city_name"]) : "";
	$address 				= (isset($_POST["address"])) ? ucwords($_POST["address"]) : "";
	$postcode		 		= (isset($_POST["postcode"])) ? $_POST["postcode"] : "";
	$phone 					= (isset($_POST["phone"])) ? $_POST["phone"] : "";
	$about_me		 		= (isset($_POST["about_me"])) ? $_POST["about_me"] : "";
	$hobbies 				= (isset($_POST["hobbies"])) ? $_POST["hobbies"] : "";
	
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == $registration_button_text)
	{
		//Make sure mandatory fields have been entered
		$restricted_usernames = array("Allah", "fuck", "arse", "AdisaWasiuOlayemi", "Adisa_Wasiu_Olayemi", "WasiuAdisa", "Wasiu_Adisa", "AdisaWasiu", "Adisa_Wasiu", "Administrator", "Admin", "Mod", "Moderator", "Super_Moderator", "SuperModerator", "Super.Moderator", "OlayemiAdisa", "Olayemi.Adisa", "Olayemi_Adisa", "AdisaOlayemi", "Adisa.Olayemi", "Adisa_Olayemi", "aowasiu", "wasiu", "awasiu", "oawasiu", "o_a_wasiu", "o.a.wasiu", "a_o_wasiu", "a.o.wasiu", "a.wasiu", "a_wasiu", ".wasiu", "o.wasiu", "o_wasiu", "terrorist", "Islam");
		
		if(in_array($username, $restricted_usernames))
		{
			$errors[] = "Your Username is not allowed.";
		}
		elseif($username == "" || $username == "Username")
		{
			$errors[] = "You didn't provide a Username.";
		}
		else {
				$username_min_length = 5;
				$username_max_length = 11;
				if(strlen($username) < $username_min_length)
				{
					$errors[] = "Your Username must not be less than $username_min_length characters.";
				}
				elseif(strlen($username) > $username_max_length)
				{
						$errors[] = "Your Username must not be more than $username_max_length characters.";
				}
				elseif(preg_match('/\s/', $username))
				{
						$errors[] = 'Your Username must not contain spaces';
				}
				else {
						$username;
				}
		}
		
		//Check if the username is already registered or is in use.
		$query 	= 'SELECT username FROM gv_members WHERE username = "' . $username . '"';
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$number_of_rows = mysql_num_rows($result);
		if(mysql_num_rows($result) > 0)
		{
			$errors[] = 'The username <b>' . $username . '</b> is already registered by a member.' . '<br>Enter a unique username.';
		}
		mysql_free_result($result);

		if(!isset($post_email_address))
		{
			//This visitor managed to evade the previous conditions but never again.  
			header("Location: join_us.php");
		}
		
		if(empty($password1) || empty($password2))
		{
			$errors[] = "You didn't enter a password. Employ characters ONLY YOU can remember EASILY.";
		}
		elseif(strlen($password1) > 15 || strlen($password2) > 15)
		{
			$errors[] = "Your password is longer than 15 characters.";
		}
		else {
				if($password2 !== $password1)
				{
					$errors[] = 'Your passwords do not match.';
				}
				else {
						$salt = '$2a$12$G.tFo2T2W.IiO4hWj1d2KI$';
						$secure_password_hash = crypt($password1, $salt);
				}
		}
		
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
		}
		if(empty($middlename))
		{
			$middlename = "";
		}

		if($d > 31 || $d < 1)
		{
			$errors[] = "Your day of birth is invalid.";
		}
		else {
				$month = array("January","February","March","April","May","June","July","August","September","October","November","December");
				if(!in_array($m, $month))
				{
					$errors[] = "Your month of birth is invalid.";
				}
				else {
						$current_year = date('Y');
						$highest_year = $current_year - 18;
						$lowest_year = $current_year - 95;
						if($y < $lowest_year || $y > $highest_year) //Oldest_age/$lowest_year = 95, Youngest_age/$highest_year = 18
						{
							$errors[] = "Your year of birth is invalid.";
						}
				}
		}
		
		if(!preg_match("/(Male|Female)/", $gender))
		{
			$errors[] = "You didn't choose a gender.";
		}
		else {
				$gender = $_POST["gender"];
		}
		
		if($country_id == "")
		{
			$errors[] = "Country name isn't valid.";
		}
		
		if(empty($state_name) || empty($city_name))
		{
			$errors[] = 'What state and town you are in?';
		}
		
		if(empty($address))
		{
			$address = "No address";
		}
		
		if(empty($postcode))
		{
			$postcode = "No postcode";
		}
		
		if(!isset($phone) && !is_int($phone))
		{ 
			$errors[] = "Your phone number is not given";
		}

		if(empty($about_me))
		{
			$about_me = "I'm just me.";
		}

		if(empty($hobbies))
		{
			$hobbies = "I've no hobbies.";
		}
		
		//Ensure the given phone number is non-existent
		function checking_phone_number($data)
		{
			global $db;
			$query = 'SELECT phone FROM gv_member_profile WHERE phone = "' . $data . '"';
			$result = mysql_query($query, $db) or die(mysql_error($db));
			if(mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_assoc($result);
				extract($row);
				return $phone;
			}
		}
		
		$phone_found = checking_phone_number($phone);
		
		if($phone_found == $phone)
		{
			$errors[] = 'You must provide a diffrent phone number. <b>' . $phone . '</b> has been registered by a member.';
		}
	
		if(count($errors) < 1)
		{
			function get_continent_id($data)
			{
				global $db;
				$query = 'SELECT continent_id FROM geoipwhois_cc WHERE cc_id = ' . $data;
				$result = mysql_query($query, $db) or die(mysql_error($db));
				$row = mysql_fetch_assoc($result);
				$continent = extract($row);
				return $continent;
			}
			
			$the_assumed_continent = get_continent_id($country_id);
			
			//Get check_input function
			require_once('Server_Includes/scripts/common_scripts/check_input.php');
			
			//There's no error, proceed with entering data in the database
			$safe_username 			= check_input($username);
			$safe_email_address 	= check_input($post_email_address);
			$safe_firstname			= check_input($firstname);
			$safe_middlename 		= check_input($middlename);
			$safe_surname 			= check_input($surname);
			$safe_gender 			= check_input($gender);
			$safe_state_name 		= check_input($state_name);
			$safe_city_name 		= check_input($city_name);
			$safe_address 			= check_input($address);
			$safe_postcode 			= check_input($postcode);
			$safe_about_me 			= check_input($about_me);
			$safe_hobbies 			= check_input($hobbies);

			//Capitalise the first character or word in each word
			$correct_username 		= $safe_username;
			$correct_firstname 		= ucfirst($safe_firstname);
			$correct_middlename		= ucfirst($safe_middlename);
			$correct_surname		= ucfirst($safe_surname);
			$correct_gender			= ucfirst($safe_gender);
			$correct_state_name		= ucfirst($safe_state_name);
			$correct_city_name		= ucfirst($safe_city_name);
			$correct_address		= ucfirst($safe_address);
			$correct_phone			= $phone;//is_int($phone);
			$correct_about_me		= ucfirst($safe_about_me);
			$correct_hobbies		= ucwords($safe_hobbies);

			$date_of_birth = $d . ', ' . $m . ', ' . $y;

			//Insert the OKAY fields/values in the database
			$query = 'INSERT INTO gv_members
			(member_id, membership_date, deactivated, banned, suspended, last_login, privilege, username, email_address, password)
			VALUES
			(NULL, NOW(), 0, 0, 0, NOW(), 1, "' . mysql_real_escape_string($correct_username, $db) . '", "' . mysql_real_escape_string($safe_email_address, $db) . '", "' . mysql_real_escape_string($secure_password_hash, $db) . '")';
			mysql_query($query, $db) or die(mysql_error());
			
			$member_id = mysql_insert_id($db);
			
			$query = 'INSERT INTO gv_member_profile
			(profile_id, member_id, cc_id, continent_id, state_name, city_name, edit_date, firstname, middlename, surname, date_of_birth, gender, address, postcode, phone, about_me, hobbies)
			VALUES
			(NULL, ' . $member_id . ', ' . mysql_real_escape_string($country_id, $db) . ', ' . $the_assumed_continent . ', "' . mysql_real_escape_string($correct_state_name, $db) . '", "' . mysql_real_escape_string($correct_city_name, $db) . '", NOW(), "' . mysql_real_escape_string($correct_firstname, $db) . '", "' . mysql_real_escape_string($correct_middlename, $db) . '", "' . mysql_real_escape_string($correct_surname, $db) . '", "' . mysql_real_escape_string($date_of_birth, $db) . '", "' . mysql_real_escape_string($correct_gender, $db) . '", "' . mysql_real_escape_string($correct_address, $db) . '", "' . mysql_real_escape_string($safe_postcode, $db) . '", "' . mysql_real_escape_string($correct_phone, $db)  .  '", "' . mysql_real_escape_string($correct_about_me, $db) .  '", "' . mysql_real_escape_string($correct_hobbies, $db) . '")';
			mysql_query($query, $db) or die(mysql_error());
			
			//Set session variables
			$_SESSION["logged_in"] 	= 1;
			$_SESSION["username"] 	= $username;
			$_SESSION["country_id"]	= $country_id;
			$_SESSION["privilege"]	= 1;
			$_SESSION["member_id"]	= $member_id;
			
			$query = 'DELETE FROM gv_email_confirmation WHERE confirm_email_address = "' . $safe_email_address . '"';
			mysql_query($query, $db) or die(mysql_error());

			mysql_free_result();
			mysql_close();

			//Create a new session id while retaining the SESSION variables
			//session_regenerate_id();
			
			header("Location: new_profile_photo.php");
			exit;
		}
	}

	//Get country_list function
	require_once('Server_Includes/scripts/common_scripts/get_list_functions.php');
	
	//Check that the provided confirm_id and full_token are existing in the email confirmation table
	$query = 'SELECT confirm_email_address FROM gv_email_confirmation WHERE full_token = "' . $full_token . '" AND confirm_id = ' . $confirm_id;
	$result = mysql_query($query, $db) or die(mysql_error($db));
	$number_of_rows = mysql_num_rows($result);
	$row = mysql_fetch_array($result);
	if($number_of_rows < 1)
	{
		//This may be an illegal attempt to register, so send this person to the email submission page
		mysql_free_result($result);
		mysql_close();
		header("Location: index.php");
		exit();
	}

	//This email address will be displayed on the form and also as a hidden input for processing later
	$email_address = $row['confirm_email_address'];
	
	//$hobbies_list	=	array('Archery', 'Dancing', 'Diving', 'Exercising', 'Flying', 'Golfing', 'Hunting', 'Internet', 'Reading', 'Sailing', 'Singing', 'Sky-diving', 'Surfing', 'Swimming', 'Travelling', 'Being a jerk', 'Being a meanie', 'I\'m boring', 'Other than listed');

	//Set extra title
	$extra_title = "Registration form";

	//Set email address field default string
	$business_casual = "Set Biz-casual template";

	//Set page template name
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
                    <h2 class="intro-text text-center">Registration form</h2>
                    <hr>
                    <h4 class="intro-text text-center">Fields marked, *, are mandatory</h4>
                    <hr>
					<?php

		if(count($errors) > 0)
		{
			echo '<p class="alert alert-danger">Your registration could not be submitted:</p>' . "\n";
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
								<input type="text" class="form-control" name="username" id="username" value="<?php	echo $username;	?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Email Address *</label>
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
										echo $post_email_address;
								}; ?>">
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-lg-6">
                                <label>Password *</label>
								<input type="password" class="form-control" name="password1" maxlength="15" class="password" value="<?php	echo $password1;	?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Re-type Password *</label>
								<input type="password" class="form-control" name="password2" maxlength="15" class="password" value="<?php	echo $password2;	?>">
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
                            <div class="form-group col-lg-2">
                                <label>Date of Birth *</label>
								<select class="form-control" name="d" id="d">
									<option value="0">Date</option>
									   <?php
											for ($x=1; $x<32; $x++)
											{
												echo '<option ';
												if(isset($d) && $d == "$x")
												{	echo 'selected="selected"';	}
												echo 'value="' . $x . '">' . $x . '</option>';
											}
										?>
								</select>
								<select class="form-control" name="m" id="m">
									<option value="m">Month</option>
									<option <?php	if(isset($m) && $m == "January"){ echo 'selected="selected" ';} ?>value="January">January</option>
									<option <?php	if(isset($m) && $m == "February"){ echo 'selected="selected" ';} ?>value="February">February</option>
									<option <?php	if(isset($m) && $m == "March"){ echo 'selected="selected" ';} ?>value="March">March</option>
									<option <?php	if(isset($m) && $m == "April"){ echo 'selected="selected" ';} ?>value="April">April</option>
									<option <?php	if(isset($m) && $m == "May"){ echo 'selected="selected" ';} ?>value="May">May</option>
									<option <?php	if(isset($m) && $m == "June"){ echo 'selected="selected" ';} ?>value="June">June</option>
									<option <?php	if(isset($m) && $m == "July"){ echo 'selected="selected" ';} ?>value="July">July</option>
									<option <?php	if(isset($m) && $m == "August"){ echo 'selected="selected" ';} ?>value="August">August</option>
									<option <?php	if(isset($m) && $m == "September"){ echo 'selected="selected" ';} ?>value="September">September</option>
									<option <?php	if(isset($m) && $m == "October"){ echo 'selected="selected" ';} ?>value="October">October</option>
									<option <?php	if(isset($m) && $m == "November"){ echo 'selected="selected" ';} ?>value="November">November</option>
									<option <?php	if(isset($m) && $m == "December"){ echo 'selected="selected" ';} ?>value="December">December</option>
								</select> 
								<select class="form-control" name="y" id="y">
									<option value="0">Year</option>
								 <?php
									$current_year = date('Y');
									$highest_year = $current_year - 18;
									$lowest_year = $current_year - 95;
									
									for ($y=$lowest_year; $y<$highest_year; $y++)
									{
										echo '<option ';
									/*		if(isset($y) && $y == "$y")
											{
											  echo ' selected="selected" ' ;
											}
									*/	echo 'value="' .$y . '">' . $y . '</option>';
									}

								 ?>
								</select>
							</div>
                            <div class="form-group col-lg-2">
                                <label>Gender *</label>
								<select class="form-control" name="gender" id="gender">
									<option <?php	if(isset($gender) && $gender == "Female"){ echo 'selected="selected"';} ?> value="Female">Female</option>
									<option <?php	if(isset($gender) && $gender == "Male"){ echo 'selected="selected"';} ?> value="Male">Male</option>
								 </select>
                            </div>
                            <div class="form-group col-lg-2">
                                <label>Country *</label>
								<?php echo country_list(); ?>
							</div>
                            <div class="form-group col-lg-3">
                                <label>State *</label>
								<input type="text" class="form-control" name="state_name" id="state_name" value="<?php	echo $state_name;	?>">
                            </div>
                            <div class="form-group col-lg-3">
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
								<input type="submit" class="btn btn-default" name="submit" value="<?php echo $registration_button_text; ?>">
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
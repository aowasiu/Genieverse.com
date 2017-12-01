<?php

	$mobile_view_menu_colour = '#000';
	//Get custom error function script 
	require_once('../Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	//Filter incoming values
	$name				= (isset($_POST["name"])) ? ucwords($_POST["name"]) : "";
	$gender				= (isset($_POST["gender"])) ? $_POST["gender"] : "male";
	$age				= (isset($_POST["age"])) ? $_POST["age"] : 99;
	$city				= (isset($_POST["city"])) ? ucwords($_POST["city"]) : "";
	$state				= (isset($_POST["state"])) ? ucwords($_POST["state"]) : "";
	$country			= (isset($_POST["country_id"])) ? $_POST["country_id"] : "";
	$phone				= (isset($_POST["phone"])) ? $_POST["phone"] : "";
	$email				= (isset($_POST["email"])) ? $_POST["email"] : "";
	$about_me			= (isset($_POST["about_me"])) ? ucfirst($_POST["about_me"]) : "";
	$submit_button 		=	"Submit application";
	$maximum_photo_size = 204800;
	$upload_file = (isset($_POST["upload_file"])) ? $_POST["upload_file"] : "";
	$maximum_age = 100;
	$minimum_age = 18;

	//Set error as array
	$errors = array();
		
	//Connect to the database
	require_once('../Server_Includes/visitordbaccess.php');
	
	//Define variable and set the default value to empty	
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == $submit_button)
	{
/*		$upload_file;
		$name;
		$age;
		$city;
		$state;
		$country;
		$phone;
		$email;
		$about_me;

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
*/				require_once('../Server_Includes/image_directory_core.php');
				
				//Path to images directory
				$image_directory = $image_directory_core . "/images/service_images/hearts/featured";
				
				//Ensure the uploaded photo transfer was successful
				if($_FILES['upload_file']['error'] != UPLOAD_ERR_OK)
				{
					switch($_FILES['upload_file']['error'])
					{
						case UPLOAD_ERR_INI_SIZE:
							$errors[] = 'The photo is larger than 200kb. Resize it or upload a smaller photo.';
							break;
						case UPLOAD_ERR_FORM_SIZE:
							$errors[] = 'The photo is larger than 200kb. Resize it or upload a smaller photo.';
							break;
						case UPLOAD_ERR_PARTIAL:
							$errors[] = 'The photo was partially uploaded. Please upload it again.';
							break;
						case UPLOAD_ERR_NO_FILE:
							$errors[] = "You didn't upload any photo. Choose a photo to upload.";
							break;
						case UPLOAD_ERR_NO_TMP_DIR:
							$errors[] = "There's a problem uploading your photo. Please try later while Genieverse tries to  fix this problem.";
							break;
						case UPLOAD_ERR_NO_TMP_DIR:
							$errors[] = "The uploaded photo is in an unacceptable format.";
							break;
					}
				}
				
				//TEST FILE TOO LARGE
				if($_FILES['upload_file']['size'] > $maximum_photo_size)
				{
					$errors[] = "The photo is too large. It shouldn't be more than $maximum_photo_size bytes(1000 bytes = 1 kilobyte).";
				}

				if($name == "")
				{
					$errors[] = "You didn't enter a name.";
				}

				if(isset($gender))
				{
					$gender = 'female';
				}
				else {
						$gender = 'male';
				}

				if(!empty($city))
				{
					$max_length_city = 15;
					if(strlen($city) > $max_length_city)
					{
						$errors[] = "The name of the city must not be more than 15 characters";
					}
				}
				
				if(!empty($state))
				{
					$max_length_state = 14;
					if(strlen($state) > $max_length_state)
					{
						$errors[] = "The name of the state must not be more than 14 characters";
					}
				}

				if(!isset($age))
				{
					$errors[] = "You didn't enter how old you are.";
				}
				elseif($age < $minimum_age)
				{
					$errors[] = "Your age must be between 18 and 100";
				}
				elseif($age > $maximum_age)
				{
					$errors[] = "Your age must be between 18 and 100";
				}
				else {
						$age = $_POST["age"];
				}

				if(!empty($email))
				{
					if(!filter_var($email, FILTER_VALIDATE_EMAIL))
					{
						$errors[] = "Your email address is not valid.";
					}
				}

//				if(!is_int($country))
				if($country == "")
				{
					$errors[] = "You didn't select your country";
				}

				if($phone !== "")
				{
					$maximum_phone_length = 15;
					if(strlen($phone) > $maximum_phone_length)
					{
						$errors[] = "Your phone number should be less than 15 characters.";
					}
				}

				if(count($errors) < 1)
				{
					require_once('../Server_Includes/scripts/common_scripts/check_input.php');
					
					$clean_name = check_input($name);
					$clean_gender = check_input($gender);
					$clean_age = $age;
					$clean_city = check_input($city);
					$clean_state = check_input($state);
					$clean_phone = check_input($phone);
					$clean_email = check_input($email);
					$clean_about_me = check_input($about_me);

					$query = 'INSERT INTO gv_hearts_featured_profile
								(featured_profile_id, cc_id, featured_name, featured_gender, featured_age, featured_city, featured_state, featured_phone, featured_email, featured_about_me, featured_profile_updated_on)
								VALUES
								(NULL, ' . mysql_real_escape_string($country) . ', "' . mysql_real_escape_string(filter_var($clean_name, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '", "' . mysql_real_escape_string(filter_var($clean_gender, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '", ' . mysql_real_escape_string($clean_age, $db) . ', "' . mysql_real_escape_string(filter_var($clean_city, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '", "' .mysql_real_escape_string(filter_var($clean_state, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '", "' .mysql_real_escape_string(filter_var($clean_phone, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '", "' .mysql_real_escape_string(filter_var($clean_email, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '", "' .mysql_real_escape_string(filter_var($clean_about_me, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '", NOW())';
					mysql_query($query, $db) or die(mysql_error($db));
					
					$featured_id = mysql_insert_id($db);

					//Acquire info about the image being uploaded
					list($width, $height, $type, $attr) = getimagesize($_FILES['upload_file']['tmp_name']);
					
					//Ensure the uploaded image is really in a supported format
					switch($type)
					{
						case IMAGETYPE_GIF:
							$image = imagecreatefromgif($_FILES['upload_file']['tmp_name']) or die('The photo is not a supported file type.');
							$ext = '.gif';
							break;
						case IMAGETYPE_JPEG:
							$image = imagecreatefromjpeg($_FILES['upload_file']['tmp_name']) or die('The photo is not a supported file type.');
							$ext = '.jpg';
							break;
						case IMAGETYPE_PNG:
							$image = imagecreatefrompng($_FILES['upload_file']['tmp_name']) or die('The photo is not a supported file type.');
							$ext = '.png';
							break;
						default:
							die('The photo is not a supported file type.');
					}

							
					//Get check_input function
					require_once('../Server_Includes/scripts/common_scripts/check_input.php');
					
					$safe_caption = check_input($caption);
					
					$temporary_photo_name = 'No name';
					
					//Insert photo information into the featured profile photo table
					$queryPhotoInsert = 'INSERT INTO gv_hearts_featured_profile_image
								(featured_image_id, featured_profile_id, featured_image_created_on, featured_image_filename)
								VALUES
								(NULL, ' . mysql_real_escape_string($featured_id, $db) . ', NOW(), "' . mysql_real_escape_string($temporary_photo_name, $db) . '")';
					mysql_query($queryPhotoInsert, $db) or die(mysql_error($db));
					
					//Get the incrementing id of the above table
					$last_id = mysql_insert_id();
					$file_md5_hash_name = md5($last_id);

					//Create a name for the image by adding an extension to the id to make the name unique
					$image_name = substr("$file_md5_hash_name", 0) . $ext;
					
					//Now update the above table with the new and unique image name
					$queryPhotoUpdate = 'UPDATE gv_hearts_featured_profile_image SET featured_image_filename="' . $image_name . '" WHERE featured_image_id = ' . $last_id;
					mysql_query($queryPhotoUpdate, $db) or die(mysql_error($db));
					
					//Now proceed to saving the image to its final destination
					switch($type)
					{
						case IMAGETYPE_GIF:
							imagegif($image, $image_directory . '/' . $image_name);
							break;
						case IMAGETYPE_JPEG:
							imagejpeg($image, $image_directory . '/' . $image_name);
							break;
						case IMAGETYPE_PNG:
							imagepng($image, $image_directory . '/' . $image_name);
							break;
					}
					
					imagedestroy($image);
	
					$_SESSION["errand_title"] = "Feature Profile form has been submitted.";
					$_SESSION["errand"] = 'Thanks for wanting to be featured on Genieverse Feature Profiles.<br/>Only two profiles will be featured each time.' . '<br/>';
					$_SESSION["errand"] .= 'To get more people to see your profile,' . '<br>' . 'To get your profile seen for a long time,' . '<br>' . ' Subscribe for <a href="member/my_profile.php">Genieverse Sweethearts</a>.';
					}//End of if(count($errors) < 1)
/*			}//End of if($response.success==false)
*/	}//End of if($_SERVER["REQUEST_METHOD"] == "POST")

	//Get country_list function
	require_once('../Server_Includes/scripts/common_scripts/get_list_functions.php');

	//Get Hearts meta details and footer script
	require_once('../Server_Includes/scripts/hearts_scripts/hearts_meta.php');

	//Set extra title
	$extra_title = 'Feature Profile submission | Genieverse Hearts - ' . $hearts_title_description;

	//Set extra script
	$extra_script = "\n" . '	<script src="https://www.google.com/recaptcha/api.js"></script>' . "\n";
	
	//Set meta details
	$meta_keyword = $hearts_meta_keywords;
	$meta_description = $hearts_meta_description;

	//Set link active in navigation
	$featured_submit = "set";

	//Get page head element properties
	require_once('../Server_Includes/scripts/hearts_scripts/hearts_outer_page_head.php'); ?>
	<body>
	<?php require_once('../Server_Includes/scripts/hearts_scripts/hearts_outer_page_header.php'); ?>
	
    <!-- Page Content -->
    <div class="container">

		<div class="row">
			<div>	
				<div class="row">
					<div class="col-md-12">
						<h3 class="alert alert-info"></h3>
					</div>
				</div>
			</div>
		</div>
	
		<div class="row">
			<div class="">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<h3>Feature Profile Application</h3>
					</div>
				</div>
			</div>
		</div>
	
<?php
	if(isset($_SESSION["errand_title"]) || isset($_SESSION["errand"]))
	{
?>
		<div class="row col-md-8 col-md-offset-2">
            <div class="col-lg-12 text-center">
				<div class="alert alert-success">
					<h3><?php echo $_SESSION["errand_title"]; ?></h3>
					<p><?php echo $_SESSION["errand"]; ?></p>
				</div>
			</div>
		</div>
<?php
	}

	if(count($errors) > 0)
	{
?>
		<div class="row col-md-8 col-md-offset-2">
            <div class="col-md-12 alert alert-danger">
				<div>
				<p>Your form could not be submitted due to the following issue<?php if(count($errors) < 2){ echo ':'; } else{ echo 's:'; } ?></p>
				<br>
				<?php
					echo '<ul>';
					foreach($errors as $error)
					{
						echo "<li>$error</li>";
					}
					echo "</ul>" . "\n";
				?>
				</div>
			</div>
		</div>
<?php
	}
?>
		///////////////////////
		
    <!-- /.container -->

        <hr>

		<?php require_once('../Server_Includes/scripts/hearts_scripts/hearts_footer.php'); ?>
		
</body>

</html><?php 
	mysql_close();
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
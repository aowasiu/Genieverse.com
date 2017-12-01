<?php

	//Get custom error function script
	require_once('../../Server_Includes/scripts/common_scripts/feature_error_message.php');
			
	if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== 1)
	{
		$_SESSION["errand_title"] = "Log in issue";
		$_SESSION["errand"] = "You must log in first to view member area.";
		//Take this visitor to the log in page because he's not logged in
		header("Location: ../log_in.php?location=" . urlencode($_SERVER["REQUEST_URI"]));
		exit;
	}
	
	$get_id = (isset($_GET["id"]))	?	$_GET["id"] : 0;
	$post_id = (isset($_POST["id"]))	?	$_POST["id"] : $get_id;
	
	if(!isset($post_id) || $post_id == 0)
	{
		$_SESSION["errand_title"] = "New post issue";
		$_SESSION["errand"] = "The post you requested does not exist.";
		//Take this visitor to the log in page because he's not logged in
		header("Location: dashboard.php");
		exit;
	}
	
	//Connect to the database
	require_once('../../Server_Includes/visitordbaccess.php');

	//Get function to check if the provided category exists
	require_once('../../Server_Includes/scripts/voice_scripts/functions_for_voice.php');

	//Get function to check if post requested is this member's post
	require_once('../../Server_Includes/scripts/voice_scripts/functions_for_voice.php');
	
	$the_members_id = is_post_id_members_own($post_id);
	if($the_members_id !== $_SESSION["member_id"])
	{
		$_SESSION["errand"] = "You don't have any post as such.";
		header("Location: dashboard.php");
		exit;
	}
		
	//Get function that the name of a category
	require_once('../../Server_Includes/scripts/voice_scripts/functions_get_name_for_voice.php');
	
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["edit"] == "Edit this post"){	/*Do nought*/ }
	
	//Set error variable
	$errors = array();

	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["update"] == "Save editing")
	{
		//Set variables and default values
		//$post_id = (isset($_POST["post_id"])) ? $_POST["post_id"] : 0;
		$category_id = (isset($_POST["category_id"])) ? $_POST["category_id"] : 0;
		$title = (isset($_POST["title"])) ? $_POST["title"] : "";
		$description = (isset($_POST["description"])) ? $_POST["description"] : "";
		$youtube =	(isset($_POST["youtube"]))	?	$_POST["youtube"] : "";
	//	$country_id = (isset($_POST["country_id"]))	?	$_POST["country_id"] : 72;
		$state = (isset($_POST["state"])) ? $_POST["state"] : "";
		$city = (isset($_POST["city"])) ? $_POST["city"] : "";
		$contact_name = (isset($_POST["contact_name"])) ? $_POST["contact_name"] : "";
		$contact_phone = (isset($_POST["contact_phone"])) ? $_POST["contact_phone"] : "";
		$contact_email = (isset($_POST["contact_email"])) ? $_POST["contact_email"] : "";
		$contact_website = (isset($_POST["contact_website"])) ? $_POST["contact_website"] : "";
		$use_info = (isset($_POST["use_info"]))	?	$_POST["use_info"] : 0;
		
		//Make sure mandatory fields have been entered
		if(!isset($category_id) || ($category_id == 0))
		{
			$_SESSION["errand_title"] = "Post editing issue";
			$_SESSION["errand"] = "The category you're relating to the post does not exist.";
			header("Location: dashboard.php");
			exit;
		}

		$category_name = check_if_category_name_exists($category_id);

		if($category_name == "Dude you're being hacked")
		{
			$_SESSION["errand_title"] = "Post editing issue";
			$_SESSION["errand"] = "The category you're relating to the post does not exist.";
			//Take this visitor to the new category page
			header("Location: dashboard.php");
			exit;
		}
	
		if(empty($title))
		{
			$errors[] = "Your post doesn't have a title.";
		}
		else {
				$max_length_title = 100;
				$min_length_title = 10;
				if(strlen($title) > $max_length_title || strlen($title) < $min_length_title)
				{
					$errors[] = "Your title should be between 10 and 100 characters in length.";
				}
		}
		
		if(!empty($description))
		{
			$max_length_description = 3000;
			if(strlen($description) > $max_length_description)
			{
				$errors[] = "Your detail should not be more than 3000 characters in length.";
			}
		}

		if(empty($state))
		{
			$errors[] = "You didn't fill in the name of your state or region.";
		}		
		
		if(!empty($contact_website))
		{
			if(!filter_var($contact_website, FILTER_VALIDATE_URL))
			{
				$errors[] = "Your website address is not valid.";
			}
		}
		
		if(isset($use_info) && $use_info == 0)
		{	
			if(!empty($contact_name))
			{
				if(!preg_match('/\s/', $contact_name))
				{
					$errors[] = "Your contact name is invalid.";
				}
			}
			
			if(!empty($contact_email))
			{
				if(!filter_var($contact_email, FILTER_VALIDATE_EMAIL))
				{
					$errors[] = "Your contact email is invalid.";
				}
			}
			
			if(!empty($contact_phone))
			{
				$max_length_phone = 15;
				if(strlen($contact_phone) > $max_length_phone)
				{
					$errors[] = "Your phone number should not be longer than 15 digits.";
				}
			}
		}
		
		if(isset($use_info) && $use_info == 1)
		{
			function get_name($data)
			{
				global $db;
				$query = 'SELECT firstname, surname FROM gv_member_profile WHERE member_id = ' . $data;
				$result = mysql_query($query, $db) or die (mysql_error($db));
				$row = mysql_fetch_array($result);
				extract($row);
				$fullname = "'" . $row["firstname"] . ' ' . $row["surname"] . "'";
				return $fullname;
			}

			function get_phone($data)
			{
				global $db;
				$query = 'SELECT phone FROM gv_member_profile WHERE member_id = ' . $data;
				$result = mysql_query($query, $db) or die (mysql_error($db));
				$row = mysql_fetch_array($result);
				extract($row);
				return $phone;
			}
			
			function get_email($data)
			{
				global $db;
				$query = 'SELECT email_address FROM gv_members WHERE member_id = ' . $data;
				$result = mysql_query($query, $db) or die (mysql_error($db));
				$row = mysql_fetch_array($result);
				extract($row);
				return $email_address;
			}
			
			$profile_name = get_name($_SESSION["member_id"]);
			$profile_email = get_email($_SESSION["member_id"]);
			$profile_phone = get_phone($_SESSION["member_id"]);
		}//End of if($use_info == 1)
		else {
				$profile_name = $contact_name;
				$profile_phone = $contact_phone;
				$profile_email = $contact_email;
		}

		if(count($errors) < 1)
		{
			function country_id($data)
			{
				global $db;
				$query = 'SELECT cc_id FROM gv_member_profile WHERE member_id = ' . $data;
				$result = mysql_query($query, $db) or die (mysql_error($db));
				$row = mysql_fetch_array($result);
				extract($row);
				return $cc_id;
			}
			
			function sanitized_string($string)
			{
				return "'" . mysql_real_escape_string($string) . "'"; 
			}
	
			function check_input($data)
			{
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}
			
			$safe_title					= check_input($title);
			$safe_description	 		= check_input($description);
			$safe_state 				= check_input($state);
			$safe_city 					= check_input($city);
			$safe_youtube				= $youtube;
			
			if($use_info == 1)
			{
				$safe_contact_name		= $profile_name;
				$safe_contact_phone		= $profile_phone;
				$safe_contact_email		= $profile_email;
			}
			else {
					$safe_contact_name 		= check_input($profile_name);
					$safe_contact_phone		= check_input($profile_phone);
					$safe_contact_email		= check_input($profile_email);
			}
			
			//Capitalize the first character or word in each word
			$correct_state				= ucwords($safe_state);
			$correct_city 				= ucwords($safe_city);
			$correct_contact_name		= ucwords($safe_contact_name);
			
			//Insert the OKAY fields/values in the database
			$query = 'UPDATE gv_voice_post SET
					  voice_post_settled_on=NOW(),
					  voice_post_edited_on=NOW(),
					  voice_post_assessment=0,
					  voice_post_assessed_by="None",
					  voice_category_id=' . mysql_real_escape_string($category_id, $db) . ',
					  cc_id=' . mysql_real_escape_string(country_id($_SESSION["member_id"]), $db) . ', ' . 'voice_post_youtube="' . mysql_real_escape_string(filter_var($safe_youtube, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '",
					  voice_post_title="' . mysql_real_escape_string(filter_var($safe_title, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '",
					  voice_post_body="' . mysql_real_escape_string($safe_description, $db) . '", voice_post_state="' . mysql_real_escape_string(filter_var($correct_state, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '",
					  voice_post_city="' . mysql_real_escape_string(filter_var($correct_city, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '", voice_post_contact_name="' . mysql_real_escape_string($correct_contact_name, $db) . '", voice_post_contact_phone="' . mysql_real_escape_string($safe_contact_phone, $db) . '", voice_post_contact_email="' . mysql_real_escape_string($safe_contact_email, $db) . '", voice_post_contact_website="' . mysql_real_escape_string($contact_website, $db) . '"
					  WHERE 
					  voice_post_id = ' . mysql_real_escape_string($post_id, $db) . '
					  AND member_id = ' . $_SESSION["member_id"];
			mysql_query($query, $db) or die(mysql_error());
			mysql_close();
			
			$_SESSION["errand_title"] = "Post editing success";
			$_SESSION["errand"] = "The post has been updated successfully.";
			header("Location: my_post.php?id=$post_id");
			exit;
		}//End of if(count($errors) < 1)
	}//End of if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["update"] == "Update post")

	$query = 'SELECT voice_category_id, cc_id, voice_post_youtube, voice_post_title, voice_post_body, voice_post_state, voice_post_city, voice_post_contact_name, voice_post_contact_phone, voice_post_contact_email,  voice_post_contact_website FROM gv_voice_post WHERE  (member_id = ' . $_SESSION["member_id"] . ' AND voice_post_id = ' . mysql_real_escape_string($post_id, $db) . ') AND voice_post_block = 0';
	$result = mysql_query($query, $db) or die (mysql_error($db));
	
	if($result = mysql_query($query))
	{
		if(mysql_num_rows($result) < 1)
		{
			$_SESSION["errand_title"] = "Post editing issue";
			$_SESSION["errand"] = "The post you're requesting does not exist.";
			header("Location: dashboard.php");
			exit;
		}
		
		if(mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_assoc($result);
			extract($row);	
			
			$category_id =  $row["voice_category_id"];
			$title = $row["voice_post_title"];
			$description =  $row["voice_post_body"];
			$country_id =  $row["cc_id"];
			$youtube =  $row["voice_post_youtube"];
			$state =  $row["voice_post_state"];
			$city =  $row["voice_post_city"];
			$contact_name =  $row["voice_post_contact_name"];
			$contact_phone =  $row["voice_post_contact_phone"];
			$contact_email =  $row["voice_post_contact_email"];
			$contact_website =  $row["voice_post_contact_website"];
		}//End of if(mysql_num_rows($result) > 0)
	}//End of if($result = mysql_query($query))

	//Get Mall meta detail
	require_once('../../Server_Includes/scripts/voice_scripts/voice_meta.php');
	
	//Get Genieverse Mall footer function
	require_once('../../Server_Includes/scripts/voice_scripts/voice_member_footer.php');
	
	$extra_title = "Post editing | Genieverse Voice - Tell the world what's happening. ";
	$shop_item = "set";
	$meta_keyword = $voice_meta_keywords;
	$meta_description = $voice_meta_description;
	
	$ckeditor = "set";
	
	require_once('../../Server_Includes/scripts/common_scripts/common_member_page_head2.php'); ?><body>
<?php require_once('../../Server_Includes/scripts/voice_scripts/voice_member_page_header.php'); ?>
    <!-- Page Content -->
    <div class="container">

		<div class="row">
			<div><!--class="col-md-9"-->	
				<div class="row">
					<div class="col-md-12">
						<h3 class="pull-right"><a class="btn btn-info" href="my_post.php?id=<?php echo $post_id; ?>">Cancel editing</a></h3>
						<h3 class="alert alert-info">Post editing</h3>
					</div>
				</div>
			</div>
		</div>
	
<?php
	if(isset($_SESSION["errand_title"]) || isset($_SESSION["errand"]))
	{
?>
		<div class="row">
            <div class="col-lg-12 text-center">
				<div class="alert alert-danger">
					<p><?php if(isset($_SESSION["errand_title"]) || isset($_SESSION["errand"])){ ?><?php echo $_SESSION["errand"]; ?><?php } ?></p>
				</div>
			</div>
		</div>
<?php
	}

	if(count($errors) > 0)
	{
?>
		<div class="row">
            <div class="alert alert-danger col-lg-8 text-center">
				<div>
				<p>Your post could not be updated due to the following issue<?php if(count($errors) < 2){ echo ':'; } else{ echo 's:'; } ?></p>
				<br>
				<?php
					echo '<ul>';
					foreach($errors as $error)
					{
						echo "<li>$error</li>";
					}
					echo "</ul>" . "\n" . '<hr>';
				?>
				</div>
			</div>
		</div>
<?php
	}
?>
		<div class="row text-left">
		
		<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group col-lg-12">
				<div class="row">
					<div class="col-lg-8 col-lg-offset-2 text-center">
						<h4 class="text-left">Category</h4>
						<h3 class="pull-right form-control"><?php echo category_name($category_id); ?></h3>
						<input type="hidden" class="form-control" name="category_id" value="<?php echo $category_id; ?>">
					</div>
				</div>
			</div>
			
			<div class="form-group col-lg-12">
				<div class="row">
					<div class=" col-md-8 col-md-offset-2 text-center">
						<span class="pull-right"></span>
						<h4 class="text-left">Title</h4>
						<input type="text" class="form-control" name="title" value="<?php echo $title; ?>">
					</div>
				</div>
			</div>
			
<?php /*			<div class="form-group col-lg-12">
				<div class="row">
					<div class=" col-md-8 col-md-offset-2 text-center">
						<span class="pull-right"><a class="btn btn-info" href="new_category.php">Change category</a></span>
						<h4 class="text-left">Category</h4>
						<p class="form-control"  class="alert alert-info"><?php echo category_name($category_id); ?></p>
						<input type="hidden" name="category_id" class="form-control"  value="<?php echo $category_id; ?>">
						<input type="hidden" name="id" class="form-control"  value="<?php echo $id; ?>">
					</div>
				</div>
			</div>
*/ ?>			
			<div class="form-group col-lg-12">
				<div class="row">
					<div class=" col-md-8 col-md-offset-2 text-center">
						<span class="pull-right"></span>
						<h4 class="text-left">Detail</h4>
						<textarea name="description" id="description"  class="form-control" ><?php echo html_entity_decode($description); ?></textarea>
						<script> CKEDITOR.replace( 'description' ); </script>
					</div>
				</div>
			</div>

			<div class="form-group col-lg-12">
				<div class="row">
					<div class=" col-md-8 col-md-offset-2 text-center">
						<span class="pull-right"></span>
						<h4 class="text-left">YouTube link</h4>
						<p>Example:
							<br>For: http://www.youtube.com/embed/W-Q7RMpINVo
							<br> Enter only: W-Q7RMpINVo<br>
							<input type="text" name="youtube" class="form-control" value="<?php echo $youtube; ?>">
					</div>
				</div>
			</div>
			
			<div class="form-group col-lg-12">
				<div class="row">
					<div class=" col-md-8 col-md-offset-2 text-center">
						<span class="pull-right"></span>
						<h4 class="text-left">State</h4>
						<input type="text" name="state" class="form-control" value="<?php echo $state; ?>">
					</div>
				</div>
			</div>
			
			<div class="form-group col-lg-12">
				<div class="row">
					<div class=" col-md-8 col-md-offset-2 text-center">
						<span class="pull-right"></span>
						<h4 class="text-left">City</h4>
						<input type="text" name="city" class="form-control" value="<?php echo $city; ?>">
					</div>
				</div>
			</div>
			
			<div class="form-group col-lg-12">
				<div class="row">
					<div class=" col-md-8 col-md-offset-2 text-center">
						<span class="pull-right"></span>
						<h4 class="text-left">Use Profile info for Contact</h4>
						<select name="use_info" class="form-control" >
							<option value="0">Do not use info</option>
							<option <?php	if(isset($use_info) && $use_info == "1"){ echo 'selected="selected" ';} ?>value="1">Use info</option>
							</select>
					</div>
				</div>
			</div>
			
			<div class="form-group col-lg-12">
				<div class="row">
					<div class=" col-md-8 col-md-offset-2 text-center">
						<span class="pull-right"></span>
						<h4 class="text-left">Contact name</h4>
						<input type="text" name="contact_name" class="form-control" value="<?php echo $contact_name; ?>"></p>
					</div>
				</div>
			</div>

			<div class="form-group col-lg-12">
				<div class="row">
					<div class=" col-md-8 col-md-offset-2 text-center">
						<span class="pull-right"></span>
						<h4 class="text-left">Contact Phone</h4>
						<input type="text" name="contact_phone" class="form-control" value="<?php echo $contact_phone; ?>">
					</div>
				</div>
			</div>
			
			<div class="form-group col-lg-12">
				<div class="row">
					<div class=" col-md-8 col-md-offset-2 text-center">
						<span class="pull-right"></span>
						<h4 class="text-left">Contact Email</h4>
						<input type="text" name="contact_email" class="form-control" value="<?php echo $contact_email; ?>">
					</div>
				</div>
			</div>
			
			<div class="form-group col-lg-12">
				<div class="row">
					<div class=" col-md-8 col-md-offset-2 text-center">
						<span class="pull-right"></span>
						<h4 class="text-left">Web Address</h4>
						<p>Example: http://www.the_web_address.com<br>
						<input type="text" name="contact_website" class="form-control" value="<?php echo $contact_website; ?>">
					</div>
				</div>
			</div>
			
			<div class="form-group col-lg-12">
				<div class="row text-center">
					<div class=" col-md-8 col-md-offset-2 text-center">
						<span class="pull-right"></span>
						<h4 class="text-left"></h4>
						<input type="hidden" name="id" value="<?php echo $post_id; ?>" />
						<input type="hidden" name="edit" value="edit this post" />
						<input class="btn btn-info" type="submit" name="update" value="Save editing">
					</div>
				</div>
			</div>

		</form>
		
		<hr>
		

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

<?php require_once('../../Server_Includes/scripts/common_scripts/common_member_page_before_body_end2.php'); ?>

</body>

</html><?php 
	mysql_close();
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
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
	require_once('../../Server_Includes/scripts/mall_scripts/functions_for_mall.php');

	//Get function to check if post requested is this member's post
	require_once('../../Server_Includes/scripts/mall_scripts/functions_for_mall.php');
	
	$the_members_id = is_post_id_members_own($post_id);
	if($the_members_id !== $_SESSION["member_id"])
	{
		$_SESSION["errand"] = "You don't have any post as such.";
		header("Location: dashboard.php");
		exit;
	}
		
	//Get function that the name of a category
	require_once('../../Server_Includes/scripts/mall_scripts/functions_get_name_for_mall.php');
	
	//Get dynamic functions
	require_once('../../Server_Includes/scripts/mall_scripts/get_dynamic_form_fields.php');

	$hidden_condition = array(13, 17, 20, 22, 23, 24);
	$common_condition = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 11, 12, 18, 19, 21);
	$unique_condition = array(10, 14, 15, 16);
		
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["edit"] == "Edit this post"){	/*Do nought*/ }
	
	//Set error variable
	$errors = array();

	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["update"] == "Save editing")
	{
		//Set variables and default values
		$price = (isset($_POST["price"])) ? $_POST["price"] : "";
		//$post_id = (isset($_POST["post_id"])) ? $_POST["post_id"] : 0;
		$category_id = (isset($_POST["category_id"])) ? $_POST["category_id"] : 0;
		$title = (isset($_POST["title"])) ? $_POST["title"] : "";
		$description = (isset($_POST["description"])) ? $_POST["description"] : "";
		$barter = (isset($_POST["barter"])) ? $_POST["barter"] : "";
		$age = (isset($_POST["age"])) ? $_POST["age"] : "";
		$condition = (isset($_POST["condition"])) ? $_POST["condition"] : "";
		$youtube =	(isset($_POST["youtube"]))	?	$_POST["youtube"] : "";
	//	$country_id = (isset($_POST["country_id"]))	?	$_POST["country_id"] : 72;
		$state = (isset($_POST["state"])) ? $_POST["state"] : "";
		$city = (isset($_POST["city"])) ? $_POST["city"] : "";
		$contact_name = (isset($_POST["contact_name"])) ? $_POST["contact_name"] : "";
		$contact_address = (isset($_POST["contact_address"])) ? $_POST["contact_address"] : "";
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
				$errors[] = "Your description should not be more than 3000 characters in length.";
			}
		}
		
		if(!empty($barter))
		{
			$max_length_barter = 1000;
			if(strlen($barter) > $max_length_barter)
			{
				$errors[] = "Your barter detail should not be more than 1000 characters in length.";
			}
		}
		
		if($price !== "")
		{
			if($price == 0 || $price == "")
			{
				$price = "";
			}
/*			elseif($price > 0 && $price < 999)
			{
				$price = number_format($price, 2);
			}
*/			elseif($price > 999)
			{
			$price = number_format($price, 2, '.', ',');
				$max_length_price = 15;
				if(strlen($price) > $max_length_price)
				{
					$errors[] = "Your " . dynamic_price($category_id) . " should not be more than 15 digits long.";
				}
 
/*
           elseif(!is_int($price))
          {
             $errors[] = "Your " . dynamic_price($category_id) . " should be only numbers";
          }
          else { }
*/
			}
			elseif(preg_match("[0-9]{2|3}\,[0-9]{3}$", $price))
			{
				$price = $_POST["price"];
			}
			elseif($price == number_format($price, 2, '.', ','))
			{
				//Do nought
			}
			else{
					//Do nought
			}
		}
		else {
				$safe_price = is_string($price);
		}
		
		if(!empty($age))
		{
			$max_length_age = 15;
			if(strlen($age) > $max_length_age)
			{
				$errors[] = "Your entry for age should not be more than 15 characters long.";
			}
		}
		
		if($condition == "none")
		{
			$errors[] = "You must choose a " . dynamic_condition($category_id) . ".";
		}

		if(isset($condition))
		{
			$max_length_condition = 15;
			if(strlen($condition) > $max_length_condition)
			{
				$errors[] = "Your entry for " . dynamic_condition($category_id) . " should not be longer than 15 characters.";
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
			
			function get_address($data)
			{
				global $db;
				$query = 'SELECT address FROM gv_member_profile WHERE member_id = ' . $data;
				$result = mysql_query($query, $db) or die (mysql_error($db));
				$row = mysql_fetch_array($result);
				extract($row);
				return $address;
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
			$profile_address = get_address($_SESSION["member_id"]);
			$profile_email = get_email($_SESSION["member_id"]);
			$profile_phone = get_phone($_SESSION["member_id"]);
		}//End of if($use_info == 1)
		
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
			$safe_barter				= check_input($barter);
			$safe_age 					= check_input($age);
			$safe_price					= check_input($price);
			$safe_condition 			= check_input($condition);
			$safe_state 				= check_input($state);
			$safe_city 					= check_input($city);
			$safe_youtube				= $youtube;
			
			if($use_info == 1)
			{
				$safe_contact_name		= $profile_name;
				$safe_contact_address	= $profile_address;
				$safe_contact_phone		= $profile_phone;
				$safe_contact_email		= $profile_email;
			}
			else {
					$safe_contact_name 		= check_input($contact_name);
					$safe_contact_address 	= check_input($contact_address);
					$safe_contact_phone		= check_input($contact_phone);
					$safe_contact_email		= $contact_email;
			}
			
			//Capitalize the first character or word in each word
			$correct_condition 			= ucwords($safe_condition);
			$correct_state				= ucwords($safe_state);
			$correct_city 				= ucwords($safe_city);
			$correct_contact_name		= ucwords($safe_contact_name);
			
			//Insert the OKAY fields/values in the database
			$query = 'UPDATE gv_mall_post SET
					  mall_post_activated_on=NOW(),
					  mall_post_edited_on=NOW(),
					  mall_category_id=' . mysql_real_escape_string($category_id, $db) . ',
					  cc_id=' . mysql_real_escape_string(country_id($_SESSION["member_id"]), $db) . ',
					  mall_post_price="' . mysql_real_escape_string($safe_price, $db) . '",
					  mall_post_youtube="' . mysql_real_escape_string(filter_var($safe_youtube, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '",
					  mall_post_title="' . mysql_real_escape_string(filter_var($safe_title, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '",
					  mall_post_description="' . mysql_real_escape_string($safe_description, $db) . '", mall_post_barter="' . mysql_real_escape_string($safe_barter, $db) . '",
					  mall_post_age="' . mysql_real_escape_string(filter_var($safe_age, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '",
					  mall_post_condition="' . mysql_real_escape_string(filter_var($correct_condition, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '", mall_post_state="' . mysql_real_escape_string(filter_var($correct_state, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '",
					  mall_post_city="' . mysql_real_escape_string(filter_var($correct_city, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '", mall_post_contact_name="' . mysql_real_escape_string($correct_contact_name, $db) . '", mall_post_contact_address="' . mysql_real_escape_string(filter_var($safe_contact_address, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH), $db) . '", mall_post_contact_phone="' . mysql_real_escape_string($safe_contact_phone, $db) . '", mall_post_contact_email="' . mysql_real_escape_string($safe_contact_email, $db) . '", mall_post_contact_website="' . mysql_real_escape_string($contact_website, $db) . '"
					  WHERE 
					  mall_post_id = ' . mysql_real_escape_string($post_id, $db) . '
					  AND member_id = ' . $_SESSION["member_id"];
			mysql_query($query, $db) or die(mysql_error());
			mysql_close();
			
			$_SESSION["errand_title"] = "Post editing success";
			$_SESSION["errand"] = "The post has been updated successfully.";
			header("Location: my_post.php?id=$post_id");
			exit;
		}//End of if(count($errors) < 1)
	}//End of if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["update"] == "Update post")

	$query = 'SELECT mall_category_id, cc_id, mall_post_price, mall_post_youtube, mall_post_title, mall_post_description, mall_post_barter, mall_post_age, mall_post_condition, mall_post_state, mall_post_city, mall_post_contact_name, mall_post_contact_address, mall_post_contact_phone, mall_post_contact_email,  mall_post_contact_website FROM gv_mall_post WHERE  (member_id = ' . $_SESSION["member_id"] . ' AND mall_post_id = ' . mysql_real_escape_string($post_id, $db) . ') AND mall_post_block = 0';
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
			
			$category_id =  $row["mall_category_id"];
			$price =  $row["mall_post_price"];
			$title = $row["mall_post_title"];
			$description =  $row["mall_post_description"];
			$barter =  $row["mall_post_barter"];
			$country_id =  $row["cc_id"];
			$age =  $row["mall_post_age"];
			$condition =  $row["mall_post_condition"];
			$youtube =  $row["mall_post_youtube"];
			$state =  $row["mall_post_state"];
			$city =  $row["mall_post_city"];
			$contact_name =  $row["mall_post_contact_name"];
			$contact_address =  $row["mall_post_contact_address"];
			$contact_phone =  $row["mall_post_contact_phone"];
			$contact_email =  $row["mall_post_contact_email"];
			$contact_website =  $row["mall_post_contact_website"];
		}//End of if(mysql_num_rows($result) > 0)
	}//End of if($result = mysql_query($query))


	//Get Mall meta detail
	require_once('../../Server_Includes/scripts/common_scripts/money_matters.php');
	
	//Get Mall meta detail
	require_once('../../Server_Includes/scripts/mall_scripts/mall_meta.php');
	
	//Get Genieverse Mall footer function
	require_once('../../Server_Includes/scripts/mall_scripts/mall_member_footer.php');
	
	$extra_title = "Post editing | Genieverse Mall - Free web market ";
	$shop_item = "set";
	$meta_keyword = $mall_meta_keywords;
	$meta_description = $mall_meta_description;
	
	$ckeditor = "set";

	require_once('../../Server_Includes/scripts/common_scripts/common_member_page_head2.php'); ?><body>
<?php require_once('../../Server_Includes/scripts/mall_scripts/mall_member_page_header.php'); ?>
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
						<h4 class="text-left">Description</h4>
						<textarea name="description" id="description"  class="form-control" ><?php echo html_entity_decode($description); ?></textarea>
						<script> CKEDITOR.replace( 'description' ); </script>
					</div>
				</div>
			</div>
			
			<div class="form-group col-lg-12">
				<div class="row">
					<div class=" col-md-8 col-md-offset-2 text-center">
						<?php
	if($category_id == 10 || $category_id == 16)
		{ ?><input type="hidden" name="barter" class="form-control" value="<?php echo $barter; ?>">
<?php
	}
	elseif($category_id == 23)
	{ ?><input type="hidden" name="barter" class="form-control" value="<?php echo $barter; ?>">
<?php 
	}
	else {
  ?><span class="pull-right"></span>
						<h4 class="text-left">Barter:</h4>
						<textarea name="barter" id="barter" class="form-control" rows="5" cols="40"><?php echo html_entity_decode($barter); ?></textarea>
						<script> CKEDITOR.replace( 'barter' ); </script>
<?php
   }
?>
					</div>
				</div>
			</div>
			
			<div class="form-group col-lg-12">
				<div class="row">
					<div class=" col-md-8 col-md-offset-2 text-center">
						<?php
							if(in_array($category_id, $hidden_condition))
							{
						?><input type="hidden" name="condition" class="form-control" value="<?php echo $condition; ?>"/>
						<?php
							}
							if(in_array($category_id, $unique_condition))
							{
								if($category_id == 10)
								{
						?><span class="pull-right"></span>
						<h4 class="text-left"><?php echo ucfirst(dynamic_condition($category_id)); ?></h4>
						<select name="condition" class="form-control" >
							<option value="none">Select <?php echo dynamic_condition($category_id); ?></option>
							<option <?php	if(isset($condition) && $condition == "Permanent"){ echo 'selected="selected" ';} ?> value="Permanent">Permanent</option>
							<option <?php	if(isset($condition) && $condition == "Temporary"){ echo 'selected="selected" ';} ?> value="Temporary">Temporary</option>
							</select>
							<?php
								}
								
								if($category_id == 15)
								{
						?><h4 class="text-left"><?php echo ucfirst(dynamic_condition($category_id)); ?></h4>
						<select name="condition" class="form-control" >
							<option value="none">Select <?php echo dynamic_condition($category_id); ?></option>
							<option <?php	if(isset($condition) && $condition == "Completed"){ echo 'selected="selected" ';} ?>value="Completed">Completed</option>
							<option <?php	if(isset($condition) && $condition == "Uncompleted"){ echo 'selected="selected" ';} ?>value="Uncompleted">Uncompleted</option>
							<option <?php	if(isset($condition) && $condition == "Renovated"){ echo 'selected="selected" ';} ?>value="Renovated">Renovated</option>
							<option <?php	if(isset($condition) && $condition == "Substructure"){ echo 'selected="selected" ';} ?>value="Substructure">Substructure</option>
							<option <?php	if(isset($condition) && $condition == "Fertile land"){ echo 'selected="selected" ';} ?>value="Fertile land">Fertile land</option>
							</select>
							<?php
								}
								
								if($category_id == 16)
								{
						?><h4 class="text-left"><?php echo ucfirst(dynamic_condition($category_id)); ?></h4>
						<select name="condition" class="form-control" >
							<option value="none">Select <?php echo dynamic_condition($category_id); ?></option>
							<option <?php	if(isset($condition) && $condition == "Single"){ echo 'selected="selected" ';} ?>value="Single">Single</option>
							<option <?php	if(isset($condition) && $condition == "Married"){ echo 'selected="selected" ';} ?>value="Married">Married</option>
							<option <?php	if(isset($condition) && $condition == "Divorced"){ echo 'selected="selected" ';} ?>value="Divorced">Divorced</option>
							<option <?php	if(isset($condition) && $condition == "Widowed"){ echo 'selected="selected" ';} ?>value="Widowed">Widowed</option>
							</select>
							<?php
								}
								
								if($category_id == 14)
								{
						?><h4 class="text-left"><?php echo ucfirst(dynamic_condition($category_id)); ?></h4>
						<select name="condition" class="form-control" >
							<option value="none">Select <?php echo dynamic_condition($category_id); ?></option>
							<option <?php	if(isset($condition) && $condition == "Boxed new"){ echo ' selected="selected" ';} ?>value="Boxed new">Boxed new</option>
							<option <?php	if(isset($condition) && $condition == "Boxed used"){ echo ' selected="selected" ';} ?>value="Boxed used">Boxed used</option>
							<option <?php	if(isset($condition) && $condition == "Non-boxed new"){ echo ' selected="selected" ';} ?>value="Non-boxed new">Non-boxed new</option>
							<option <?php	if(isset($condition) && $condition == "Non-boxed used"){ echo ' selected="selected" ';} ?>value="Non-boxed used">Non-boxed used</option>
							</select>
							<?php
								}
							}
							
							if(in_array($category_id, $common_condition))
							{
						?><h4 class="text-left">Condition</h4>
						<select name="condition" class="form-control" >
							<option value="none">Select condition</option>
							<option <?php	if(isset($condition) && $condition == "New"){ echo 'selected="selected" ';} ?>value="new">New</option>
							<option <?php	if(isset($condition) && $condition == "Used"){ echo 'selected="selected" ';} ?>value="used">Used</option>
							</select>
							<?php
							}
						?>
					</div>
				</div>
			</div>
			
			<div class="form-group col-lg-12">
				<div class="row">
					<div class=" col-md-8 col-md-offset-2 text-center">
						<span class="pull-right"></span>
						<?php
							if($category_id == 16 || $category_id == 23)
							{
								?><input type="hidden" name="price" class="form-control" value="<?php echo $price; ?>">
						<?php
							}
							else {
						?><h4 class="text-left"><?php echo dynamic_price($category_id); ?></h4>
						<?php echo currency_code($country_id, $category_id); ?> <input type="text" name="price" class="form-control" value="<?php echo $price; ?>">
						<?php
							}
						?>
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
						<h4 class="text-left">Contact Address</h4>
						<textarea name="contact_address" id="contact_address" class="form-control" ><?php echo $contact_address; ?></textarea>
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
						<p>Example:
							<br>http://www.the_web_address.com<br>
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

<?php require_once('../../Server_Includes/scripts/common_scripts/common_member_page_before_body_end.php'); ?>

</body>

</html><?php 
	mysql_close();
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
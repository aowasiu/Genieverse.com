<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	if(!isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] !== 1)
	{
		$_SESSION['errand_title'] = "Log in issue";
		$_SESSION['errand'] = "You must be logged in to view your profile.";
		//Take this visitor to the log in page because he's not logged in
		header("Location: log_in.php?location=" . urlencode($_SERVER["REQUEST_URI"]));
		exit;
	}

	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["delete"] == "Deactivate Profile")
	{
		header("Location: delete_profile.php");
		exit;
	}

	//Connect tot the database
	require_once('Server_Includes/visitordbaccess.php');

	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["delete"] == "Delete photo")
	{
		require_once("Server_Includes/image_directory_core.php");
		
		//Path to images directory
		$image_directory = $image_directory_core . '/images/service_images/profile';
		
		$photo = (isset($_POST["photo"])) ? $_POST["photo"] : "";

		$existing_images = scandir($image_directory);
		
		if(!in_array($photo, $existing_images))
		{
			//Hurray, now redirect this member
			$_SESSION["errand_title"] = "No such photo";
			$_SESSION["errand"] = "The photo you refer to doesn't exist.";
			$_SESSION["errand"] .= '<br><a href="services.php">Click here to continue.</a>';
			header("Location: issues.php");
			exit;
		}
		else {
				unlink($image_directory . '/' . $photo);
		}
		$query = 'DELETE FROM gv_profile_image WHERE profile_image_filename = "' . mysql_real_escape_string($photo, $db) . '" AND member_id = ' . mysql_real_escape_string($_SESSION["member_id"], $db);
		mysql_query($query, $db) or die (mysql_error($db));
	}//End of "Delete photo"

	//Get functions
	require_once('Server_Includes/scripts/common_scripts/get_page_features.php');
	require_once('Server_Includes/scripts/common_scripts/get_name_functions.php');
	require_once('Server_Includes/scripts/common_scripts/date_formats.php');
	
	$query = 'SELECT 
			membership_date, last_login, privilege, username, email_address, continent_id, cc_id, state_name, city_name, edit_date, firstname, middlename, surname, date_of_birth, gender, address, postcode, phone, about_me, hobbies 
			FROM 
			gv_members AS members,
			gv_member_profile AS profile
			WHERE members.member_id = profile.member_id 
			AND username = "' . mysql_real_escape_string($_SESSION["username"], $db) . '"';
	$result = mysql_query($query, $db) or die (mysql_error($db));
	
	if(mysql_num_rows($result) < 1)
	{
		$_SESSION["errand"] = "You haven't created a profile yet.";
		header("Location: services.php");
		exit;
	}
	
	$row = mysql_fetch_assoc($result);
	extract($row);

	if($row["privilege"] == 1)
	{
		$privilege = "Member";
	}
	elseif($row["privilege"] == 2) {
			$privilege = "Moderator";
	}
	else {
			$privilege = "Administrator";
	}

	//Set page to use Fancybox
	$fancy_box = "Use Fancybox.";

	//Set extra title
	$extra_title =  ucfirst($_SESSION["username"]) . "'s Profile";

	//Set page template name
	$shop_item = "set";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">

		<div class="caption-full">
            <?php if(isset($_SESSION["errand"])){ echo '<h5 class="pull-right alert alert-success">' . $_SESSION["errand"] . '</h5>';
					unset($_SESSION["errand"]); }?>
			<h3><?php echo ucfirst($_SESSION["username"]); ?>'s Profile</h3>
			<hr>
		</div>
		
        <div class="row">
            <div class="col-md-3">
                <p class="lead">Categories</p>
                <div class="list-group">
				<?php  ?>
	<a href="m_home.php" class="list-group-item">Genieverse Mall</a>
                    <a href="voice/home.php" class="list-group-item">Genieverse Voice</a>
                    <a href="board/home.php" class="list-group-item">Genieverse Board</a>
				</div>
            </div>

			<div class="col-md-9">
				<div class="row">
					<div class="col-md-12 text-center">
						<?php
					$members_image = members_image($_SESSION["member_id"]);
					if($members_image !== "default.png")
					{ ?><a href="images/service_images/profile/<?php echo $members_image; ?>" class="fancybox" title="<?php echo profile_image_caption($_SESSION["member_id"]); ?>"><img class="img-responsive" src="images/service_images/profile/<?php echo $members_image; ?>" style="width:35%; height:35%;" alt="<?php echo $_SESSION["username"]; ?> profile | Genieverse - Virtual universe" /></a><br/>
					<script>
						$(document).ready(function() {
							$('.fancybox').fancybox();
						});
					</script>
					<a href="images/service_images/profile/<?php echo $members_image; ?>" class="fancybox" rel="gallery" title="<?php echo profile_image_caption($_SESSION["member_id"]); ?>"><button class="btn btn-default pull-left">Enlarge</button></a>
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
					<input type="hidden" name="photo" value="<?php echo $members_image; ?>" />
					<input class="pull-right btn btn-default" type="submit" name="delete" value="Delete photo" />
					</form>
					<?php
					}
					else {
					?><a href="new_profile_photo.php">Upload photo</a> 
					<img style="width:35%; height:35%;" src="images/service_images/profile/<?php echo $members_image; ?>" alt="Genieverse - Virtual universe" />
					<?php
					}
					?>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<span class="pull-right badge"><?php echo ucfirst($row["username"]); ?></span>
					<p><b>Username</b></p>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<span class="pull-right"><?php	echo ucfirst($row["email_address"]);	?></span>
					<p><b>Email</b></p>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<span class="pull-right"><?php	echo $privilege;	?></span>
					<p><b>Status</b></p>
				</div>
			</div>
			<hr>
			<?php
				if($privilege !== "1"){
				echo '<div class="row">
				<div class="col-md-12">
					<span class="pull-right">' . moderator_alias($_SESSION["member_id"]) . '</span>
					<p><b>Moderator alias</b></p>
				</div>
			</div>
			<hr>';
				} ?>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo $row["firstname"]; ?></span>
								<p><b>First name</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo $row["middlename"]; ?></span>
								<p><b>Middle name</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo $row["surname"]; ?></span>
								<p><b>Surname</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo $row["date_of_birth"]; ?></span>
								<p><b>Date of Birth</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo age($row["date_of_birth"]) . ' years old'; ?></span>
								<p><b>Age</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo $row["gender"]; ?></span>
								<p><b>Gender</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo continent_name($row["continent_id"]); ?></span>
								<p><b>Continent</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo country_name($row["cc_id"]); ?></span>
								<p><b>Country</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo $row["state_name"]; ?></span>
								<p><b>State</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo $row["city_name"]; ?></span>
								<p><b>City</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo $row["address"]; ?></span>
								<p><b>Address</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo $row["postcode"]; ?></span>
								<p><b>Postcode</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo $row["phone"]; ?></span>
								<p><b>Phone</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo $row["about_me"]; ?></span>
								<p><b>About me</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo $row["hobbies"]; ?></span>
								<p><b>Hobbies/Interests</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php echo custom_date($row["membership_date"], "l, F t, Y. g:ia"); ?></span>
								<p><b>Joined on</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo custom_date($row["edit_date"], "l, F t, Y. g:ia"); ?></span>
								<p><b>Edited profile on</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"><?php	echo custom_date($row["last_login"], "l, F t, Y. g:ia"); ?></span>
								<p><b>Last visit was on</b></p>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<span class="pull-right"></span>
								<p><form action="update_profile.php" method="POST"><input class="btn btn-info" type="submit" name="update" value="Edit your Profile" /></form><br><?php /* <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"><input class="btn btn-info" type="submit" name="delete" value="Deactivate Profile" /> */ ?></form></p>
							</div>
						</div>
						<hr>
					</div>
        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-8 col-md-offset-1">
                    <p><?php echo $the_footer; ?></p>
                </div>
            </div>
        </footer>

    <!-- /.container -->

<?php require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_before_body_end2.php'); ?>

</body>

</html><?php	
	mysql_close(); ?>
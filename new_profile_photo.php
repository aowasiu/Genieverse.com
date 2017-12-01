<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	if(!isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] !== 1)
	{
		//Take this visitor to the log in page because he's not logged in
		header("Location: log_in.php?location=" . urlencode($_SERVER["REQUEST_URI"]));
		exit;
	}

	require_once('Server_Includes/visitordbaccess.php');
	
	//Declare errors as array
	$errors = array();
	
	$maximum_photo_size = 204800;
	$caption = (isset($_POST["caption"])) ? $_POST["caption"] : "";
	$upload_file = (isset($_POST["upload_file"])) ? $_POST["upload_file"] : "";
	
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Upload photo")
	{
		require_once("Server_Includes/image_directory_core.php");
		
		//Path to images directory
		$image_directory = $image_directory_core . '/images/service_images/profile';
		
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
			$errors[] = "The photo is too large. It shouldn't be more than 200kb.";
		}
		
		if(count($errors) < 1)
		{
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
			
			require_once('Server_Includes/scripts/common_scripts/check_input.php');
			
			$safe_caption = check_input($caption);
			
			$temporary_photo_name = 'No name';
			
			//Insert photo information into the post photo table
			$query = 'INSERT INTO gv_profile_image
						(profile_image_id, member_id, profile_image_block, profile_image_assessment, profile_image_assessed_on, profile_image_assessed_by, profile_image_created_on, profile_image_edited_on, profile_image_caption, profile_image_filename)
						VALUES
						(NULL, ' . mysql_real_escape_string($_SESSION["member_id"], $db) . ', 0, 0, NULL, NULL, NOW(), NOW(), "' . mysql_real_escape_string($safe_caption, $db) . '", "' . mysql_real_escape_string($temporary_photo_name, $db) . '")';
			mysql_query($query, $db) or die(mysql_error($db));
			
			//Get the incrementing id of the above table
			$last_id = mysql_insert_id();	
			$file_md5_hash_name = md5($last_id);

			//Create a name for the image by adding an extension to the id to make the name unique
			$image_name = substr("$file_md5_hash_name", 3) . $ext;
			
			//Now update the above table with the new and unique image name
			$query = 'UPDATE gv_profile_image SET profile_image_filename="' . $image_name . '" WHERE profile_image_id = ' . $last_id;
			mysql_query($query, $db) or die(mysql_error($db));
			
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
			
			header("Location: profile.php");
			exit;
		}
	}
	
	function get_current_photo($data)
	{
		global $db;
		$query = 'SELECT profile_image_filename FROM gv_profile_image WHERE member_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		if(mysql_num_rows($result) < 1)
		{
			$the_find = "";
		}
		else {
				$row = mysql_fetch_assoc($result);
				extract($row);
				$the_find = $profile_image_filename;
		}
		return $the_find;
	}

	//Set extra title
	$extra_title =  ucfirst($_SESSION["username"]) . "'s profile photo";

	//Set page template name
	$shop_item = "set";	

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">

		<div class="row">
			<div><!--class="col-md-9"-->	
				<div class="row">
					<div class="col-md-12">
						<span class="btn btn-default pull-right"><a href="profile.php">Back to profile</a></span>
						<h3 class="alert alert-info"><b><?php echo ucfirst($_SESSION["username"]); ?>'s new profile photo</b></h3>
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
            <div class="col-lg-8 text-center">
				<div>
				<p class="alert alert-danger">Your photo could not be uploaded<?php if(count($errors) < 2){ echo ':'; } else{ echo 's:'; } ?></p>
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
		<form role="form" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<div class="form-group col-lg-12">
				<div class="col-lg-6 col-lg-offset-3 text-center">
					<div>
						<h4>Caption</h4>
						<input type="text" class="form-control" name="caption" value="<?php echo $caption; ?>">
					</div>
				</div>
			</div>
		
			<div class="form-group col-lg-12">
				<div class="col-lg-6 col-lg-offset-3 text-center">
					<div>
						<h4>Locate photo</h4>
						<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maximum_photo_size; ?>"class="form-control">
						<input type="file" name="upload_file" id="upload_file" class="form-control">
					</div>
				</div>
			</div>

			<div class="form-group col-lg-12">
				<div class="col-lg-12 text-center">
					<div>
						<input type="hidden" name="id" value="<?php echo $get_id; ?>">
						<input class="btn btn-info" type="submit" name="submit" value="Upload photo">
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

<?php require_once('Server_Includes/scripts/common_scripts/common_member_page_before_body_end.php'); ?>

</body>

</html><?php
	mysql_close($db);
?>
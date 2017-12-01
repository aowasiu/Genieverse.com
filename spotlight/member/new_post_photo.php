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
	
	$photo_upload = 'spotlight_photo';
	$post_id = (isset($_POST["id"])) ? $_POST["id"] : 0;
	$get_id = (isset($_GET["id"])) ? $_GET["id"] : $post_id;
	
	if($get_id == 0 || !isset($get_id))
	{
		header("Location: dashboard.php");
		exit;
	}
	
	//Connect to the database
	require_once('../../Server_Includes/visitordbaccess.php');
	
	//Investigate or screen the provided id as post id
	function does_id_exist($data1, $data2)
	{
		global $db;
		$query = 'SELECT spotlight_post_id FROM gv_spotlight_post WHERE (spotlight_post_block = 0) AND (spotlight_post_id = ' . $data1 . ' AND member_id = ' . $data2 . ')';
		$result = mysql_query($query, $db) or die(mysql_error($db));
		
		if(mysql_num_rows($result) > 0)
		{
			$post_exists = 1;
		}
		else {
				$post_exists = 0;
		}
		return $post_exists;
	}
	
	$post_exists_or_not = does_id_exist($get_id, $_SESSION["member_id"]);
	
	if($post_exists_or_not == 0)
	{
		$_SESSION["errand_title"] = "New post photo encountered a problem";
		$_SESSION["errand"] = "No such post nor photo exists";
		header("Location: dashboard.php");
		exit;
	}
	
	//Now count the number of photos already in the database for this post
	function how_many_photos($data)
	{
		global $db;
		$query = 'SELECT spotlight_post_id FROM gv_spotlight_post_image WHERE (spotlight_image_block = 0) AND spotlight_post_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		
		if(mysql_num_rows($result) > 0)
		{
			$number_of_images = mysql_num_rows($result);
				}
		else {
				$number_of_images = 0;
		}
		return $number_of_images;
	}

	$number_of_photos_in_the_db = how_many_photos($get_id);
	$maximum_number_of_photos_you_can_upload = 6;
	$remaining_number_of_photos_you_can_upload = $maximum_number_of_photos_you_can_upload - $number_of_photos_in_the_db;
	
	//Declare errors
	$errors = array();
	
	$maximum_photo_size = 204800;
	$caption = (isset($_POST["caption"])) ? $_POST["caption"] : "";
	$upload_file = (isset($_POST["upload_file"])) ? $_POST["upload_file"] : "";
	
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Upload photo")
	{
		require_once('../../Server_Includes/image_directory_core.php');
		
		//Path to images directory
		$image_directory = $image_directory_core . "/images/service_images/spotlight";
		
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
	
			//Get check_input function
			require_once('../../Server_Includes/scripts/common_scripts/check_input.php');
			
			$safe_caption = check_input($caption);
			
			$temporary_photo_name = 'No name';
			
			//Insert photo information into the post photo table
			$query = 'INSERT INTO gv_spotlight_post_image
						(spotlight_image_id, spotlight_post_id, spotlight_image_created_on, spotlight_image_edited_on, spotlight_image_caption, spotlight_image_filename)
						VALUES
						(NULL, ' . mysql_real_escape_string($post_id, $db) . ', NOW(), NOW(), "' . mysql_real_escape_string($safe_caption, $db) . '", "' . mysql_real_escape_string($temporary_photo_name, $db) . '")';
			mysql_query($query, $db) or die(mysql_error($db));
			
			//Get the incrementing id of the above table
			$last_id = mysql_insert_id();	
			$file_md5_hash_name = md5($last_id);

			//Create a name for the image by adding an extension to the id to make the name unique
			$image_name = substr("$file_md5_hash_name", 3) . $ext;
			
			//Now update the above table with the new and unique image name
			$query = 'UPDATE gv_spotlight_post_image SET spotlight_image_filename="' . $image_name . '" WHERE spotlight_image_id = ' . $last_id;
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
			
			//Hurray, now redirect this member
			$_SESSION["keep_session"] = "";
			$_SESSION["errand_title"] = "The photo has been uploaded successfully.";
			$_SESSION["errand"] = '<br><a href="spotlight/member/new_post_photo.php?id=' . $post_id .'">Click here to upload more photos.</a>' . '<br> or <a href="spotlight/member/my_post.php?id=' . $post_id .'">Click here to continue.</a>';
			header("Location: ../../issues.php");
			exit;
		}//End of if(count($errors) < 1)
	}//End of if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Upload photo")

	//Get Spotlight meta detail
	require_once('../../Server_Includes/scripts/spotlight_scripts/spotlight_meta.php');
	
	//Get Genieverse Spotlight footer function
	require_once('../../Server_Includes/scripts/spotlight_scripts/spotlight_member_footer.php');
	
	$extra_title = "New post photo | Genieverse Spotlight - " . $spotlight_title_description;
	$shop_item = "set";
	$meta_keyword = $spotlight_meta_keywords;
	$meta_description = $spotlight_meta_description;
	
	require_once('../../Server_Includes/scripts/common_scripts/common_member_page_head2.php'); ?><body>
<?php require_once('../../Server_Includes/scripts/spotlight_scripts/spotlight_member_page_header.php'); ?>
    <!-- Page Content -->
    <div class="container">

		<div class="row">
			<div><!--class="col-md-9"-->	
				<div class="row">
					<div class="col-md-12">
						<span class="pull-right"><a href="my_post.php?id=<?php echo $get_id; ?>">View this post</a></span>
						<h3 class="alert alert-info"><b><?php echo ucfirst($_SESSION["username"]); ?>'s new post photo</b></h3>
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
		
		<div class="row">
            <div class="col-lg-12 text-center">
				<div class="alert alert-info">
					<p>Total allowed photos for this post is <b><?php echo $maximum_number_of_photos_you_can_upload; ?></b>.</p>
				</div>
			</div>
		</div>
		
		<div class="row">
            <div class="col-lg-12 text-center">
				<div class="alert alert-info">
					<p>You have uploaded <b><?php echo $number_of_photos_in_the_db; ?></b> photo<?php
						if($number_of_photos_in_the_db > 1)
						{
							echo 's';
						}
						?> for this post.</p>
				</div>
			</div>
		</div>
		<?php
	if($remaining_number_of_photos_you_can_upload < 1)
	{
?>
		<div class="row">
            <div class="col-lg-12 text-center">
				<div class="alert alert-danger">
					<p>You can't upload any more photos for this post.</p>
				</div>
			</div>
		</div>

<?php
	}
	else {
?>
		<div class="row">
            <div class="col-lg-12 text-center">
				<div class="alert alert-info">
					<p>You may upload up to <b><?php echo $remaining_number_of_photos_you_can_upload; ?></b> photos.</p>
				</div>
			</div>
		</div>

		<div class="row">
            <div class="col-lg-12 text-center">
				<div class="alert alert-danger">
					<p>The photo should not be more than 200kb in size.<br/>Only photos in GIF, JPG/JPEG and PNG formats are allowed.<br/>All photos are subject to <a href="index.php">Genieverse</a> <a href="../../terms_of_use.php">Terms of Use</a></p>
				</div>
			</div>
		</div>

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
<?php
	}
?>
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
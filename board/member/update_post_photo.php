<?php

	//Get custom error function script 
	require_once('../../Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== 1)
	{
		//Take this visitor to the log in page because he's not logged in
		header("Location: ../log_in.php?location=" . urlencode($_SERVER["REQUEST_URI"]));
		exit;
	}

	$get_id = (isset($_GET["id"])) ? $_GET["id"] : 0;
	$get_name = (isset($_GET["name"])) ? $_GET["name"] : "";
	$post_id = (isset($_POST["id"])) ? $_POST["id"] : $get_id;
	$post_name = (isset($_POST["name"])) ? $_POST["name"] : $get_name;
	
	if(!isset($post_id) || $post_id == 0)
	{
		$_SESSION["errand_title"] = "Post photo editing encountered a problem";
		$_SESSION["errand"] = "The photo you want to change doesn't exist.";
		header("Location: dashboard.php");
		exit;
	}

	if(!isset($post_name) || $post_name == "")
	{
		$_SESSION["errand_title"] = "Post photo editing encountered a problem";
		$_SESSION["errand"] = "The photo you want to change doesn't exist.";
		header("Location: dashboard.php");
		exit;
	}

	//Connect to the database
	require_once('../../Server_Includes/visitordbaccess.php');
	
	function find_image_existence($data1, $data2)
	{
		global $db;
		$query = 'SELECT spotlight_image_id FROM gv_spotlight_post_image WHERE (spotlight_image_block = 0 AND spotlight_post_id = ' . mysql_real_escape_string($data1, $db) . ') AND spotlight_image_filename = "' . mysql_real_escape_string($data2, $db) . '"';
		$result = mysql_query($query, $db) or die(mysql_error($db));
		
		if(mysql_num_rows($result) > 0)
		{
			$image_exists = 'Yes';
		}
		else {
				$image_exists = 'No';
		}
		return $image_exists;
	}//End of find_image_existence() definition
	
	$image_is_found = find_image_existence($post_id, $post_name);
	
	if($image_is_found == 'No')
	{
		$_SESSION["errand_title"] = "Post photo editing encountered a problem";
		$_SESSION["errand"] = "The photo you want to change doesn't exist.";
		header("Location: dashboard.php");
		exit;
	}

	//Declare errors
	$errors = array();
	
	$maximum_photo_size = 204800;
	$caption = (isset($_POST["caption"])) ? $_POST["caption"] : "";
	$upload_file = (isset($_POST["upload_file"])) ? $_POST["upload_file"] : "";
	
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Change photo")
	{

		if(!isset($post_name) || $post_name == 0)
		{
			$_SESSION["errand_title"] = "Post photo editing encountered a problem";
			$_SESSION["errand"] = "The photo you want to change doesn't exist.";
			header("Location: dashboard.php");
			exit;
		}

		require_once("../../Server_Includes/image_directory_core.php");
		
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
		}//End of if($_FILES['upload_file']['error'] != UPLOAD_ERR_OK)
		
		//TEST FILE TOO LARGE
		if($_FILES['upload_file']['size'] > $maximum_photo_size)
		{
			$errors[] = "The photo is too large. It shouldn't be more than 200kb.";
		}
		
		if(count($errors) < 1)
		{
			//Now delete the previous image from the directory
			unlink($image_directory . '/' . $post_name);
			
			//Now delete details of the previous image from the post photo table
			$query = 'DELETE FROM gv_spotlight_post_image WHERE spotlight_image_filename = "' . mysql_real_escape_string($post_name, $db) . '"';
			mysql_query($query, $db) or die(mysql_error($db));
			
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
						(spotlight_image_id, spotlight_post_id, spotlight_image_block, spotlight_image_assessment, spotlight_image_assessed_by, spotlight_image_created_on, spotlight_image_edited_on, spotlight_image_caption, spotlight_image_filename)
						VALUES
						(NULL, ' . mysql_real_escape_string($post_id, $db) . ', 0, 0, " ", NOW(), NOW(), "' . mysql_real_escape_string($safe_caption, $db) . '", "' . mysql_real_escape_string($temporary_photo_name, $db) . '")';
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
			$_SESSION["errand_title"] = "The photo has been changed successfully.";
			$_SESSION["errand"] = '<br><a href="spotlight/member/new_post_photo.php?id=' . $post_id . '&name=' .$post_name . '">Click here to upload more photos.</a>' . '<br> or <a href="spotlight/member/my_post.php?id=' . $post_id . '">Click here to continue.</a>';
			header("Location: ../../issues.php");
			exit;
		}//End of if(count($errors) < 1)
	}//End of if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Change photo")

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
						<span class="pull-right"><a href="my_post.php?id=<?php echo $get_id; ?>">Back to post</a></span>
						<h3 class="alert alert-info"><b><?php echo ucfirst($_SESSION["username"]); ?>'s photo change</b></h3>
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
				<p class="alert alert-danger">Your photo could not be changed due to the following issue<?php if(count($errors) < 2){ echo ':'; } else{ echo 's:'; } ?></p>
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
				<div class="alert alert-danger">
					<p>The photo should not be more than 200kb in size.<br/>Only photos in GIF, JPG/JPEG and PNG formats are allowed.<br/>All photos are subject to <a href="../../index.php">Genieverse</a> <a href="../../terms_of_use.php">Terms of Use</a></p>
				</div>
			</div>
		</div>

	<form role="form" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<div class="form-group col-lg-12">
			<div class="col-lg-12 text-left">
				<div>
					<h4>Caption</h4>
					<input type="text" class="form-control" name="caption" value="<?php echo $caption; ?>">
				</div>
			</div>
		</div>
	
		<div class="form-group col-lg-12">
			<div class="col-lg-12 text-left">
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
					<input type="hidden" name="id" value="<?php echo $post_id; ?>">
					<input type="hidden" name="name" value="<?php echo $post_name; ?>">
					<input class="alert alert-info" type="submit" name="submit" value="Change photo">
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

<?php require_once('../../Server_Includes/scripts/common_scripts/common_member_page_before_body_end.php'); ?>

</body>

</html><?php 
	mysql_close($db);
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
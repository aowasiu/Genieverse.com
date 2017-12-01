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

	if($_SESSION["privilege"] == 1)
	{
		$_SESSION["errand_title"] = "Moderation issue.";
		$_SESSION["errand"] = "You can't view the moderation page because you're not a Moderator.";
		//Take this member to the dashboard because he's not a moderator
		header("Location: dashboard.php");
		exit;
	}

	$get_id = (isset($_GET["id"])) ? $_GET["id"]: "";
	$action = (isset($_GET["action"])) ? $_GET["action"]: "";
	
	//Connect to the database
	require_once('../../Server_Includes/visitordbaccess.php');

	if($action !== "" && $get_id !== "")
	{
		if($action == 'approve')
		{
			$query = 'UPDATE gv_voice_post_image SET voice_image_assessment=1, voice_image_assessed_by="' . $_SESSION["username"] . '", voice_image_assessed_on=NOW() WHERE voice_image_block = 0 AND voice_image_filename = ' . $get_id;
		}
		elseif($action == 'disapprove')
		{
			$query = 'UPDATE gv_voice_post_image SET voice_image_assessment=1, voice_image_assessed_by="' . $_SESSION["username"] . '", voice_image_assessed_on=NOW(), voice_image_block=1 WHERE voice_image_block = 0 AND voice_image_filename = ' . $get_id;
		}
		else{
			//Do nought
		}
		
		mysql_query($query, $db) or die(mysql_error($db));
		
		$query1 = 'UPDATE gv_mod_points SET mod_points=mod_points + 1, last_mod_date=NOW() WHERE member_id = ' . $_SESSION["member_id"];
		mysql_query($query1, $db) or die(mysql_error($db));	
		
	}//End of if($action !== "" && $get_id !== "")
	
	//Get category_name
	require_once('../../Server_Includes/scripts/common_scripts/category_name2.php');
	require_once('../../Server_Includes/scripts/voice_scripts/functions_for_voice.php');
	
	//Get Voice meta detail
	require_once('../../Server_Includes/scripts/voice_scripts/voice_meta.php');
	
	//Get Genieverse Voice footer function
	require_once('../../Server_Includes/scripts/voice_scripts/voice_member_footer.php');

	//Get change_date_medium and currency_code functions
	require_once('../../Server_Includes/scripts/common_scripts/date_formats.php');

	$query = 'SELECT voice_image_id, voice_post_id, voice_image_created_on, voice_image_edited_on, voice_image_caption, voice_image_filename FROM gv_voice_post_image	WHERE	(voice_image_block = 0 AND voice_image_assessment = 0) AND (voice_image_assessed_by = "None") LIMIT 1';
	$result = mysql_query($query, $db) or die(mysql_error($db));

	if($result = mysql_query($query))
	{
		if(mysql_num_rows($result) < 1)
		{
			$_SESSION["errand"] = "There's no unmoderated photo.";
			header("Location: dashboard.php");
			exit;
		}
		else {
				$row = mysql_fetch_array($result);
				extract($row);
				$posts_id = $row["voice_post_id"];
				$photos_id = $row["voice_image_id"];
				$photos_creation = $row["voice_image_created_on"];
				$photos_editing = $row["voice_image_edited_on"];
				$photos_caption = $row["voice_image_caption"];
				$photos_filename = $row["voice_image_filename"];
		}//End of if(mysql_num_rows($result) < 1)
	}//End of if($result = mysql_query($query))
	
	$posts_category_id = voice_category_id($posts_id);
	
	$extra_title = "Photo Moderation | Genieverse Voice - Tell the world what's happening. ";
	$shop_item = "set";
	$meta_keyword = $voice_meta_keywords;
	$meta_description = $voice_meta_description;

	require_once('../../Server_Includes/scripts/common_scripts/common_member_page_head2.php'); ?><body>
		<?php require_once('../../Server_Includes/scripts/voice_scripts/voice_member_page_header.php'); ?>
    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <div class="col-md-3">
                <p class="lead">Categories</p>
                <div class="list-group">
					<a href="../search.php?category=Head-Of-State" class="list-group-item<?php if($the_category_name == "Head-Of-State"){ echo ' active'; }?>">Head of State</a><?php

		function getCategoryList($the_category_name)
		{
			global $db;
			$query = 'SELECT voice_category_id, voice_category_name FROM gv_voice_category where voice_category_lock = 0 ORDER BY voice_category_id';
			$result = mysql_query($query, $db) or die(mysql_error($db));

			$row = mysql_fetch_assoc($result);
			
			//Get modified_for_url function
			require_once('../../Server_Includes/scripts/common_scripts/modified_for_url.php');
			
			while($row = mysql_fetch_assoc($result))
			{
				extract($row);
				echo  '
			<a href="../search.php?category=' . modified_for_url($voice_category_name, ' ', '-', '') . '" class="list-group-item">';
			echo $voice_category_name . '</a>';
			}
		}

		getCategoryList($the_category_name);

				?>
                </div>
            </div>
            <div class="col-md-9">

					<p class="lead"><a href="dashboard.php">Voice Dashboard</a></p><hr>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><p class="alert alert-info"><b><a href="../../profile.php">My Profile</a></b></p></span>
                            <p class="alert alert-info"><b><a href="../../services.php">Explore Genieverse</a></b></p>
                        </div>
                    </div>
					<br>
					<div class="row">
                        <div class="col-md-12">
                            <span class="pull-right badge"><?php echo moderation_points($_SESSION["member_id"]);	?></span>
                            <p><b>Your Mod Points</b></p>
                        </div>
                    </div>
					
                    <hr>
					
                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php echo voice_category_name($posts_category_id); ?></span>
                            <p><b>Category</b></p>
                       </div>
                    </div>

					<br>

					<div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"></span>
                            <img class="img-responsive" src="../../images/service_images/voice/<?php echo $photos_filename; ?>"  alt="<?php
						  if($photos_caption == "")
						  {	echo "No caption.";	}
						  else {  echo html_entity_decode($photos_caption); } ?>">
                        </div>
                    </div>
					
					<hr>
					
					<div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php
							  if($photos_caption == "")
							  {	echo "No caption.";	}
							  else {  echo html_entity_decode($photos_caption); } ?></span>
                            <p><b>Caption</b></p>
                        </div>
                    </div>
					<br>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php echo change_date_medium($photos_creation); ?></span>
                            <p><b>Uploaded on</b></p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php echo change_date_medium($photos_editing); ?></span>
                            <p><b>Edited on</b></p>
                        </div>
                    </div>
					
	                <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><b><a href="moderation_photo.php?id=<?php echo $photos_id; ?>&action=approve">Approve</a></b></span>
                            <p><b><a href="moderation_photo.php?id=<?php echo $photos_id; ?>&action=disapprove">Disapprove</a></b></p>
                        </div>
                    </div>
					<br>
<?php
/*					<div class="row">
                        <div class="col-md-12">
                            <span class="pull-center"><b><a href="moderation_photo.php?id=<?php echo $posts_id; ?>">Next</a></b></span>
                        </div>
                    </div>
*/
?>					
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

<?php require_once('../../Server_Includes/scripts/common_scripts/member_page_common_before_body_end2.php'); ?>

</body>

</html><?php
	$query = 'UPDATE gv_voice_post_image SET voice_image_assessed_by = "moderator" WHERE voice_image_id = ' . $photos_id;
	mysql_query($query, $db) or die(mysql_error($db));
	
	mysql_close($db);
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
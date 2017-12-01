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
			$query = 'UPDATE gv_spotlight_post SET spotlight_post_assessment=1, spotlight_post_assessed_by="' . $_SESSION["username"] . '", spotlight_post_assessed_on=NOW() WHERE spotlight_post_block = 0 AND spotlight_post_id = ' . $get_id;
		}
		elseif($action == 'disapprove')
		{
			$query = 'UPDATE gv_spotlight_post SET spotlight_post_assessment=1, spotlight_post_assessed_by="' . $_SESSION["username"] . '", spotlight_post_assessed_on=NOW(), spotlight_post_block=1 WHERE spotlight_post_block = 0 AND spotlight_post_id = ' . $get_id;
		}
		else{
			//Do nought
		}
		
		mysql_query($query, $db) or die(mysql_error($db));
		
		$query1 = 'UPDATE gv_mod_points SET mod_points=mod_points + 3, last_mod_date=NOW() WHERE member_id = ' . $_SESSION["member_id"];
		mysql_query($query1, $db) or die(mysql_error($db));	
		
	}//End of if($action !== "" && $get_id !== "")
	
	//Get category_name, total active and Inactive posts functions
	require_once('../../Server_Includes/scripts/spotlight_scripts/functions_get_name_for_spotlight.php');
	require_once('../../Server_Includes/scripts/spotlight_scripts/functions_for_spotlight.php');
		
	//Get total unread message function
	require_once('../../Server_Includes/scripts/common_scripts/get_common_functions.php');
	
	//Get Spotlight meta detail
	require_once('../../Server_Includes/scripts/spotlight_scripts/spotlight_meta.php');
	
	//Get Genieverse Spotlight footer function
	require_once('../../Server_Includes/scripts/spotlight_scripts/spotlight_member_footer.php');

	//Get change_date_medium function
	require_once('../../Server_Includes/scripts/common_scripts/date_formats.php');
	
	$query = 'SELECT spotlight_post_id, spotlight_post_created_on, spotlight_post_edited_on, cc_id, spotlight_category_id, spotlight_post_title, spotlight_post_body, spotlight_post_state, spotlight_post_city	FROM	gv_spotlight_post	WHERE	(spotlight_member = 1 AND spotlight_post_block = 0) AND (spotlight_post_assessment = 0 AND spotlight_post_assessed_by = "None")';
	$result = mysql_query($query, $db) or die(mysql_error($db));

	if($result = mysql_query($query))
	{
		if(mysql_num_rows($result) < 1)
		{
			$_SESSION["errand_title"] = "Moderation issue.";
			$_SESSION["errand"] = "There's no unmoderated post.";
			header("Location: dashboard.php");
			exit;
		}
		else {
				$row = mysql_fetch_array($result);
				extract($row);
				$posts_id = $row["spotlight_post_id"];
				$posts_creation = $row["spotlight_post_created_on"];
				$posts_editing = $row["spotlight_post_edited_on"];
				$posts_country_id = $row["cc_id"];
				$posts_category_id = $row["spotlight_category_id"];
				$posts_title = $row["spotlight_post_title"];
				$posts_description = $row["spotlight_post_body"];
				$posts_state = $row["spotlight_post_state"];
				$posts_city = $row["spotlight_post_city"];
		}//End of if(mysql_num_rows($result) < 1)
	}//End of if($result = mysql_query($query))

	$extra_title = "Post Moderation | Genieverse Spotlight - " . $spotlight_title_description;
	$shop_item = "set";
	$meta_keyword = $spotlight_meta_keywords;
	$meta_description = $spotlight_meta_description;

	require_once('../../Server_Includes/scripts/common_scripts/common_member_page_head2.php'); ?><body>
<?php require_once('../../Server_Includes/scripts/spotlight_scripts/spotlight_member_page_header.php'); ?>
    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <div class="col-md-3">
                <p class="lead">Categories</p>
                <div class="list-group">
					<a href="../search.php?category=Abduction" class="list-group-item<?php if($the_category_name == "Abduction"){ echo ' active'; }?>">Abduction</a><?php

		function getCategoryList($the_category_name)
		{
			global $db;
			$query = 'SELECT spotlight_category_id, spotlight_category_name FROM gv_spotlight_category where spotlight_category_lock = 0 ORDER BY spotlight_category_id';
			$result = mysql_query($query, $db) or die(mysql_error($db));

			$row = mysql_fetch_assoc($result);
			
			//Get modified_for_url function
			require_once('../../Server_Includes/scripts/common_scripts/modified_for_url.php');
			
			while($row = mysql_fetch_assoc($result))
			{
				extract($row);
				echo  '
			<a href="../search.php?category=' . modified_for_url($spotlight_category_name, ' ', '-', '') . '" class="list-group-item';
			if($the_category_name == $row["spotlight_category_name"]){ echo ' active'; }
			echo '">' . $spotlight_category_name . '</a>';
			}
		}

		getCategoryList($the_category_name);

				?>
                </div>
            </div>
			
            <div class="col-md-9">
					<p class="lead"><a href="dashboard.php">Spotlight Dashboard</a></p><hr>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><p class="alert alert-info"><b><a href="../../profile.php">My Profile</a></b></p></span>
                            <p class="alert alert-info"><b><a href="../../services.php">Explore Genieverse</a></b></p>
                        </div>
                    </div>
					<br>
					<div class="row">
                        <div class="col-md-12">
                            <span class="pull-right badge"><?php echo moderation_points($_SESSION["member_id"]); ?></span>
                            <p><b>Your Mod Points</b></p>
                        </div>
                    </div>
					
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php echo category_name($posts_category_id); ?></span>
                            <p><b>Category</b></p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php	echo $posts_title; ?></span>
                            <p><b>Title</b></p>
                        </div>
                    </div>
                    <br>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php echo html_entity_decode($posts_description); ?></span>
                            <p><b>Detail</b></p>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php	echo $posts_state; ?> - <?php	echo $posts_city;	?></span>
                            <p><b>Location</b></p>
						</div>
                    </div>
                    <br>
					
                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php  echo change_date_medium($posts_creation); ?></span>
                            <p><b>Created on</b></p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php	echo change_date_medium($posts_editing); ?></span>
                            <p><b>Last edited on</b></p>
                        </div>
                    </div>
					
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><b><a href="moderation_post.php?id=<?php echo $posts_id; ?>&action=approve">Approve</a></b></span>
                            <p><b><a href="moderation_post.php?id=<?php echo $posts_id; ?>&action=disapprove">Disapprove</a></b></p>
                        </div>
                    </div>
					<br>
<?php
/*				<div class="row">
                        <div class="col-md-12">
                            <span class="pull-center"><b><a href="moderation_post.php?id=<?php echo $posts_id; ?>">Next</a></b></span>
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
	$query = 'UPDATE gv_spotlight_post SET spotlight_post_assessed_by = "moderator" WHERE spotlight_post_id = ' . $posts_id;
	mysql_query($query, $db) or die(mysql_error($db));
	
	mysql_close($db);
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
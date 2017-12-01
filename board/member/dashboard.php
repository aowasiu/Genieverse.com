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
	
	//Connect to the database
	require_once('../../Server_Includes/visitordbaccess.php');

	//Get total active and Inactive posts functions
	require_once('../../Server_Includes/scripts/spotlight_scripts/functions_for_spotlight.php');
		
	//Get total unread message(s) function
	require_once('../../Server_Includes/scripts/common_scripts/get_common_functions.php');
	
	//Get Spotlight meta detail
	require_once('../../Server_Includes/scripts/spotlight_scripts/spotlight_meta.php');
	
	//Get Genieverse Spotlight footer function
	require_once('../../Server_Includes/scripts/spotlight_scripts/spotlight_member_footer.php');
	
	//Get change_date_medium function
	require_once('../../Server_Includes/scripts/common_scripts/date_formats.php');
	
	$investigated_posts = investigated_posts_number($_SESSION["member_id"]);
	$noninvestigated_posts = noninvestigated_posts_number($_SESSION["member_id"]);
	$total_posts = $investigated_posts + $noninvestigated_posts;
	
	$unread_general_messages = unread_general_messages();
	
	//In case this member is a moderator, fetch his moderator points from the database
	if($_SESSION["privilege"] !== "1")
	{		
		$mod_points = moderation_points($_SESSION["member_id"]);
	}

	$dashboard = "";

	$extra_title = "Dashboard | Genieverse Spotlight - " . $spotlight_title_description;
	$shop_item = "set";
	$meta_keyword = $spotlight_meta_keywords;
	$meta_description = $spotlight_meta_description;

	require_once('../../Server_Includes/scripts/common_scripts/common_member_page_head2.php'); ?><body>
<?php require_once('../../Server_Includes/scripts/spotlight_scripts/spotlight_member_page_header.php'); ?>
    <!-- Page Content -->
    <div class="container">

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
?>
		<?php
/*        <div class="row">
            <div class="col-sm-4 text-center">
				<a href="marketing.php"><img class="img-responsive" src="images/genieverse_logos/AdSpace_Banner01.png" alt="Advert space"></a><br>
			</div>
			<div class="col-sm-4 text-center">
					<a href="marketing.php"><img class="img-responsive" src="images/genieverse_logos/AdSpace_Banner02.png" alt="Advert space"></a><br>
                </div>
				<div class="col-sm-4 text-center">
					<a href="marketing.php"><img class="img-responsive" src="images/genieverse_logos/AdSpace_Banner03.png" alt="Advert space"></a><br>
                </div>
        </div>
        <!-- /.row -->
        <hr>
*/
?>
		<br/>
        <div class="row">
            <div class="col-md-3">
				<br/>
                <p class="lead">Categories</p>
                <div class="list-group">
					<a href="../search.php?category=abduction" class="list-group-item">Abduction</a><?php

		function getCategoryList()
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
			<a href="../search.php?category=' . modified_for_url($spotlight_category_name, ' ', '-', '') . '" class="list-group-item">' . $spotlight_category_name . '</a>';
			}
		}

		getCategoryList();

				?>
                </div>
            </div>

            <div class="col-md-9">
					<br/>
					<p class="lead">Genieverse Spotlight Dashboard</p><hr>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="btn btn-info pull-right"><b><a href="../../profile.php">My Profile</a></b></span>
                            <p class="btn btn-info"><b><a href="../../services.php">Explore Genieverse</a></b></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"></span>
                            <p><b>My posts</b></p>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right badge"><?php echo $investigated_posts; ?></span>
                            <p><a href="my_posts.php?class=investigated">Investigated matters</a></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right badge"><?php echo $noninvestigated_posts; ?></span>
                            <p><a href="my_posts.php?class=noninvestigated">Non-investigated matters</a></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right badge"><b><?php echo $total_posts; ?></b></span>
                            <p><b><a href="my_posts.php">Total posts</a></b></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"></span>
                            <p><b>Messages</b></p>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right badge"><?php echo $unread_general_messages; ?></span>
                            <p><a href="../../messages.php">Private Messages</a></p>
                        </div>
                    </div>

					<?php
		if($_SESSION["privilege"] !== "1")
		{
?><hr><p style="text-align: center;"><b>Moderation panel</b></p>
					<div class="row">
                        <div class="col-md-12">
                            <span class="pull-right badge"><?php echo $mod_points; ?></span>
                            <p>Moderation points</p>
                        </div>
                    </div>
					<hr/>
					<div class="row">
                        <div class="col-md-12">
                            <span class="btn btn-info pull-right"><a href="moderation_photo.php">Moderate photos</a></span>
                            <p class="btn btn-info"><a href="moderation_post.php">Moderate posts</a></p>
                        </div>
                    </div>
<?php
	} 
?>
                </div>

            </div>

        </div>

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
	mysql_close($db);
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
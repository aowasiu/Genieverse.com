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
			$query = 'UPDATE gv_mall_post SET mall_post_assessment=1, mall_post_assessed_by="' . $_SESSION["username"] . '", mall_post_assessed_on=NOW() WHERE mall_post_block = 0 AND mall_post_id = ' . $get_id;
		}
		elseif($action == 'disapprove')
		{
			$query = 'UPDATE gv_mall_post SET mall_post_assessment=1, mall_post_assessed_by="' . $_SESSION["username"] . '", mall_post_assessed_on=NOW(), mall_post_block=1 WHERE mall_post_block = 0 AND mall_post_id = ' . $get_id;
		}
		else{
			//Do nought
		}
		
		mysql_query($query, $db) or die(mysql_error($db));
		
		$query1 = 'UPDATE gv_mod_points SET mod_points=mod_points + 3, last_mod_date=NOW() WHERE member_id = ' . $_SESSION["member_id"];
		mysql_query($query1, $db) or die(mysql_error($db));	
		
	}//End of if($action !== "" && $get_id !== "")
	
	//Get category_name, total active and Inactive posts functions
	require_once('../../Server_Includes/scripts/mall_scripts/functions_get_name_for_mall.php');
	require_once('../../Server_Includes/scripts/mall_scripts/functions_for_mall.php');
	
	//Get total unread message function
	require_once('../../Server_Includes/scripts/common_scripts/get_common_functions.php');

	//Get Mall meta detail
	require_once('../../Server_Includes/scripts/mall_scripts/mall_meta.php');
	
	//Get Genieverse Mall footer function
	require_once('../../Server_Includes/scripts/mall_scripts/mall_member_footer.php');
	
	//Get change_date_medium and currency_code functions
	require_once('../../Server_Includes/scripts/common_scripts/date_formats.php');
	require_once('../../Server_Includes/scripts/common_scripts/money_matters.php');
	
	$query = 'SELECT mall_post_id, mall_post_created_on, mall_post_edited_on, cc_id, mall_category_id, mall_post_price, mall_post_title, mall_post_description, mall_post_barter, mall_post_age, mall_post_condition, mall_post_state, mall_post_city	FROM	gv_mall_post	WHERE	(mall_member = 1 AND mall_post_block = 0) AND (mall_post_assessment = 0 AND mall_post_assessed_by = "None")';
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
				$posts_id = $row["mall_post_id"];
				$posts_creation = $row["mall_post_created_on"];
				$posts_editing = $row["mall_post_edited_on"];
				$posts_country_id = $row["cc_id"];
				$posts_category_id = $row["mall_category_id"];
				$posts_price = $row["mall_post_price"];
				$posts_title = $row["mall_post_title"];
				$posts_description = $row["mall_post_description"];
				$posts_barter = $row["mall_post_barter"];
				$posts_age = $row["mall_post_age"];
				$posts_condition = $row["mall_post_condition"];
				$posts_state = $row["mall_post_state"];
				$posts_city = $row["mall_post_city"];
		}//End of if(mysql_num_rows($result) < 1)
	}//End of if($result = mysql_query($query))

	$extra_title = "Post Moderation | Genieverse Mall - Free web market ";
	$shop_item = "set";
	$meta_keyword = $mall_meta_keywords;
	$meta_description = $mall_meta_description;

	require_once('../../Server_Includes/scripts/common_scripts/common_member_page_head2.php'); ?><body>
<?php require_once('../../Server_Includes/scripts/mall_scripts/mall_member_page_header.php'); ?>
    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <div class="col-md-3">
                <p class="lead">Categories</p>
                <div class="list-group">
	<a href="../search.php?category=Appliances" class="list-group-item<?php if($the_category_name == "appliances"){ echo ' active'; }?>">Appliances</a><?php

		function getCategoryList()
		{
			global $db;
			$query = 'SELECT mall_category_id, mall_category_name FROM gv_mall_category where mall_category_lock = 0 ORDER BY mall_category_id';
			$result = mysql_query($query, $db) or die(mysql_error($db));

			$row = mysql_fetch_assoc($result);
			
			//Get modified_for_url function
			require_once('../../Server_Includes/scripts/common_scripts/modified_for_url.php');
			
			while($row = mysql_fetch_assoc($result))
			{
							extract($row);
							echo  '
			<a href="../search.php?category=' . modified_for_url($mall_category_name, ' ', '-', '') . '" class="list-group-item';
			if($the_category_name == $row["mall_category_name"]){ echo ' active'; }
			echo '">' . $mall_category_name . '</a>';
			}
		}

		getCategoryList();

				?>
                </div>
            </div>
			
            <div class="col-md-9">
					<p class="lead"><a href="dashboard.php">Mall Dashboard</a></p><hr>
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
                    
<?php 
	if($posts_category_id == 16 || $posts_category_id == 23)
	{
		echo "";
	}
	else{ ?>                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php	echo currency_code($posts_country_id, $posts_price); ?></span>
                            <p><b>Price</b></p>
                        </div>
                    </div>
                    <br>
<?php } ?>                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php echo html_entity_decode($posts_description); ?></span>
                            <p><b>Description</b></p>
                        </div>
                    </div>
                    <br>
<?php
	if($posts_category_id == 10 || $posts_category_id == 16)
	{
		echo "";
	}
	elseif($posts_category_id == 23)
	{
		echo "";
	}
	else {	?>                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php echo html_entity_decode($posts_barter); ?></span>
                            <p><b>Barter</b></p>
                        </div>
                    </div>
                    <br>
<?php } ?>					                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php echo $posts_age; ?></span>
                            <p><b>Age</b></p>
                        </div>
                    </div>
                    <br>
<?php 
	$hidden_condition = array(13, 17, 20, 22, 23, 24);
	$common_condition = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 11, 12, 14, 18, 19, 21);
	$unique_condition = array(10, 15, 16);
	
	function dynamic_condition($data)
	{
		if($data == 16)
		{
			$dynamic_condition = 'Status';
		}
		elseif($data == 10)
		{
			$dynamic_condition = 'Duration';
		}
		else{
				$dynamic_condition = 'Condition';
		}
		return $dynamic_condition;
	}

	if(in_array($posts_category_id, $hidden_condition))
	{ echo ""; }
	
	if(in_array($posts_category_id, $unique_condition))
	{
		if($posts_category_id == 10)
		{ ?>                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php	echo $posts_condition; ?></span>
                            <p><b><?php echo ucfirst(dynamic_condition($posts_category_id)); ?></b></p>
                        </div>
                    </div>
                    <br><?php
		}
		
		if($posts_category_id == 15)
		{ 
?>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php	echo $posts_condition; ?></span>
                            <p><b><?php echo ucfirst(dynamic_condition($posts_category_id)); ?></b></p>
                        </div>
                    </div>
                    <br>
<?php
		}
		
		if($posts_category_id == 16)
		{
?>                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php	echo $posts_condition; ?></span>
                            <p><b><?php echo ucfirst(dynamic_condition($posts_category_id)); ?></b></p>
                        </div>
                    </div>
                    <br>
<?php
		}
	}
	
	if(in_array($posts_category_id, $common_condition))
	{
?>                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-right"><?php	echo $posts_condition; ?></span>
                            <p><b>Condition</b></p>
                        </div>
                    </div>
                    <br><?php 
	 }
?>                    <div class="row">
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

<?php require_once('../../Server_Includes/scripts/common_scripts/member_page_common_before_body_end.php'); ?>

</body>

</html><?php
	$query = 'UPDATE gv_mall_post SET mall_post_assessed_by = "moderator" WHERE mall_post_id = ' . $posts_id;
	mysql_query($query, $db) or die(mysql_error($db));
	
	mysql_close($db);
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
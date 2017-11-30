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

	$get_id = (isset($_GET["id"])) ? $_GET["id"] : 0;
	$post_id = (isset($_POST["id"])) ? $_POST["id"] : $get_id;
	$post_name = (isset($_POST["name"])) ? $_POST["name"] : "";
	$special_button = 'Mark this post as Activated.';
	$delete_button = 'Delete all photos';
	$delete_button2 = 'Delete this post';
	$service_tagPlus = 'active';
	$service_tagNegative = 'inactive';

	//Errors definition
	$errors = array();
	
	if($post_id == 0)
	{
		//Take this visitor to the log in page because he's not logged in
		$_SESSION["errand_title"] = "Sorry there's a problem";
		$_SESSION["errand"] = "The post you requested does not exist.";
		header("Location: dashboard.php");
		exit;
	}
	
	//Connect to the database
	require_once('../../Server_Includes/visitordbaccess.php');

	//Get page functions
	require_once('../../Server_Includes/scripts/mall_scripts/functions_get_page_features_for_mall.php');
	
	//Get function to check if post requested is this member's post
	require_once('../../Server_Includes/scripts/mall_scripts/functions_for_mall.php');
	
	$the_members_id = is_post_id_members_own($post_id);
	if($the_members_id !== $_SESSION["member_id"])
	{
		$_SESSION["errand"] = "You don't have any post as such.";
		header("Location: dashboard.php");
		exit;
	}

	//Get image core directory
	require_once('../../Server_Includes/image_directory_core.php');

	//Path to images directory
	$image_directory = $image_directory_core . '/images/service_images/mall/';
		
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["delete"] == "Delete this photo")
	{
		$existing_images = scandir($image_directory);
		
		if(!in_array($post_name, $existing_images))
		{
			//Hurray, now redirect this member
			$_SESSION["keep_session"] = "";
			$_SESSION["errand_title"] = "No such photo";
			$_SESSION["errand"] = "The photo you refer to doesn't exist." . "<br><a href='dashboard.php'>Click here to continue.</a>";
			header("Location: ../../issues.php");
			exit;
		}
		else {
				unlink($image_directory . '/' . $post_name);
		}
		
		$query = 'DELETE FROM gv_mall_post_image WHERE mall_image_filename = "' . mysql_real_escape_string($post_name, $db) . '" AND mall_post_id = ' . mysql_real_escape_string($post_id, $db);
		mysql_query($query, $db) or die (mysql_error($db));

		$_SESSION["keep_session"] = "";
		$_SESSION["errand_title"] = "Successful removal";
		$_SESSION["errand"] = "The photo has been deleted.";
		header("Location: my_post.php?id=$post_id");
		exit;
	}
	
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["delete_all"] == "Delete all photos")
	{
		$query = 'SELECT mall_image_filename FROM gv_mall_post_image WHERE mall_post_id = ' .mysql_real_escape_string($post_id, $db);
		$result = mysql_query($query, $db) or die (mysql_error($db));
		while($row = mysql_fetch_assoc($result))
		{
			extract($row);
			
			if(mysql_num_rows($result) > 0)
			{
				$existing_images = scandir($image_directory);
				
				if(in_array($row["mall_image_filename"], $existing_images))
				{
					unlink($image_directory . '/' . $row["mall_image_filename"]);
				}
			}
		}
		
		$query = 'DELETE FROM gv_mall_post_image WHERE mall_post_id = ' . mysql_real_escape_string($post_id, $db);
		mysql_query($query, $db) or die (mysql_error($db));

		$_SESSION["keep_session"] = "";
		$_SESSION["errand_title"] = "Successful removal";
		$_SESSION["errand"] = "The photos have been deleted.";
		header("Location: my_post.php?id=$post_id");
		exit;
	}
	
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["delete"] == "Delete this post")
	{
		$query = 'SELECT mall_image_filename FROM gv_mall_post_image WHERE mall_post_id = ' . mysql_real_escape_string($post_id, $db);
		$result = mysql_query($query, $db) or die (mysql_error($db));
		while($row = mysql_fetch_assoc($result))
		{
			extract($row);
			
			if(mysql_num_rows($result) > 0)
			{
				$existing_images = scandir($image_directory);
				
				if(in_array($row["mall_image_filename"], $existing_images))
				{
					unlink($image_directory . '/' . $row["mall_image_filename"]);
				}
			}
		}
		
		$query = 'DELETE FROM gv_mall_post_image WHERE mall_post_id = ' . mysql_real_escape_string($post_id, $db);
		mysql_query($query, $db) or die (mysql_error($db));

		$query = 'DELETE FROM gv_mall_post WHERE mall_post_id = ' . mysql_real_escape_string($post_id, $db) . ' AND member_id = ' . $_SESSION["member_id"];
		mysql_query($query, $db) or die (mysql_error($db));

		$_SESSION["keep_session"] = "";
		$_SESSION["errand_title"] = "Successful removal";
		$_SESSION["errand"] = "The post has been deleted.";
		header("Location: my_posts.php");
		exit;
	}
	
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["$service_tagPlus"] == $special_button)
	{
		$query = 'UPDATE gv_mall_post SET mall_post_activation=1, mall_post_activated_on=NOW() WHERE mall_post_id = ' . mysql_real_escape_string($post_id, $db);
		mysql_query($query, $db) or die(mysql_error($db));
		header("Location: my_post.php?id=$post_id");
		exit;
	}
	
	//Get name functions
	require_once('../../Server_Includes/scripts/mall_scripts/functions_get_name_for_mall.php');
		
	//Get Mall meta data and footer
	require_once('../../Server_Includes/scripts/mall_scripts/mall_meta.php');
	require_once('../../Server_Includes/scripts/mall_scripts/mall_member_footer.php');
	
	//Get change_date_medium function
	require_once('../../Server_Includes/scripts/common_scripts/date_formats.php');
	
	//Get currency_code function
	require_once('../../Server_Includes/scripts/common_scripts/money_matters.php');
	
	$query = 'SELECT 
			mall_post_id, mall_post_activation, mall_post_created_on, mall_post_edited_on, cc_id, mall_category_id, mall_post_price, mall_post_youtube, mall_post_title, mall_post_description, mall_post_barter, mall_post_age, mall_post_condition, mall_post_state, mall_post_city, mall_post_contact_name, mall_post_contact_address, mall_post_contact_phone, mall_post_contact_email, mall_post_contact_website	FROM	gv_mall_post	WHERE	(mall_member = 1 AND mall_post_block = 0) AND (mall_post_id = ' . mysql_real_escape_string($get_id, $db) . ' AND member_id = ' . $_SESSION["member_id"] . ')';
	$result = mysql_query($query, $db) or die (mysql_error($db));

	if(mysql_num_rows($result) < 1)
	{
		$_SESSION["errand_title"] = "Sorry, there's an issue.";
		$_SESSION["errand"] = "The post you requested does not exist.";
		header("Location: dashboard.php");
		exit;
	}

	$row = mysql_fetch_array($result);
	extract($row);
	$the_category_name = category_name( $row["mall_category_id"]);

	$shop_item = "set";
	$thumbnail_gallery = "set";
	$meta_keyword = $mall_meta_keywords;
	$meta_description = $mall_meta_description;
	
	$fancy_box = "Fancy box here please.";
	
	$extra_title =  $row["mall_post_title"] . " | My posts | Genieverse Mall - Free web market ";
		
	require_once('../../Server_Includes/scripts/common_scripts/common_member_page_head2.php'); ?><body>
<?php require_once('../../Server_Includes/scripts/mall_scripts/mall_member_page_header.php'); ?>
    <!-- Page Content -->
    <div class="container">
<?php
/*
        <div class="row">
            <div class="col-sm-4 text-center">
				<a href="../../marketing.php"><img class="img-responsive" src="../../images/genieverse_logos/AdSpace_Banner01.png" alt="Advert space"></a><br>
			</div>
			<div class="col-sm-4 text-center">
					<a href="../../marketing.php"><img class="img-responsive" src="../../images/genieverse_logos/AdSpace_Banner02.png" alt="Advert space"></a><br>
                </div>
				<div class="col-sm-4 text-center">
					<a href="../../marketing.php"><img class="img-responsive" src="../../images/genieverse_logos/AdSpace_Banner03.png" alt="Advert space"></a><br>
                </div>
        </div>
        <!-- /.row -->*/
?>
        <hr>
        <div class="row">

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
				<?php
					echo '<ul class="alert alert-danger">';
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
		
            <div class="col-md-3">
                <p class="lead">Categories</p>
                <div class="list-group">
					<a href="../search.php?category=Appliances" class="list-group-item<?php if($the_category_name == "appliances"){ echo ' active'; }?>">Appliances</a><?php
			
		function getCategoryList($the_category_name)
		{
			global $db;
			$query = 'SELECT mall_category_name FROM gv_mall_category where mall_category_lock = 0 ORDER BY mall_category_id';
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

		getCategoryList($the_category_name);
					
					?>
                </div>
            </div>

            <div class="col-md-9">
               <div class="thumbnail">
                    <div class="caption-full">
						<a class="btn btn-default pull-right" href="new_post_photo.php?id=<?php echo $post_id; ?>">Upload photos</a>
						<a class="btn btn-default" href="my_posts.php">Back to My Posts</a>
					</diV>
				</diV>
                <div class="thumbnail">
                    <div class="caption-full">
                        <h4 class="pull-right"><?php
						echo currency_code($row["cc_id"], $row["mall_post_price"]);
						echo $row["mall_post_price"]; ?></h4>
                        <h3><?php echo $row["mall_post_title"]; ?></h3>
						<?php if(!empty($row["mall_post_youtube"])){ ?><iframe class="col-sm-8 col-lg-12" title="YouTube video player" class="youtube-player" type="text/html" width="425" height="349" src="http://www.youtube.com/embed/<?php echo $row["mall_post_youtube"]; ?>"
						frameborder="0" allowFullScreen></iframe>
						<?php
							}
						?>
<br>
						<hr><b>Description:</b><hr>
						<?php echo html_entity_decode($row["mall_post_description"]); ?>
						<?php
							if($row["mall_category_id"] == 10 || $row["mall_category_id"] == 16)
							{
								echo "";
							}
							elseif($row["mall_category_id"] == 23)
							{
								echo "";
							}
							else{
									echo '<br><br>
						<hr><b>Barter:</b><hr><br>
						' . html_entity_decode($row["mall_post_barter"]) . '' . "\n";
							}
							echo '
						<br><br>
						<hr><b>Age:</b><hr>
						' . $row["mall_post_age"] . '';
						
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

	if(in_array($row["mall_category_id"], $hidden_condition))
	{
		echo "";
	}
	
	if(in_array($row["mall_category_id"], $unique_condition))
	{
		if($row["mall_category_id"] == 10)
		{ ?>
						<br>
						<hr><b><?php echo ucfirst(dynamic_condition($row["mall_category_id"])); ?>:</b><hr>
						<p><?php	echo $row["mall_post_condition"]; ?></p>
		<?php
		}
		
		if($row["mall_category_id"] == 15)
		{ 
?>
						<br>
						<hr><b><?php echo ucfirst(dynamic_condition($row["mall_category_id"])); ?>:</b><hr>
						<p><?php	echo $row["mall_post_condition"]; ?></p>
<?php
		}
		
		if($row["mall_category_id"] == 16)
		{
?>
						<br><br>
						<hr><b><?php echo ucfirst(dynamic_condition($row["mall_category_id"])); ?>:</b><hr>
						<p><?php	echo $row["mall_post_condition"]; ?></p>
<?php
		}
	}
	
	if(in_array($row["mall_category_id"], $common_condition))
	{
?>
						<br><br>
						<hr><b>Condition:</b><hr>
						<p><?php echo $row["mall_post_condition"]; ?></p>
<?php 
	}
?>
						<br>
						<hr><b>Country:</b><hr>
						<p><?php echo country_name($row["cc_id"]); ?></p>
						<br>
						<hr><b>Location:</b><hr>
						<p><?php echo $row["mall_post_city"];
						if($row["mall_post_city"] !== ""){ echo ", "; }
						echo $row["mall_post_state"];	?></p>
						<br>
						<hr><b>Contact:</b><hr>
						<p><?php echo $row["mall_post_contact_name"]; ?></p>
						<br>
						<hr><b>Address:</b><hr>
						<p><?php echo $row["mall_post_contact_address"]; ?></p>
						<br>
						<hr><b>Phone:</b><hr>
						<p class="badge"><?php echo $row["mall_post_contact_phone"];	?></p>
						<br>
						<hr><b>Email:</b><hr>
						<p><?php echo Protected_Email($row["mall_post_contact_email"]); ?></p>
						<br>
						<hr><b>Website:</b><hr>
						<p><?php echo $row["mall_post_contact_website"]; ?></p>
						<br>
						<hr><b>You created this post on:</b><hr>
						<p><?php	echo change_date_long($row["mall_post_created_on"]); ?></p>
						<br>
						<hr><b>Your last edit was on:</b><hr>
						<p><?php	echo change_date_long($row["mall_post_edited_on"]); ?></p>
						<br>
						<hr>
                    </div>
<?php
	if($row["mall_post_activation"] == 0)
	{
?>
					<div class="caption-full">
                        <h5 class="pull-right"></h5>
                        <h5><form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
								<input type="hidden" name="id" value="<?php echo $post_id; ?>" />
								<input class="btn btn-info" type="submit" name="activate" value="Activate this post" />
							   </form></h5>
						<hr>
					</div>
<?php 
	 }
	 else {
?>					<div class="caption-full">
                        <h5 class="pull-right"></h5>
                        <h5><p class="alert alert-info">This post is Active</p></h5>
						<hr>
					</div>
<?php
	}
?>					<div class="caption-full">
                        <h5 class="pull-right"><form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
								<input type="hidden" name="id" value="<?php echo $post_id; ?>">
								<input type="hidden" name="name" value="<?php echo $post_id; ?>">
								<input class="btn btn-info" type="submit" name="delete" value="Delete this post">
							   </form></h5>
							   <h5><a href="update_post.php?id=<?php echo $post_id; ?>" class="btn btn-info">Edit this post</a></h5>
					</div>
                </div>
			</div>
			
			<div class="col-lg-12">
				<h1 class="page-header">Photo gallery</h1>
			</div>
<?php
					
					//Declare photo fetching function
					function get_post_photos($data, $data2, $data3)
					{
						global $db;
						$query = 'SELECT	mall_image_id, mall_image_created_on, mall_image_edited_on, mall_image_caption, mall_image_filename	FROM	gv_mall_post_image	WHERE	(mall_image_block = 0 AND mall_post_id = ' . mysql_real_escape_string($data, $db) . ')';
						$result = mysql_query($query, $db) or die (mysql_error($db));
						
						function get_formated_date($data)
						{
							$time = strtotime($data);
							$date_format = date("M t, Y. g:ia", $time);
							return $date_format;
						}

						if(mysql_num_rows($result) < 1)
						{
							echo '<div class="col-lg-12 col-md-12 col-xs-12">
                <h5>Your post has no photo</h5>
				<a class="pull-right btn btn-info" href="new_post_photo.php?id=' . $data . '">Upload photos</a>
            </div>' . "\n";
						}
						else{
								while($row = mysql_fetch_assoc($result))
								{
?>
			<div class="col-lg-3 col-md-4 col-xs-6 thumb">
				<a href="../../images/service_images/mall/<?php echo $row["mall_image_filename"]; ?>" class="fancybox thumbnail" <?php if(mysql_num_rows($result) > 1){ echo ' rel="gallery"';}?> title="<?php
				if($row["mall_image_caption"] == ""){ echo "No caption"; }
				else{ echo $row["mall_image_caption"]; } ?>"><img <?php // class="img-responsive" ?> src="../../images/service_images/mall/<?php echo $row["mall_image_filename"]; ?>"  style="height:30%; width:30%;" alt="<?php echo $data2; ?>"></a>
				<p class="alert alert-info text-center"><?php if($row["mall_image_caption"] == ""){ echo "No caption"; } else { echo $row["mall_image_caption"]; } ?></p>
				<form class="text-center" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
					<input type="hidden" name="id" value="<?php echo $data3; ?>" />
					<input type="hidden" name="name" value="<?php echo $row["mall_image_filename"]; ?>" />
					<input class="btn btn-default" type="submit" name="delete" value="Delete this photo" />
				</form>
				<p class="text-center"><a class="btn btn-default" href="update_post_photo.php?id=<?php echo $data; ?>&name=<?php echo $row["mall_image_filename"]; ?>">Change this photo</a></p>
			   <p><b>Created on:</b> <?php echo get_formated_date($row["mall_image_created_on"]); ?></p>
			   <p><b>Edited on:</b> <?php echo get_formated_date($row["mall_image_edited_on"]); ?></p>
			</div>
			
<?php
								}
								if(mysql_num_rows($result) == 1)
								{
									//
								}
								else{
										//
								}		
						}
					}
					
					//Execute photo fetching function
					echo get_post_photos($get_id, $row["mall_post_title"], $post_id);
				?>
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

<?php require_once('../../Server_Includes/scripts/common_scripts/common_member_page_before_body_end.php'); ?>

</body>

</html><?php
	mysql_close($db);
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
<?php

	//Get custom error function script 
	require_once('../Server_Includes/scripts/common_scripts/feature_error_message.php');

	//Set default value for GET global variable ID
	$get_id = (isset($_GET["id"])) ? $_GET["id"] : 0;

	if($get_id == 0)
	{
		//Take this visitor to the log in page because he's not logged in
		header("Location: home.php");
		exit;
	}
	
	//Connect to the database
	require_once('../Server_Includes/visitordbaccess.php');

	//Get page functions
	require_once('../Server_Includes/scripts/voice_scripts/functions_get_page_features_for_voice.php');
	
	//Get name functions
	require_once('../Server_Includes/scripts/voice_scripts/functions_get_name_for_voice.php');

	//Get Voice meta details and footer script
	require_once('../Server_Includes/scripts/voice_scripts/voice_meta.php');	
	require_once('../Server_Includes/scripts/voice_scripts/voice_footer.php');
	
	//Get change_date_medium function
	require_once('../Server_Includes/scripts/common_scripts/date_formats.php');

	//This function gets the name of a member
	function member_username($data)
	{
		global $db;
		$query = 'SELECT username FROM gv_members WHERE member_id = ' . mysql_real_escape_string($data);
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return ucfirst($username);
	}
	
	$query = 'SELECT voice_post_id, voice_post_view_count, member_id, voice_post_settlement, voice_post_settled_on, voice_post_edited_on, cc_id, voice_category_id, voice_post_youtube, voice_post_title, voice_post_body, voice_post_state, voice_post_city, voice_post_contact_name, voice_post_contact_phone, voice_post_contact_email, voice_post_contact_website	FROM gv_voice_post	WHERE	(voice_member = 1 AND voice_post_block = 0) AND (voice_post_id = ' . mysql_real_escape_string($get_id, $db) . ')';
	$result = mysql_query($query, $db) or die (mysql_error($db));

	if(mysql_num_rows($result) < 1)
	{
		$_SESSION["errand_title"] = "Sorry, there's an issue.";
		$_SESSION["errand"] = "The post you requested does not exist.";
		header("Location: ../issues.php");
		exit;
	}

	$row = mysql_fetch_array($result);
	extract($row);
	
	$the_category_name = category_name( $row["voice_category_id"]);

	$the_poster = member_username($row["member_id"]);

	$queryP = 'SELECT voice_image_filename FROM gv_voice_post_image WHERE voice_post_id = ' . $row["voice_post_id"]  . ' ORDER BY voice_image_id DESC LIMIT 1';
	$resultP =  mysql_query($queryP, $db) or die(mysql_error($db));
						
	if(mysql_num_rows($resultP) !== 0)
	{
		while($rowP = mysql_fetch_assoc($resultP))
		{
			extract($rowP);
			$post_image = $rowP["voice_image_filename"]; 
		}
	}
	else{
		$post_image = 'default.png';
	}

	//Get the total view incrementing function definition
	require_once('../Server_Includes/scripts/common_scripts/total_view_incrementing.php');

	//Get the poster's total posts count function definition
	require_once('../Server_Includes/scripts/common_scripts/posters_total_post_count.php');

	//Get this_posts_total_comments function
	require_once('../Server_Includes/scripts/voice_scripts/this_posts_total_comments.php');
	
	//Initiate total_view_incrementing function
	total_view_incrementing($row["voice_post_id"], 'gv_voice_post', 'voice_post_view_count', 'voice_post_id');

	$total_comments = this_posts_total_comments($row["voice_post_id"]);

	//Set template page name
	$shop_item = "set";

	//Set meta details
	$meta_keyword = $voice_meta_keywords;
	$meta_description = $voice_meta_description;

	//Set Fancybox plugin for use on this page
	$fancy_box = "Use Fancybox";

	//Set extra title
	$extra_title =  $row["voice_post_title"] . " | Genieverse Voice - Tell the world what's happening. ";

	//Get page head element properties
	require_once('../Server_Includes/scripts/common_scripts/common_head2.php'); ?><body>
<?php require_once('../Server_Includes/scripts/voice_scripts/voice_outer_page_header.php'); ?>

    <!-- Page Content -->
    <div class="container">
<?php
/*        <div class="row">
            <div class="col-sm-4 text-center">
				<a href="../marketing.php"><img class="img-responsive" src="../images/genieverse_logos/AdSpace_Banner01.png" alt="Advert space"></a><br>
			</div>
			<div class="col-sm-4 text-center">
					<a href="../marketing.php"><img class="img-responsive" src="../images/genieverse_logos/AdSpace_Banner02.png" alt="Advert space"></a><br>
                </div>
				<div class="col-sm-4 text-center">
					<a href="../marketing.php"><img class="img-responsive" src="../images/genieverse_logos/AdSpace_Banner03.png" alt="Advert space"></a><br>
                </div>
        </div>
        <!-- /.row -->
        <hr>*/
?>        <div class="row">

            <div class="col-md-3">
                <p class="lead">Categories</p>
                <div class="list-group">
					<a href="search.php?category=Head-Of-State" class="list-group-item">Head of State</a><?php

		function getCategoryList($the_category_name)
		{
			global $db;
			$query = 'SELECT voice_category_id, voice_category_name FROM gv_voice_category where voice_category_lock = 0 ORDER BY voice_category_id';
			$result = mysql_query($query, $db) or die(mysql_error($db));

			$row = mysql_fetch_assoc($result);

			//Get modified_for_url function
			require_once('../Server_Includes/scripts/common_scripts/modified_for_url.php');

			while($row = mysql_fetch_assoc($result))
			{
				extract($row);
				echo  '
			<a href="search.php?category=' . modified_for_url($voice_category_name, ' ', '-', '') . '" class="list-group-item';
			if($the_category_name == $row["voice_category_name"]){ echo ' active'; }
			echo '">' . $voice_category_name . '</a>';
			}
		}

		getCategoryList($the_category_name);

				?>
                </div>
				<div>
					<h3>Post's statistics</h3>
					<table>
						<tr>
							<td class="col-lg-2"><p>Total views</p></td>
							<td class="col-lg-1"><p><?php echo $row["voice_post_view_count"] + 1; ?></p></td>
						</tr>
						<tr>
							<td class="col-lg-2"><p><a href="member_posts.php?id=<?php echo $row["member_id"] ?>"><?php echo ucfirst($the_poster); ?>'s other Voice posts</a></p></td>
							<td class="col-lg-1"><p><?php echo posters_total_post_count($row["member_id"], 'gv_voice_post', 'voice_post_id', 'member_id'); ?></p></td>
						</tr>
						<tr>
							<td class="col-lg-2"><p><a href="comments.php?id=<?php echo $get_id; ?>">Post's total comments</a></p></td>
							<td class="col-lg-1"><p><?php echo $total_comments; ?></p></td>
						</tr>
					</table><br/>
				</div>
            </div>

            <div class="col-md-9">
                <div class="thumbnail">
                    <div class="caption-full">
                        <h4 class="pull-right"></h4>
                        <h3><?php echo $row["voice_post_title"]; ?></h3> 
<p class="col-sm-12 col-md-12 col-lg-12">Posted by: <a href="../member_profile.php?id=<?php echo $row["member_id"]; ?>"><b><?php echo $the_poster; ?></b></a></p>
 
						<div><img src="../images/service_images/voice/<?php echo $post_image; ?>"/><hr></div>
						<?php
							if(!empty($row["voice_post_youtube"])){ ?><div class="panel panel-info">
							<div class="panel-heading">
								<p class="panel-title">YouTube video</p>
								<div class="panel-body">
									<div class="embed-responsive embed-responsive-16by9 hidden-xs">
										<iframe class="col-sm-8 col-lg-12" title="YouTube video player" class="youtube-player embed-responsive-item" type="text/html" width="425" height="349" src="http://www.youtube.com/embed/<?php echo $row["voice_post_youtube"]; ?>"
										frameborder="0" allowFullScreen></iframe>
									</div>
								</div>
							</div>
						</div>
						<?php
							}
						?>
						<hr><b>Detail:</b><hr>
						<?php echo html_entity_decode($row["voice_post_body"]); ?>
						
						<br>
						<hr>
						<p><b>Country:</b> <?php echo country_name($row["cc_id"]); ?></p>
						<br>
						<hr><b>Location:</b>
						<p><?php echo $row["voice_post_city"]; ?>, <?php	echo $row["voice_post_state"];	?></p>
						<br>
						<hr><b>Contact:</b><hr>
						<p><?php echo $row["voice_post_contact_name"]; ?></p>
						<br>
						<hr><b>Address:</b><hr>
						<p><?php echo $row["voice_post_contact_address"]; ?></p>
						<br>
						<hr><b>Phone:</b><hr>
						<p class="badge"><?php echo $row["voice_post_contact_phone"];	?></p>
						<br>
						<hr><b>Email:</b><hr>
						<p><?php echo Protected_Email($row["voice_post_contact_email"]); ?></p>
						<br>
						<hr><b>Website:</b><p><b><a class="btn btn-default" href="<?php echo $row["voice_post_contact_website"]; ?>"><?php if($the_poster == 'Affiliate'){ echo 'Buy Now'; }else{ echo 'Visit page'; } ?></a></b></p>
						<hr><b>Last edited on:</b><hr>
						<p><?php	echo change_date_long($row["voice_post_edited_on"]); ?></p>
						<br>
						<p class="col-lg-4" style="text-align: center;"><b><a class="btn btn-default" href="comments.php?id=<?php echo $row["voice_post_id"]; ?>">View All Comments</a></b></p>
						<p class="col-lg-4" style="text-align: center;"><b><a class="btn btn-default" href="comments.php?id=<?php echo $row["voice_post_id"]; ?>">Post a Comment</a></b></p>
						<p class="col-lg-4" style="text-align: center;"><b><a class="btn btn-default" href="../thanks_for_reporting.php?id=<?php echo $row["voice_post_id"]; ?>&db=voice">Report this post</a></b></p>
                    </div>
                </div>
			</div>
			
			<div class="col-lg-12">
				<h1 class="page-header">Photo gallery</h1>
			</div>
			<div id="blueimp-gallery" class="blueimp-gallery">
				<!-- The container for the modal slides -->
				<div class="slides"></div>
				<!-- Controls for the borderless lightbox -->
				<h3 class="title"></h3>
				<a class="prev"></a>
				<a class="next"></a>
				<a class="close"></a>
				<a class="play-pause"></a>
				<ol class="indicator"></ol>
				<!-- The modal dialogue, which will be used to wrap the lightbox content -->
				<div class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="btn btn-default pull-left prev">
									<li class="glyphicon glyphicon-chevron-left"></i>
									Previous
								</button>
								<button type="button" class="btn btn-primary next">
									Next
									<li class="glyphicon glyphicon-chevron-right"></i>
								</button>
							</div>
						</div>	
					</div>
				</div>
			</div>

 <?php

					
					//Declare photo fetching function
					function get_post_photos($data, $data2)
					{
						global $db;
						$query = 'SELECT	voice_image_id, voice_image_created_on, voice_image_edited_on, voice_image_caption, voice_image_filename	FROM gv_voice_post_image	WHERE	(voice_image_block = 0 AND voice_post_id = ' . mysql_real_escape_string($data, $db) . ')';
						$result = mysql_query($query, $db) or die (mysql_error($db));
						
						if(mysql_num_rows($result) < 1)
						{
							echo '<div class="col-lg-12 col-md-12 col-xs-12">
                <p>This post has no photo</p>
            </div>' . "\n";
						}
						else{
								while($row = mysql_fetch_assoc($result))
								{
?>
			
			<div class="col-lg-3 col-md-4 col-xs-6 thumb">
				<br>
				<a href="../images/service_images/voice/<?php echo $row["voice_image_filename"]; ?>" class="fancybox thumbnail"  rel="gallery" title="<?php
				if($row["voice_image_caption"] == ""){ echo "No caption"; }
				else{ echo $row["voice_image_caption"]; } ?>">
					<img class="img-responsive" src="../images/service_images/voice/<?php echo $row["voice_image_filename"]; ?>" style="height:30%; width:30%;" alt="<?php echo $data2; ?>">
				</a>
				<p class="alert alert-info text-center"><?php if($row["voice_image_caption"] == ""){ echo "No caption"; } else { echo $row["voice_image_caption"]; } ?></p>
				<p class="text-center"><a class="btn btn-default" href="../images/service_images/voice/<?php echo $row["voice_image_filename"]; ?>" class="fancybox" rel="gallery" title="<?php echo $row["voice_image_caption"]; ?>">Enlarge</a></p>
				<p class="text-center"><a class="btn btn-default" href="../thanks_for_reporting.php?id=<?php echo $row["voice_image_filename"]; ?>&db=voice_photo">Report this photo</a></p>
			</div>
<?php
								}
							
								if(mysql_num_rows($result) == 1)
								{
?>	 		<script>
				$("a[href$='.jpg'],a[href$='.jpeg'],a[href$='.png'],a[href$='.gif']").attr('rel', 'gallery').fancybox();
			</script>
<?php
								}
								
								if(mysql_num_rows($result) > 1){
?>			<script>
				$("a[href$='.jpg'],a[href$='.jpeg'],a[href$='.png'],a[href$='.gif']").attr('rel', 'gallery').fancybox();
			</script>
<?php
								}		
						}
					}
					
					//Execute photo fetching function
					echo get_post_photos($get_id, $row["voice_post_title"]);
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

<?php require_once('../Server_Includes/scripts/common_scripts/common_before_body_end2.php'); ?>

</body>

</html>
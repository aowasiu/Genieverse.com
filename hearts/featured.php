<?php

	$mobile_view_menu_colour = '#000000';

	//Get custom error function script
	require_once('../Server_Includes/scripts/common_scripts/feature_error_message.php');

	//Connect to the database
	require_once('../Server_Includes/visitordbaccess.php');

	//Get custom error function script
	require_once('../Server_Includes/scripts/hearts_scripts/feature_photo.php');
	require_once('../Server_Includes/scripts/common_scripts/country_name.php');

	$extra_title = 'Featured Profiles' . " | \n";

	//Get meta details
	require_once('../Server_Includes/scripts/hearts_scripts/hearts_meta.php');

	//Set extra title	
	$extra_title .= "Genieverse Hearts - Meet the right person | " . $hearts_title_description;

	//Set meta details
	$meta_keyword = $hearts_meta_keywords;
	$meta_description = $hearts_meta_description;

	//Set page background colour
	$header_background_color = '#FFF'; //#FFF(white) #000(black) #EEE(light ash) none(default background)

	$featured = 'set';
	
	$page_contents_title = "Featured Profiles";
	
	//Get page head element properties
	require_once('../Server_Includes/scripts/hearts_scripts/hearts_outer_page_head.php'); ?>
	<body>
	<?php require_once('../Server_Includes/scripts/hearts_scripts/hearts_outer_page_header.php'); ?>
	
    <!-- Page Content -->
    <div class="container">
	
		<div class="row">
            <div class="box col-sm-12">
				<div class="row">	
					<div class="col-md-12">
						<h3 class="alert alert-info"></h3>
					</div>
				</div>
			</div>
		</div>
	
		<!-- /.row -->
        <div class="row">
            <div class="col-sm-4 text-center">
				<a href="marketing.php"><img class="img-responsive" src="../images/genieverse_logos/AdSpace_Banner01.png" alt="Advert space"></a><br>
			</div>
			<div class="col-sm-4 text-center">
					<a href="marketing.php"><img class="img-responsive" src="../images/genieverse_logos/AdSpace_Banner02.png" alt="Advert space"></a><br>
                </div>
				<div class="col-sm-4 text-center">
					<a href="marketing.php"><img class="img-responsive" src="../images/genieverse_logos/AdSpace_Banner03.png" alt="Advert space"></a><br>
                </div>
        </div>
        <!-- /.row -->
        <hr>
		
		<div class="row col-md-12">
            <div class="col-lg-8 col-lg-offset-3">
				<div>
				<h3><?php echo $page_contents_title; ?></h3>
				<br>
				</div>
			</div>
		</div>

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="http://placehold.it/800x500" alt="">
                    <div class="caption">
                        <h3>Categories</h3>
						<ul class="list-unstyled">
							<li><a href="search.php?category=Single" class="list-group-item<?php if($the_category_name == "single"){ echo ' active'; }?>">Single</a></li><?php
						
		function getCategoryList($beforeUrl, $afterUrl)
		{
			global $db;
			$query = 'SELECT hearts_category_name FROM gv_hearts_category where hearts_category_lock = 0 ORDER BY hearts_category_id ASC';
			$result = mysql_query($query, $db) or die(mysql_error($db));

			$row = mysql_fetch_assoc($result);
			
			//Get modified_for_url function
			require_once('../Server_Includes/scripts/common_scripts/modified_for_url.php');
			
			while($row = mysql_fetch_assoc($result))
			{
				extract($row);
				echo  '
							' . $beforeUrl . '<a href="search.php?category=' . modified_for_url($hearts_category_name, ' ', '-', '') . '" class="list-group-item">' . $hearts_category_name . '</a>' . $afterUrl;
			}
		}

		getCategoryList('<li>', '</li>');
		?>
		
						</ul>
                    </div>
                </div>
            </div>

		<div class="row col-md-9">
			<div class="row col-sm-12 col-md-12 col-lg-12">
				<?php
	$queryFemale = 'SELECT featured_profile_id, featured_view_count, featured_name, featured_gender, featured_age, featured_city, featured_state, featured_phone, featured_email, featured_about_me FROM gv_hearts_featured_profile WHERE featured_display = 1 AND featured_gender = "female"';
	$resultFemale = mysql_query($queryFemale, $db) or die (mysql_error($db));
	
	$rowFemale = mysql_fetch_array($resultFemale);
	
	if(mysql_num_rows($rowFemale) !== 0)
	{
		extract($rowFemale);
?>
				<table class="table table-striped table-hover col-md-12 col-lg-12 text-center">
					<tbody>
						<tr>
							<td colspan="2" class="col-md-12 col-lg-12">
								<p class="text-center"><img class="img-responsive thumbnail text-center" height="400" width="400" src="../images/service_images/hearts/featured/<?php echo featured_image($rowFemale["featured_profile_id"]); ?>"/></p>
							</td>
						</tr>
						<tr>
							<td>Name:</td>
							<td><?php echo html_entity_decode($rowFemale["featured_name"]); ?></td>
						</tr>
						<tr>
							<td>Gender:</td>
							<td><?php echo ucfirst($rowFemale["featured_gender"]); ?></td>
						</tr>
						<tr>
							<td>Age:</td>
							<td><?php echo $rowFemale["featured_age"]; ?></td>
						</tr>
						<tr>
							<td>City:</td>
							<td><?php echo html_entity_decode($rowFemale["featured_city"]); ?></td>
						</tr>
						<tr>
							<td>State:</td>
							<td><?php echo html_entity_decode($rowFemale["featured_state"]); ?></td>
						</tr>
						<tr>
							<td>Country:</td>
							<td><?php /*echo country_name($rowMale["cc_id"]);*/ ?></td>
						</tr>
						<tr>
							<td>Phone:</td>
							<td><?php echo html_entity_decode($rowFemale["featured_phone"]); ?></td>
						</tr>
						<tr>
							<td>Email:</td>
							<td><?php echo html_entity_decode($rowFemale["featured_email"]); ?></td>
						</tr>
						<tr>
							<td colspan="2">About me:<br/><?php echo html_entity_decode($rowFemale["featured_about_me"]); ?></td>
						</tr>
						<tr>
							<td colspan="2">Total views:<br/><?php echo $rowFemale["featured_view_count"]; ?></td>
						</tr>
					</tbody>
				</table>
<?php }else{ ?>
				<table class="table table-striped table-hover col-md-12 text-center">
					<tbody>
						<tr>
							<td colspan="2" class="col-md-12">
								<p>There is no submitted Feature profile for a female yet.<br> Hey! Why don't you <a href="featured_submit.php">click here</a> to submit yours.</p>
							</td>
						</tr>
					</tbody>
				</table>
<?php } ?>
			<br/><br/><br/><hr>
			</div>

			<div class="row col-sm-12 col-md-12 col-lg-12">
				<?php

	$queryMale = 'SELECT featured_profile_id, featured_view_count, featured_name, featured_gender, featured_age, featured_city, featured_state, featured_phone, featured_email, featured_about_me FROM gv_hearts_featured_profile WHERE featured_display = 1 AND featured_gender = "male"';
	$resultMale = mysql_query($queryMale, $db) or die (mysql_error($db));

	$rowMale = mysql_fetch_array($resultMale);

	if(mysql_num_rows($rowMale) !== 0)
	{
		extract($rowMale);
?>
				<table class="table table-striped table-hover col-md-12 col-lg-12 text-center">
					<tbody>
						<tr>
							<td colspan="2" class="col-md-12 col-lg-12">
								<p class="text-center"><img class="img-responsive thumbnail" height="400" width="400" src="../images/service_images/hearts/featured/<?php echo featured_image($rowMale["featured_profile_id"]); ?>"/></p>
							</td>
						</tr>
						<tr>
							<td>Name:</td>
							<td><?php echo html_entity_decode($rowMale["featured_name"]); ?></td>
						</tr>
						<tr>
							<td>Gender:</td>
							<td><?php echo ucfirst($rowMale["featured_gender"]); ?></td>
						</tr>
						<tr>
							<td>Age:</td>
							<td><?php echo $rowMale["featured_age"]; ?></td>
						</tr>
						<tr>
							<td>City:</td>
							<td><?php echo html_entity_decode($rowMale["featured_city"]); ?></td>
						</tr>
						<tr>
							<td>State:</td>
							<td><?php echo html_entity_decode($rowMale["featured_state"]); ?></td>
						</tr>
						<tr>
							<td>Country:</td>
							<td><?php /*echo country_name($rowMale["cc_id"]);*/ ?></td>
						</tr>
						<tr>
							<td>Phone:</td>
							<td><?php echo html_entity_decode($rowMale["featured_phone"]); ?></td>
						</tr>
						<tr>
							<td>Email:</td>
							<td><?php echo html_entity_decode($rowMale["featured_email"]); ?></td>
						</tr>
						<tr>
							<td colspan="2">About me:<br/><?php echo html_entity_decode($rowMale["featured_about_me"]); ?></td>
						</tr>
						<tr>
							<td colspan="2">Total views:<br/><?php echo $rowMale["featured_view_count"]; ?></td>
						</tr>
					</tbody>
				</table>
<?php }else{ ?>
				<table class="table table-striped table-hover col-md-12 text-center">
					<tbody>
						<tr>
							<td colspan="2" class="col-md-12">
								<p>There is no submitted Feature profile for a male yet.<br> Hey! Why don't you <a href="featured_submit.php">click here</a> to submit yours.</p>
							</td>
						</tr>
					</tbody>
				</table>
<?php } ?>
			</div>
	<!-- /.container -->
	
	</div>
    <!-- /.container -->

        <hr>

		<?php require_once('../Server_Includes/scripts/hearts_scripts/hearts_footer.php'); ?>
		
</body>

</html><?php 
	mysql_close();
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
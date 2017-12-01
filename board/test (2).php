<?php

	$mobile_view_menu_colour = '#000000';

	//Get custom error function script
	require_once('../Server_Includes/scripts/common_scripts/feature_error_message.php');

	//Connect to the database
	require_once('../Server_Includes/visitordbaccess.php');

	//Get truncate_text function
	require_once('../Server_Includes/scripts/common_scripts/text_truncate_function.php');

	//Get modified_for_url function
//-	require_once('../Server_Includes/scripts/common_scripts/get_page_features.php');
//-	require_once('../Server_Includes/scripts/common_scripts/get_name_functions.php');
	
	//Get page functions
	require_once('../Server_Includes/scripts/hearts_scripts/functions_get_page_features_for_hearts.php');
	
	//Get name functions
//-	require_once('../Server_Includes/scripts/hearts_scripts/functions_get_name_for_hearts.php');

	//Get declaration of pagination function
	require_once('../Server_Includes/scripts/common_scripts/pagination2.php');

	$extra_title = 'Latest Profiles' . " | \n";

	////////////////////// Every stuff goes here ---- //////////////////////////////////////

	$query = 'SELECT hearts_profile_id, hearts_category_id, hearts_profile_introduction, profile_id FROM gv_hearts_profile WHERE hearts_member = 1 AND hearts_profile_block = 0 ORDER BY hearts_profile_id DESC ';

	$data = mysql_query($query) or die(mysql_error());
	$rows = mysql_num_rows($data); //This is also the value in " for number_format($rows) results."

	if($rows > 0)
	{	
		//This checks to see if ther's a page number. If not, it will set it to page 1
		$pagenum = (isset($_GET["pagenum"])) ? $_GET["pagenum"] : 1;
		
		//This checks to see if ther's a page number. If not, it will set it to page 1
		if(!(isset($pagenum)))
		{
			$pagenum = 1;
		}

		//This is the number of rows to be displayed as results per page
		$page_rows = 17;
		
		//This tells us the page number of our last page
		$last = ceil($rows/$page_rows); //This is " Last "
		$next = $pagenum + 1;
		$previous = $pagenum - 1;
		
		//This makes sure the page number isn't below one, or more than the maximum pages
		if($pagenum < 1)
		{
			$pagenum = 1;
		}
		elseif($pagenum > $last)
		{
			$pagenum = $last;
		}
		
		//This sets the range of results to display per page for any page view.
		$max = ' LIMIT ' . ($pagenum - 1) * $page_rows . ', ' . $page_rows;
		
		//This is the query again, the same one but with the $max variable incorporated.
		$data_p = mysql_query($query . $max) or die(mysql_error($db));
	}//if($rows > 0)

	//Get meta details
	require_once('../Server_Includes/scripts/hearts_scripts/hearts_meta.php');

	//Set extra title	
	$extra_title .= "Genieverse Hearts - Meet the right person | " . $hearts_title_description;

	//Set meta details
	$meta_keyword = $hearts_meta_keywords;
	$meta_description = $hearts_meta_description;

	//Set page background colour
	$header_background_color = '#FFF'; //#FFF(white) #000(black) #EEE(light ash) none(default background)

	$latest = 'set';
	
	$page_contents_title = "Latest Profiles";
	
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
	<?php
/*        <!-- /.row -->
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
        <hr> */
?>
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
			<table class="table table-striped table-hover">
				<tbody>
					<?php
   
	if($rows !== 0)
	{
		echo "\n";
		while($row = mysql_fetch_assoc($data_p))
		{
			extract($row);
?>
					<tr>
						<td class="col-md-2 text-center">
							<a href="profile_view.php?id=<?php
		echo  $row["hearts_profile_id"];
		echo modified_for_url(category_name($row["hearts_category_id"]), "&Marital_Status=");
		echo modified_for_url(html_entity_decode($row["hearts_profile_introduction"]), "&Profile="); ?>"><img class="img-responsive" height="65" width="65" src="../images/service_images/hearts/<?php echo post_image($row["hearts_profile_id"]); ?>"/></a>
							<br>
							<br>
							<p><?php category_name($row["hearts_category_id"]); ?></p>
						</td>
						<td class="col-md-10">
							<h4><a href="profile_view.php?id=<?php
		echo  $row["hearts_profile_id"];
		echo modified_for_url(category_name($row["hearts_category_id"]), "&Marital_Status=");
		echo modified_for_url(html_entity_decode($row["hearts_profile_introduction"]), "&Profile="); ?>"><?php echo html_entity_decode($row["hearts_profile_introduction"]); ?></a></h4>
							<p>Ikotun, Lagos,</p>
							<p>Nigeria.</p>
						</td>
					</tr><?php
		}
	}
	else {
			?>
					<tr>
						<td class="col-md-12 text-center">
							<br><br><br><br><br><br>
							<p>Sorry. There're no Hearts Profiles, yet.</p>
							<br><br><br><br><br><br>
							<br><br><br><br><br><br>
							<br><br>
						</td>
					</tr><?php
	} ?>
				</tbody>
			</table>
		</div>	
	
	<?php
		if($rows !== 0)
		{
			$adjacents = 3;
			//$reload = $_SERVER['PHP_SELF'];
			echo paginate_two($page_rows, $pagenum, $rows, $adjacents, $previous, $next, $last);
		}//if($rows !== 0)
	?>
	
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
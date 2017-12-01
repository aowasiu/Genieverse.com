<?php

	//Get custom error function script
	require_once('../Server_Includes/scripts/common_scripts/feature_error_message.php');

	/*
		This script searches Spotlight table of posts to retrieve posts matching the 'search_keywords'.
		It also draws from Spotlight post image table, related photos or images
	*/
	$search_keywords = (isset($_GET["search_field"])) ? trim($_GET["search_field"]) : "";
	$url_category_name = (isset($_GET["category"])) ? trim($_GET["category"]) : "";

	//If category name is not set in the $_GET variable, redirect this person to the home page with an errand message
	if(!isset($url_category_name) || $url_category_name == "")
	{
		$_SESSION["errand_title"] = "Search issue.";
		$_SESSION["errand"] = 'You need to select a category in the <a href="categories.php?service=spotlight">Categories</a> section to view posts.';
		header("Location: ../issues.php");
		exit;
	}
	
	//Connect to the database
	require_once('../Server_Includes/visitordbaccess.php');

	//Get modified_for_category_name funcion
	require_once('../Server_Includes/scripts/common_scripts/modified_for_category_name.php');

	//Remove the url modification (hyphen or underscore) from the category name
	$category_name = modified_for_category_name($url_category_name, '-', ' ');

	//Get does_category_name_exist funcion
	require_once('../Server_Includes/scripts/spotlight_scripts/functions_for_spotlight.php');
	
    $category_id = does_category_name_exist($category_name);

	if($category_id == 0)
	{
    	mysql_close();
		$_SESSION["errand_title"] = "Sorry! Non-existent category.";
		$_SESSION["errand"] = 'The category you requested is not available.<br/><a href="categories.php?service=spotlight">Click here to choose from available categories</a>';
		header("Location: ../issues.php");
		exit;
	}
	
	$query = 'SELECT spotlight_post_id, cc_id, spotlight_category_id, spotlight_post_investigated, spotlight_post_title, spotlight_post_body, spotlight_post_state, spotlight_post_city, spotlight_post_created_on FROM gv_spotlight_post WHERE (spotlight_member = 1 AND spotlight_post_block = 0) AND (spotlight_category_id = ' . $category_id . ') ORDER BY spotlight_post_id DESC ';
/*
	(spotlight_post_investigated = 1 AND spotlight_category_id = ' . $category_id . ') ORDER BY spotlight_post_id DESC ';
*/
	$data = mysql_query($query) or die(mysql_error());
	$rows = mysql_num_rows($data); //This is also the value in " for number_format($rows) results."

	if($rows == 0)
	{
    	mysql_close();
		$_SESSION["errand_title"] = "Sorry! No post.";
		$_SESSION["errand"] = "There're no posts, yet, for this category<br/><a href='categories.php?service=spotlight'>Click here to choose another category besides " . ucfirst($category_name) . '</a>';
		header("Location: ../issues.php");
		exit;
	}
	
	//This checks to see if there's a page number. If not, it will set it to page 1
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

	//Get category_id based on given or acquired category_name
	require_once('../Server_Includes/scripts/common_scripts/get_id_functions.php');	

	//Get modified_for_url function
	require_once('../Server_Includes/scripts/common_scripts/get_page_features.php');
	
	//Get truncate_text function
	require_once('../Server_Includes/scripts/common_scripts/text_truncate_function.php');

	//Get functions for 
	require_once('../Server_Includes/scripts/spotlight_scripts/functions_get_page_features_for_spotlight.php');
	
	require_once('../Server_Includes/scripts/spotlight_scripts/spotlight_meta.php');
	
	require_once('../Server_Includes/scripts/spotlight_scripts/spotlight_footer.php');
	
	//Get change_date_medium function
	require_once('../Server_Includes/scripts/common_scripts/date_formats.php');
	
	//Get category_name function
	require_once('../Server_Includes/scripts/common_scripts/get_name_functions.php');

	//Get declaration of pagination function
	require_once('../Server_Includes/scripts/common_scripts/pagination2.php');


	$extra_title = "Search | Genieverse Spotlight - " . $spotlight_meta_description;
	$three_col_portfolio = "set";
	$meta_keyword = $spotlight_meta_keywords;
	$meta_description = $spotlight_meta_description;
	
	require_once('../Server_Includes/scripts/common_scripts/common_head2.php'); ?><body>
<?php require_once('../Server_Includes/scripts/spotlight_scripts/spotlight_outer_page_header.php'); ?>
    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><small><?php echo ucfirst($category_name); ?> Category</small></h1>
            </div>
        </div>
        <!-- /.row -->
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
        </div>*/
?>        <!-- /.row -->

        <!-- Projects Row -->
        <div class="row"><?php
	
//	if(mysql_num_rows($result))

    if($rows < 1)
    {
			?>
            <div class="col-md-4 portfolio-item">
                <p>Sorry. There're no posts yet for <?php echo ucfirst($category_name); ?>.</p>
			</div><?php
	  }
     else {
	 	while($row = mysql_fetch_assoc($data_p))
 		{
			extract($row);
?>
            <div class="col-md-4 portfolio-item">
				<a href="post_view.php?id=<?php
		 echo $row["spotlight_post_id"];
		 echo modified_for_url($row["spotlight_post_title"], "&Post="); ?>"><img class="img-responsive" height="65" width="65" src="../images/service_images/spotlight/<?php echo post_image($row["spotlight_post_id"]); ?>"></a>
                <h3><a href="post_view.php?id=<?php
		 echo $row["spotlight_post_id"];
		 echo modified_for_url($row["spotlight_post_title"], "&Post="); ?>"><?php echo $row["spotlight_post_title"]; ?></a></h3>
                <p><?php echo truncate_text(html_entity_decode($row["spotlight_post_body"]), 40, "No detail."); ?></p>
            </div>
<?php
       }
	}
 ?>
        </div>
		<!-- /.row -->
        <hr>

		
<?php
		$adjacents = 3;
		//$reload = $_SERVER['PHP_SELF'];
	
		echo paginate_two($page_rows, $pagenum, $rows, $adjacents, $previous, $next, $last);	 ?>
	
        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p><?php echo $the_footer; ?></p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

<?php require_once('../Server_Includes/scripts/common_scripts/common_before_body_end2.php'); ?>

</body>

</html><?php 
	mysql_close();
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
?>
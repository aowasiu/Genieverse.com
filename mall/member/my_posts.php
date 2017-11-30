<?php

	//Get custom error function script 
	require_once('../../Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	if(!isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] !== 1)
	{
		$_SESSION["errand_title"] = "Log in issue";
		$_SESSION["errand"] = "You must log in first to view member area.";
		//Take this visitor to the log in page because he's not logged in
		header("Location: ../log_in.php?location=" . urlencode($_SERVER["REQUEST_URI"]));
		exit;
	}
	
	$post_class = (isset($_GET["class"])) ? $_GET["class"] : "" ;
	if(isset($post_class) && $post_class == 'active')
	{
		$post_activity = 1;
	}
	elseif(isset($post_class) && $post_class == 'inactive')
	{
		$post_activity = 0;
	}
	else {
			//Do nothing
			$post_activity = "";
	}

	//Errors definition
	$errors = array();
	
	//Connect to the database
	require_once('../../Server_Includes/visitordbaccess.php');

	$delete = (isset($_GET["delete"])) ? $_GET["delete"] : 0;
	if(isset($delete) && $delete !== 0) 
	{
		//Get image core directory
		require_once('../../Server_Includes/image_directory_core.php');

		//Path to images directory
		$image_directory = $image_directory_core . '/images/service_images/mall/';
		
		$query = 'SELECT mall_image_filename FROM gv_mall_post_image WHERE mall_post_id = ' . mysql_real_escape_string($delete, $db);
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
		
		$query = 'DELETE FROM gv_mall_post_image WHERE mall_post_id = ' . mysql_real_escape_string($delete, $db);
		mysql_query($query, $db) or die (mysql_error($db));

		$query = 'DELETE FROM gv_mall_post WHERE mall_post_id = ' . mysql_real_escape_string($delete, $db) . ' AND member_id = ' . $_SESSION["member_id"];
		mysql_query($query, $db) or die (mysql_error($db));
		
		$_SESSION["errand_title"] = "Successful post removal";
		$_SESSION["errand"] = "The post has been deleted successfully.";
	}
	
	//Get Genieverse Mall post's first image function
	require_once('../../Server_Includes/scripts/mall_scripts/functions_get_page_features_for_mall.php');
	
	//Get Genieverse date function
	require_once('../../Server_Includes/scripts/common_scripts/date_formats.php');
	
	//Get Genieverse text truncate function
	require_once('../../Server_Includes/scripts/common_scripts/text_truncate_function.php');
	
	if($post_class !== "")
	{
		$query = 'SELECT mall_post_id, mall_post_activation, mall_post_created_on, mall_post_title FROM gv_mall_post WHERE (mall_post_block = 0 AND mall_member = 1) AND (member_id = ' . $_SESSION["member_id"] . ' AND mall_post_activation = ' . mysql_real_escape_string($post_activity, $db) . ') ORDER BY mall_post_created_on DESC ';
	}
	else {
			$query = 'SELECT mall_post_id, mall_post_activation, mall_post_created_on, mall_post_title FROM gv_mall_post WHERE (mall_post_block = 0 AND mall_member = 1) AND member_id = ' . $_SESSION["member_id"] . ' ORDER BY mall_post_created_on DESC ';
	}

	$data = mysql_query($query) or die(mysql_error());
	$rows = mysql_num_rows($data); //This is also the value in " for number_format($rows) results."
	
	if($rows == 0)
	{
		if(isset($post_class) && $post_class !== "")
		{
			$_SESSION["errand_title"] = ucfirst($post_class) . " posts report.";
			$_SESSION["errand"] = "There's no " . ucfirst($post_class) . " post.";
		}
		else {
				$_SESSION["errand_title"] = "Posts report.";
				$_SESSION["errand"] = "You have no post.";
		}
		
		header("Location: dashboard.php");
		exit;
	}
	
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
	
	//Get Mall meta data and footer
	require_once('../../Server_Includes/scripts/mall_scripts/mall_meta.php');
	require_once('../../Server_Includes/scripts/mall_scripts/mall_member_footer.php');
	
	//Get declaration of pagination function
	require_once('../../Server_Includes/scripts/common_scripts/pagination2.php');
	
	$extra_title = ucfirst($_SESSION["username"]) . " posts | Genieverse Mall - Free web market ";	
	$three_col_portfolio = "set";
	$meta_keyword = $mall_meta_keywords;
	$meta_description = $mall_meta_description;
	
	require_once('../../Server_Includes/scripts/common_scripts/common_member_page_head2.php'); ?><body>
<?php require_once('../../Server_Includes/scripts/mall_scripts/mall_member_page_header.php'); ?>
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
			
				<div class="caption-full">
					<h3>My <?php if($post_class !== ""){ echo ucfirst($post_class); } ?> Mall posts</h3>
				</div>
				<br>

<?php /* <form role="form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> */ ?>
<?php
	if($rows > 0)
	{
?>			<table class="table table-hover col-md-12">
					<thead>
						<tr>
							<th>Photo</th>
							<th>Title</th>
							<th>Date</th>
							<th>Action</th>
						</tr>
				</thead>
				<tbody>
<?php
		while($row = mysql_fetch_assoc($data_p))
		{
			extract($row);
			if($row["mall_post_activation"] == 0)
			{ $the_css = 'active ';}
			else { 	$the_css = ""; }
?>				<tr class="<?php echo $the_css; ?>caption-full">
					<td><a href="my_post.php?id=<?php echo $row["mall_post_id"]; ?>"><img class="img-responsive" height="60" width="60" src="../../images/service_images/mall/<?php echo post_image($row["mall_post_id"]); ?>" alt="Genieverse Mall - Free web market"></a></td>
					<td><a href="my_post.php?id=<?php echo $row["mall_post_id"]; ?>"><?php echo truncate_text($row["mall_post_title"], 80, "No title"); ?></a></td>
					<td><?php echo custom_date($row["mall_post_created_on"], "F t, Y. g:ia"); ?></td>
					<td><a class="btn btn-default" href="my_posts.php?delete=<?php echo $row["mall_post_id"]; ?>">Delete</a></td>
				</tr>
<?php
	}//End of while loop
	
	/*
				<div class="form-group caption-full">
					<h5 class="pull-right"></h5>
					<h5><input type="submit" name="delete_all" value="Delete selected posts"></h5>
					<hr>
				</div>
				
				<div class="form-group caption-full">
					<h5 class="pull-right"></h5>
					<h5><input type="submit" name="delete_all" value="Delete selected posts"></h5>
					<hr>
				</div>
				
		</form> */ ?>				
				</tbody>
			</table>
                
		</div>
		<!-- /.row -->
        <hr>
		
<?php
		$adjacents = 3;
		//$reload = $_SERVER['PHP_SELF'];
	echo paginate_two($page_rows, $pagenum, $rows, $adjacents, $previous, $next, $last);
	}//End of if(mysql_num_rows($result) > 0)
	?>
	
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

<?php require_once('../../Server_Includes/scripts/common_scripts/common_member_page_before_body_end.php'); ?>

</body>

</html><?php 
	mysql_close();
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
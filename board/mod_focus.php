<?php

	//Get custom error function script 
	require_once('../Server_Includes/scripts/common_scripts/feature_error_message.php');
		
	//Connect to the database
	require_once('../Server_Includes/visitordbaccess.php');

	//Get truncate_text function
	require_once('../Server_Includes/scripts/common_scripts/text_truncate_function.php');

	//Get modified_for_url function
	require_once('../Server_Includes/scripts/common_scripts/get_page_features.php');
	
	//Get page functions
	require_once('../Server_Includes/scripts/board_scripts/functions_get_page_features_for_board.php');
	
	//Get name functions
	require_once('../Server_Includes/scripts/board_scripts/functions_get_name_for_board.php');
		
	require_once('../Server_Includes/scripts/board_scripts/board_meta.php');

	require_once('../Server_Includes/scripts/board_scripts/board_footer.php');
	
	//Get change_date_medium function
	require_once('../Server_Includes/scripts/common_scripts/date_formats.php');
	
	//Get declaration of pagination function
	require_once('../Server_Includes/scripts/common_scripts/pagination2.php');

	$query = 'SELECT board_post_id, cc_id, board_category_id, board_post_title, board_post_body FROM gv_board_post WHERE (board_member = 1 AND board_post_block = 0) AND (board_post_mod_focus = 1) ORDER BY board_post_id DESC ';

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

	$three_col_portfolio = "set";
	$meta_keyword = $board_meta_keywords;
	$meta_description = $board_meta_description;
	$mod_focus = "set";
	$extra_title = "Mod Focus | Genieverse Board - " . $board_meta_description;
	
	require_once('../Server_Includes/scripts/common_scripts/common_head2.php'); ?><body>
<?php require_once('../Server_Includes/scripts/board_scripts/board_outer_page_header.php'); ?>
    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><small>Mod Focus</small></h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Projects Row -->
        <div class="row">
			<?php
   
	if($rows !== 0)
	{
		while($row = mysql_fetch_assoc($data_p))
		{
			extract($row);
?><div class="col-md-4 portfolio-item">
                <a href="post_view.php?id=<?php
		echo  $row["board_post_id"];
		echo modified_for_url(category_name($row["board_category_id"]), "&Category=");
		echo modified_for_url($row["board_post_title"], "&Post="); ?>"><img class="img-responsive" height="65" width="65" src="../images/service_images/board/<?php echo post_image($row["board_post_id"]); ?>"></a>
                <h4><a href="post_view.php?id=<?php
		echo  $row["board_post_id"];
		echo modified_for_url(category_name($row["board_category_id"]), "&Category=");
		echo truncate_text(modified_for_url($row["board_post_title"], "&Post="), 40, "No detail."); ?>"><?php echo $row["board_post_title"]; ?></a></h4>
            </div>
			<?php
		}
	}
	else{
			?>
            <div class="col-md-4 portfolio-item">
                <p>Sorry. There're no posts marked as Mod Focus.</p>
			</div><?php
	}?>
        </div>
		<!-- /.row -->
        <hr>

		
<?php

	if($rows !== 0)
	{
		$adjacents = 3;
		//$reload = $_SERVER['PHP_SELF'];
		echo paginate_two($page_rows, $pagenum, $rows, $adjacents, $previous, $next, $last);
	}//if($rows !== 0)
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

<?php require_once('../Server_Includes/scripts/common_scripts/common_before_body_end2.php'); ?>

</body>

</html><?php 
	
	mysql_close();
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }

?>
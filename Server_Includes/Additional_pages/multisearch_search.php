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
	require_once('../Server_Includes/scripts/voice_scripts/functions_get_page_features_for_voice.php');
	
	//Get name functions
	require_once('../Server_Includes/scripts/voice_scripts/functions_get_name_for_voice.php');
		
	require_once('../Server_Includes/scripts/voice_scripts/voice_meta.php');
	
	require_once('../Server_Includes/scripts/voice_scripts/voice_footer.php');
	
	//Get change_date_medium function
	require_once('../Server_Includes/scripts/common_scripts/date_formats.php');
	
	//Get declaration of pagination function
	require_once('../Server_Includes/scripts/common_scripts/pagination2.php');

	$search_keywords = (isset($_POST["search_keywords"])) ? trim($_POST["search_keywords"]) : "";
	$safe_search_words mysql_real_escape_string($search_keywords);
	
	if($safe_search_words == "")
	{
		$search_result = "<tr>
			<td>You didn't enter any word to search for</td>
		</tr>";
	}
	else {
			//$search_mode = 'IN NATURAL LANGUAGE MODE';
			//$search_mode = 'IN NATURAL LANGUAGE MODE WITH EXPANSION';
			$search_mode = 'IN BOOLEAN MODE';
			//$search_mode = 'WITH QUERY EXPANSION';
			
			$not_needed_match = 'voice_post_id, voice_post_view_count';
			$needed_match = 'voice_post_title, voice_post_body, voice_post_state, voice_post_city';
			$required_columns = $not_needed_match . ', ' . $needed_match;
			
			$query = 'SELECT ' . $not_needed_match . ', voice_post_title MATCH(' . $needed_match . ') AGAINST (' . $safe_search_words . ' ' . $search_mode . ') AS score FROM gv_voice_post WHERE MATCH (' . $needed_match . ') AGAINST (' . $safe_search_words . ' ' . $search_mode . ') ORDER BY score DESC ';
			
			$data = mysql_query($query) or die(mysql_error());
			$rows = mysql_num_rows($data); //This is also the value in " for number_format($rows) results."
			
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

	}
	
	$extra_title = "Voice Search | Genieverse Voice - Tell the world what's happening.";

	$three_col_portfolio = "set";
	$meta_keyword = $voice_meta_keywords;
	$meta_description = $voice_meta_description;
	$bargains = "set";
	
	require_once('../Server_Includes/scripts/common_scripts/common_head.php'); ?><body>
<?php require_once('../Server_Includes/scripts/voice_scripts/voice_outer_page_header.php'); ?>
    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><small>Search</small></h1>
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
		echo  $row["voice_post_id"];
		echo modified_for_url(category_name($row["voice_category_id"]), "&Category=");
		echo modified_for_url($row["voice_post_title"], "&post="); ?>"><img class="img-responsive" height="65" width="65" src="../images/service_images/voice/<?php echo post_image($row["voice_post_id"]); ?>"></a>
                <h4><a href="post_view.php?id=<?php
		echo  $row["voice_post_id"];
		echo modified_for_url(category_name($row["voice_category_id"]), "&Category=");
		echo modified_for_url($row["voice_post_title"], "&Post="); ?>"><?php echo $row["voice_post_title"]; ?></a></h4>
                <p><?php echo truncate_text(html_entity_decode($row["voice_post_body"]), 40, "No detail."); ?></p>
            </div>
			<?php
		}
	}
	else{
			?>
            <div class="col-md-4 portfolio-item">
                <p>Sorry. There're no Voice posts.</p>
			</div><?php
	}?>
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

<?php require_once('../Server_Includes/scripts/common_scripts/common_before_body_end.php'); ?>

</body>

</html><?php 
	mysql_close();
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
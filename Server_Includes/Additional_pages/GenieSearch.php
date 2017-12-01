<?php

	//Get custom error function script 
	require_once('../Server_Includes/scripts/common_scripts/feature_error_message.php');
		
	//Connect to the database
	require_once('../Server_Includes/visitordbaccess.php');
	
	//Get Voice meta definition
	require_once('../Server_Includes/scripts/voice_scripts/voice_meta.php');

	//Get Voice footer definition
	require_once('../Server_Includes/scripts/voice_scripts/voice_footer.php');
	
	//Get check_input function
	require_once('../Server_Includes/scripts/common_scripts/check_input.php');

	$safe_search_words = (isset($_GET["search_keywords"])) ? trim($_GET["search_keywords"]) : "Type search terms here";
/*	$search_keywords = check_input($search_keywords);
	$safe_search_words = mysql_real_escape_string($search_keywords);	
*/
//	$safe_search_words = strtoupper($search_keywords);

	if(isset($_GET["searched"]))
	{
		$minimum_search_keywords = 4;
		if(strlen($safe_search_words) <= $minimum_search_keywords)
		{
			$result = "<div class='col-lg-12'><p>Your search term is too small.<br>
			Enter a longer word or a group of words.</p></div>";
		}
		else{
			//$search_mode = ' IN NATURAL LANGUAGE MODE ';
			//$search_mode = ' IN NATURAL LANGUAGE MODE WITH EXPANSION ';
			$search_mode = ' IN BOOLEAN MODE ';
			//$search_mode = ' WITH QUERY EXPANSION ';
			
			$not_needed_match = ' voice_post_id, loud_voice, voice_post_view_count, voice_category_id ';
			
			$needed_match = ' voice_post_title, voice_post_body, voice_post_state, voice_post_city ';
			
			$query = 'SELECT ' . $not_needed_match . ", voice_post_title, voice_post_body, MATCH (" . $needed_match . ') AGAINST ("' . mysql_real_escape_string($safe_search_words, $db) . '" ' . $search_mode . ') AS score FROM gv_voice_post WHERE MATCH (' . $needed_match . ') AGAINST ("' . mysql_real_escape_string($safe_search_words, $db) . '" ' . $search_mode . ') ORDER BY score DESC ';
			
			//Pagination Preparation script follows here
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
			
			if($rows > 0)
			{
				//Get truncate_text function
				require_once('../Server_Includes/scripts/common_scripts/text_truncate_function.php');

				//Get modified_for_url function
				require_once('../Server_Includes/scripts/common_scripts/get_page_features.php');
				
				//Get page functions
				require_once('../Server_Includes/scripts/voice_scripts/functions_get_page_features_for_voice.php');
				
				//Get name functions
				require_once('../Server_Includes/scripts/voice_scripts/functions_get_name_for_voice.php');
				
				//Get change_date_medium function
				require_once('../Server_Includes/scripts/common_scripts/date_formats.php');
				
				//Get declaration of pagination function
				require_once('../Server_Includes/scripts/common_scripts/pagination2.php');
			}
		}
	}
	
	$extra_title = "Voice Search | Genieverse Voice - Tell the world what's happening.";
	$three_col_portfolio = "set";
	$meta_keyword = $voice_meta_keywords;
	$meta_description = $voice_meta_description;
	
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

		<!-- Page Top Row -->
        <div class="row">
            <div class="col-lg-12">
                <form role="form" method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
					<span>
						<input type="hidden" name="searched" value="searched">
						<input type="text" class="form-control" maxlength="100" name="search_keywords" value="<?php echo $safe_search_words; ?>">
					</span><hr>
					<span>
						<input type="submit" class="btn btn-default" name="search" value="Search Voice">
					</span>
				</form>
            </div>
        </div>
        <!-- /.row -->
		
		<!-- Projects Row -->
        <div class="row">
			<?php
				if(!isset($_GET["searched"]))
				{
					echo "<div class='col-lg-12'><p></p></div>";
				}
				else{
						if($result)
						{
							echo $result;
						}
						else{
								echo '<div class="row">
            <div class="col-md-12">
                <h3 class="page-header"><small>Search result</small></h3>
            </div>
        </div>';
								if($rows > 0)
								{
									while($row = mysql_fetch_assoc($data_p))
									{
										extract($row);
			?><div class="col-md-4 portfolio-item">
                <a href="post_view.php?id=<?php echo  $row["voice_post_id"];
		echo modified_for_url(category_name($row["voice_category_id"]), "&Category=");
		echo modified_for_url($row["voice_post_title"], "&Post="); ?>"><img class="img-responsive" height="80" width="80" src="../images/service_images/voice/<?php echo post_image($row["voice_post_id"]); ?>"></a>
                <h4><a href="post_view.php?id=<?php
		echo  $row["voice_post_id"];
		echo modified_for_url(category_name($row["voice_category_id"]), "&Category=");
		echo modified_for_url($row["voice_post_title"], "&Post="); ?>"><?php echo $row["voice_post_title"]; ?></a></h4>
                <p><?php echo truncate_text(html_entity_decode($row["voice_post_body"]), 40, "No detail."); ?></p>
            </div><?php
									}//End of while($row = mysql_fetch_assoc($data_p))
								}//End of if($rows !== 0)
								else{
										echo '<hr><div class="col-md-12">
                <p>Sorry. No post matches your search for:<br>' . $safe_search_words . '.</p>
			</div>';
								}//End of if($rows == 0)
						}//End of if(strlen($safe_search_words) > $minimum_search_keywords)
				}//End of if(isset($_GET["searched"]))
			?>
        </div>
		<!-- /.row -->

<?php
	if(isset($_GET["searched"]))
	{
		if(strlen($safe_search_words) > $minimum_search_keywords)
		{
			if($rows !== 0)
			{
				$adjacents = 3;
				//$reload = $_SERVER['PHP_SELF'];
				echo paginate_two($page_rows, $pagenum, $rows, $adjacents, $previous, $next, $last);
			}
		}
	}
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

<?php require_once('../Server_Includes/scripts/common_scripts/common_before_body_end.php'); ?>

</body>

</html><?php 
	mysql_close();
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
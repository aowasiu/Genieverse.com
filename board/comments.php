<?php

	//Get custom error function script 
	require_once('../Server_Includes/scripts/common_scripts/feature_error_message.php');

	//Connect to the database
	require_once('../Server_Includes/visitordbaccess.php');

	//Get page functions
	require_once('../Server_Includes/scripts/spotlight_scripts/functions_get_page_features_for_spotlight.php');

	require_once('../Server_Includes/scripts/spotlight_scripts/spotlight_meta.php');

	require_once('../Server_Includes/scripts/spotlight_scripts/spotlight_footer.php');
	
	//Get change_date_medium function
	require_once('../Server_Includes/scripts/common_scripts/date_formats.php');

	//Get declaration of pagination function
	require_once('../Server_Includes/scripts/common_scripts/pagination2.php');

	//This function gets the name of a member
	function member_username($data)
	{
		global $db;
		$query = 'SELECT username FROM gv_members WHERE member_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return ucfirst($username);
	}

	//Get post's title
	function posts_title($data)
	{
		global $db;
		$query = 'SELECT spotlight_post_title FROM gv_spotlight_post WHERE (spotlight_member = 1 AND spotlight_post_block = 0) AND (spotlight_post_id = ' . mysql_real_escape_string($data) . ')';
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return ucwords($spotlight_post_title);
	}

	$posts_title = posts_title($get_id);

	$query = 'SELECT spotlight_comment_id, member_id, spotlight_comment_created_on, spotlight_comment FROM gv_spotlight_post_comments WHERE (spotlight_member = 1 AND spotlight_comment_block = 0) AND (spotlight_comment_delete = 0 AND spotlight_post_id = ' . mysql_real_escape_string($get_id, $db) . ')';

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

	//Get this_posts_total_comments function
	require_once('../Server_Includes/scripts/spotlight_scripts/this_posts_total_comments.php');

	$total_comments = this_posts_total_comments($post_id);
	
	$extra_title =  $posts_title . " comments | Genieverse Spotlight - " . $spotlight_meta_description;
	$three_col_portfolio = "set";
	$meta_keyword = $spotlight_meta_keywords;
	$meta_description = $spotlight_meta_description;

	require_once('../Server_Includes/scripts/common_scripts/common_head2.php'); ?><body>
<?php require_once('../Server_Includes/scripts/spotlight_scripts/spotlight_outer_page_header.php'); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <div class="col-md-3">
                <p class="lead">Categories</p>
                <div class="list-group">
					<a href="search.php?category=abduction" class="list-group-item">Abduction</a><?php

		function getCategoryList()
		{
			global $db;
			$query = 'SELECT spotlight_category_id, spotlight_category_name FROM gv_spotlight_category where spotlight_category_lock = 0 ORDER BY spotlight_category_id';
			$result = mysql_query($query, $db) or die(mysql_error($db));

			$row = mysql_fetch_assoc($result);

			//Get modified_for_url function
			require_once('../Server_Includes/scripts/common_scripts/modified_for_url.php');

			while($row = mysql_fetch_assoc($result))
			{
				extract($row);
				echo  '
			<a href="search.php?category=' . modified_for_url($spotlight_category_name, ' ', '-', '') . '" class="list-group-item';
			echo '">' . $spotlight_category_name . '</a>';
			}
		}

		getCategoryList();

				?>
                </div>
            </div>

            <div class="col-md-9">

                <div class="thumbnail">
                    <img class="img-responsive" src="http://placehold.it/800x300" alt="">
                    <div class="caption-full">
                        <h4 class="pull-right"></h4>
                        <h1><a href="post_view.php?id=<?php echo $get_id; ?>"><?php echo $posts_title; ?></a></h1>
                    </div>
                </div>

                <div class="well">

                    <div class="text-right">
                        <a class="pull-left alert alert-info" style="background: #FFFFFF;"><?php echo $total_comments; ?></a>
                        <a class="btn btn-success" href="#comment_form">Click here to comment</a>
                        <a class="btn btn-success" href="">Vote for removing this post</a>
                    </div>

                    <hr><?php
   
	if($rows !== 0)
	{
		while($row = mysql_fetch_assoc($data_p))
		{
			extract($row);
?>
                    <div class="row">
                        <div class="col-md-12">
                            <p><b>On</b> <?php echo change_date_long($row["spotlight_comment_created_on"]); ?>, <b><?php echo member_username($row["member_id"]); ?></b> said:</p>
                            <p style="background: #FFFFFF;" class="alert alert-info"><?php echo $row["spotlight_comment"]; ?></p>
                            <?php /*<a href="">Quote</a> |*/ ?> 
                            <a href="../thanks_for_reporting.php?db=spotlightComment&id=<?php echo $row["spotlight_comment_id"]; ?>">Report</a> | 
                            <?php /*<a href="">Boo!</a> | 
                            <a href="">Hail</a> |*/ ?>
                        </div>
                    </div>

                    <hr>
<?php
		}
?>
                <div class="well">

                    <div class="row" name="comment_form">
                        <div class="col-md-12">
							<div class="form-group col-md-12">
								<?php
	if($rows !== 0)
	{
		$adjacents = 3;
		//$reload = $_SERVER['PHP_SELF'];
		echo paginate_two($page_rows, $pagenum, $rows, $adjacents, $previous, $next, $last);
	}//if($rows !== 0)
	?>
							</div>
                        </div>
                    </div>

                </div>
<?php
	}
	else {
			?>
                    <div class="row">
                        <div class="col-md-12">
                            <p>Sorry. There're no comments yet for this post.</p>
                        </div>
                    </div><?php
	}
?>
                </div>

				<!---->
                <div class="well">

                    <div class="row" name="comment_form">
                        <div class="col-md-12"><?php
	if(isset($_SESSION["logged_in"])){
?>
							<h3>Comment form</h3>
							<hr>
							<form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
								<div class="form-group col-md-12">
									<textarea class="form-control" id="comment" name="comment" placeholder="<?php
	if($rows == 0){	echo 'Hey! Be the first to comment.';	}
									?>"></textarea>
								</div>
								<div class="form-group col-md-3">
									<input class="form-control col-md-4" type="hidden" name="post" id="post" value="<?php echo $get_id; ?>"/>
									<input class="form-control col-md-4" type="submit" name="send" id="send" value="<?php echo $comment_button; ?>"/>
								</div>
							</form><?php
	}//End of if(!isset($_SESSION["logged_in"]))
	else{
?>
							<h4>You have to be logged in to comment</h4><?php
	}
?>
                        </div>
                    </div>

                </div><!---->

				</div>

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

</html><?php mysql_close(); ?>
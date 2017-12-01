<?php

	function get_post($data, $data2)
	{
		global $db;
		$query = 'SELECT board_post_created_on, member_id, board_post_title, board_post_body, board_post_source_website FROM gv_board_post WHERE board_post_id = ' . $data;
		$result = mysql_query($query, $db) or die(mysql_error($db));

		while($row = mysql_fetch_array($result))
		{
			extract($row);
			
			echo '
                    <div class="row">
                        <div class="col-md-12">
                            <p><b>On</b> ' . echo change_date_long($row["board_comment_created_on"]) . ', <b>' . echo member_username($row["member_id"]) . '</b> said:</p>
                            <p style="background: #FFFFFF;" class="alert alert-info">' . echo $row["board_comment"] . '</p>
							<p></p>
                            ' . /*<a href="">Quote</a> |*/ . ' 
                            <a href="../thanks_for_reporting.php?db=boardComment&id=' . echo $row["board_comment_id"] . '">Report</a> | 
                            ' . /*<a href="">Boo!</a> | 
                            <a href="">Hail</a> |*/ . '
                        </div>
                    </div>
';
			
			echo '
                        <h4 class="pull-right">' . $row["board_post_created_on"] . '</h4>
                        <h1><a href="post_view.php?id=' . echo $data . '">' . echo html_entity_decode($row["board_post_title"]) . '</a></h1>';
		}
	}

?><?php

	//Get custom error function script 
	require_once('../Server_Includes/scripts/common_scripts/feature_error_message.php');
	
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
	require_once('../Server_Includes/scripts/board_scripts/functions_get_page_features_for_board.php');
	
	//Get name functions
	require_once('../Server_Includes/scripts/board_scripts/functions_get_name_for_board.php');
		
	require_once('../Server_Includes/scripts/board_scripts/board_meta.php');
	
	require_once('../Server_Includes/scripts/board_scripts/board_footer.php');
	
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
	
	require_once('../Server_Includes/scripts/common_scripts/common_head2.php'); ?><body>
<?php require_once('../Server_Includes/scripts/board_scripts/board_outer_page_header.php'); ?>

    <!-- Page Content -->
    <div class="container">
		<br/><br/><br/><br/>
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
?>		
    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <div class="col-md-3">
                <h3 class="lead">Categories</h3>
                <div class="list-group">
					<a href="search.php?category=abduction" class="list-group-item">Abduction</a><?php

		function getCategoryList()
		{
			global $db;
			$query = 'SELECT board_category_name FROM gv_board_category WHERE board_category_lock = 0 ORDER BY board_category_id';
			$result = mysql_query($query, $db) or die(mysql_error($db));

			$row = mysql_fetch_assoc($result);

			//Get modified_for_url function
			require_once('../Server_Includes/scripts/common_scripts/modified_for_url.php');

			while($row = mysql_fetch_assoc($result))
			{
				extract($row);
				echo  '
			<a href="search.php?category=' . modified_for_url($board_category_name, ' ', '-', '') . '" class="list-group-item';
			echo '">' . $board_category_name . '</a>';
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
                        <h1><a href="post_view.php?id=<?php /*echo $get_id;*/ ?>"><?php /*echo $posts_title;*/ ?>The original post is here</a></h1>
                    </div>
                </div>

                <div class="well">

                    <div class="text-right">
                        <a class="pull-left alert alert-info" style="background: #FFFFFF;"><?php /*echo $total_comments;*/ ?></a>
                        <a class="btn btn-success" href="#comment_form">Comment on this post</a>
                    </div>

                    <hr><?php
/*   
	if($rows !== 0)
	{
		while($row = mysql_fetch_assoc($data_p))
		{
			extract($row);
*/?>
                    <div class="row">
                        <div class="col-md-12">
                            <p><b>On</b> <?php /*echo change_date_long($row["board_comment_created_on"]); ?>, <b><?php echo member_username($row["member_id"]); */?></b> said:</p>
                            <p style="background: #FFFFFF;" class="alert alert-info"><?php /*echo $row["board_comment"];*/ ?></p>
                            <?php /*<a href="">Quote</a> |*/ ?> 
                            <a href="../thanks_for_reporting.php?db=boardComment&id=<?php /*echo $row["board_comment_id"];*/ ?>">Report</a> | 
                            <?php /*<a href="">Boo!</a> | 
                            <a href="">Hail</a> |*/ ?>
                        </div>
                    </div>

                    <hr>
<?php
//		}
?>
                <div class="well">

                    <div class="row" name="comment_form">
                        <div class="col-md-12">
							<div class="form-group col-md-12">
								<?php
/*	if($rows !== 0)
	{
		$adjacents = 3;
		//$reload = $_SERVER['PHP_SELF'];
		echo paginate_two($page_rows, $pagenum, $rows, $adjacents, $previous, $next, $last);
	}//if($rows !== 0)
*/	?>
							</div>
                        </div>
                    </div>

                </div>
<?php
/*	}
	else {
*/			?>
                    <div class="row">
                        <div class="col-md-12">
                            <p>Sorry. There're no comments yet for this post.</p>
                        </div>
                    </div><?php
//	}
?>
                </div>

				<!---->
                <div class="well">

                    <div class="row" name="comment_form">
                        <div class="col-md-12"><?php
//	if(isset($_SESSION["logged_in"])){
?>
							<h3>Comment form</h3>
							<hr>
							<form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
								<div class="form-group col-md-12">
									<textarea class="form-control" id="comment" name="comment" placeholder="<?php
//	if($rows == 0){	echo 'Hey! Be the first to comment.';	}
									?>"></textarea>
								</div>
								<div class="form-group col-md-3">
									<input class="form-control col-md-4" type="hidden" name="post" id="post" value="<?php /*echo $get_id;*/ ?>"/>
									<input class="form-control col-md-4" type="submit" name="send" id="send" value="<?php /*echo $comment_button;*/ ?>"/>
								</div>
							</form><?php
/*	}//End of if(!isset($_SESSION["logged_in"]))
	else{
*/?>
							<h4>You have to be logged in to comment</h4><?php
//	}
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

</html><?php /*mysql_close();*/ ?>
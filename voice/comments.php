<?php

	//Get custom error function script 
	require_once('../Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	$get_id = (isset($_GET["id"])) ? $_GET["id"] : 0;
	$post_id = (isset($_POST["post"])) ? $_POST["post"] : $get_id;
	$comment = (isset($_POST["comment"])) ? htmlspecialchars($_POST["comment"]) : 0;
	$comment_button = 'Upload comment';

	if($post_id == 0)
	{
		//Take this visitor to the log in page because he's not logged in
		header("Location: home.php");
		exit;
	}

	//Connect to the database
	require_once('../Server_Includes/visitordbaccess.php');
	
	//Check if post exists
	function does_this_post_exist($data)
	{
		global $db;
		$query = 'SELECT voice_post_title FROM gv_voice_post WHERE (voice_post_delete = 0 AND voice_member = 1) AND (voice_post_block = 0 AND voice_post_id = ' . $data . ')';
		$result = mysql_query($query, $db) or die (mysql_error($db));
		if(mysql_num_rows($result) < 1)
		{
			$the_number = 0;
		}
		else{
				$the_number = 1;
		}
		return $the_number;
	}

	$post_exists_or_not = does_this_post_exist($post_id);
	
	if($post_exists_or_not !== 1)
	{
		$_SESSION["errand_title"] = 'Sorry!';
		$_SESSION["errand"] = "The post you want to comment on doesn't exist";
		header("Location: ../issues.php");
		exit;
	}

	//Declare errors
	$errors = array();
		
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["send"] == $comment_button)
	{
		//Ensure the recipient exists
		//function 
		
		if(!empty($comment))
		{
			$max_length_of_comment = 500;
			if(str_word_count($comment) > $max_length_of_comment)
			{
				$errors[] = "Your comment is longer than $max_length_of_comment characters.";
			}
		}

		if(count($errors) < 1)
		{
			//Get check_input function
			require_once('../Server_Includes/scripts/common_scripts/check_input.php');
			
			$safe_comment = check_input($comment);
			
			//Now save all in database
			$query = 'INSERT INTO gv_voice_post_comments
						(voice_comment_id, voice_post_id, voice_member, member_id, voice_comment_block, voice_comment_created_on, voice_comment_edited_on, voice_comment)
						VALUES
						(NULL, ' . $post_id . ', 1, ' . $_SESSION["member_id"] . ', 0, NOW(), NOW(), "' . $comment . '")';
			mysql_query($query, $db) or die(mysql_error($db));
		}//End of if(count($errors) < 1)
	}//End of if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["send"] == "Send message")

	//Get page functions
	require_once('../Server_Includes/scripts/voice_scripts/functions_get_page_features_for_voice.php');

	require_once('../Server_Includes/scripts/voice_scripts/voice_meta.php');

	require_once('../Server_Includes/scripts/voice_scripts/voice_footer.php');
	
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
		$query = 'SELECT voice_post_title FROM gv_voice_post WHERE (voice_member = 1 AND voice_post_block = 0) AND (voice_post_id = ' . mysql_real_escape_string($data) . ')';
		$result = mysql_query($query, $db) or die(mysql_error($db));
		$row = mysql_fetch_assoc($result);
		extract($row);
		return ucwords($voice_post_title);
	}

	$posts_title = posts_title($post_id);

	$query = 'SELECT voice_comment_id, member_id, voice_comment_created_on, voice_comment FROM gv_voice_post_comments WHERE (voice_member = 1 AND voice_comment_block = 0) AND (voice_comment_delete = 0 AND voice_post_id = ' . mysql_real_escape_string($post_id, $db) . ')';

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
	require_once('../Server_Includes/scripts/voice_scripts/this_posts_total_comments.php');

	$total_comments = this_posts_total_comments($post_id);

	//Set extra title
	$extra_title =  $posts_title . " comments | Genieverse Voice - Tell the world what's happening. ";

	//Set template page name
	$three_col_portfolio = "set";

	//Set meta details
	$meta_keyword = $voice_meta_keywords;
	$meta_description = $voice_meta_description;

	//Get page head element properties
	require_once('../Server_Includes/scripts/common_scripts/common_head2.php'); ?><body>
<?php require_once('../Server_Includes/scripts/voice_scripts/voice_outer_page_header.php'); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <div class="col-md-3">
                <p class="lead">Categories</p>
                <div class="list-group">
					<a href="search.php?category=Head-Of-State" class="list-group-item">Head of State</a><?php

		function getCategoryList()
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
			echo '">' . $voice_category_name . '</a>';
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
                            <p><b>On</b> <?php echo change_date_long($row["voice_comment_created_on"]); ?>, <b><?php echo member_username($row["member_id"]); ?></b> said:</p>
                            <p style="background: #FFFFFF;" class="alert alert-info"><?php echo htmlspecialchars_decode($row["voice_comment"]); ?></p>
                            <?php /*<a href="">Quote</a> |*/ ?> 
                            <a href="../thanks_for_reporting.php?db=voiceComment&id=<?php echo $row["voice_comment_id"]; ?>">Report</a> | 
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

                    <div class="row">
                        <div class="col-md-12"><?php
	if(isset($_SESSION["logged_in"])){
?>
							<h3 name="comment_form">Comment form</h3>
							<hr>
							<form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
								<div class="form-group col-md-12">
									<textarea class="form-control" id="comment" name="comment" placeholder="<?php
	if($rows == 0){	echo 'Hey! Be the first to comment.';	}
									?>"></textarea>
								</div>
								<div class="form-group col-md-3">
									<input class="form-control col-md-4" type="hidden" name="post" id="post" value="<?php echo $post_id; ?>"/>
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
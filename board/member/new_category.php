<?php

	//Get custom error function script
	require_once('../../Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== 1)
	{
		$_SESSION["errand_title"] = "Log in issue";
		$_SESSION["errand"] = "You must log in first to to create new post.";
		//Take this visitor to the log in page because he's not logged in
		header("Location: ../log_in.php?location=" . urlencode($_SERVER["REQUEST_URI"]));
		exit;
	}

	$category_id = (isset($_POST["category_id"])) ? $_POST["category_id"] : 0;

	//Declare errors
	$errors = array();

	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Submit category")
	{
		if(!isset($category_id) || $category_id == 0)
		{
			$errors[] = "You didn't select a category.";
		}
		elseif($category_id > 24)
		{
			$errors[] = 'Your chosen category is invalid.';
		}
		
		if(count($errors) < 1)
		{
			header("Location: new_post.php?id=$category_id");
			exit;
		}
	}

	//Connect to the database
	require_once('../../Server_Includes/visitordbaccess.php');
	
	//Get Spotlight meta detail
	require_once('../../Server_Includes/scripts/spotlight_scripts/spotlight_meta.php');
	
	//Get Genieverse Spotlight footer function
	require_once('../../Server_Includes/scripts/spotlight_scripts/spotlight_member_footer.php');
	
	$extra_title = "New post category | Genieverse Spotlight - " . $spotlight_title_description;
	$shop_item = "set";
	$meta_keyword = $spotlight_meta_keywords;
	$meta_description = $spotlight_meta_description;

	require_once('../../Server_Includes/scripts/common_scripts/common_member_page_head2.php'); ?><body>
<?php require_once('../../Server_Includes/scripts/spotlight_scripts/spotlight_member_page_header.php'); ?>
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

	if($errors)
	{
?>
		<div class="row">
            <div class="col-lg-12 text-center">
				<div class="alert alert-danger">
				<p class="alert alert-danger">You can't proceed due to the following:</p>
				<br>
				<?php
					echo '<ul>';
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
		<div class="row">

            <div class="col-md-3">
                <p class="lead">Categories</p>
                <div class="list-group">
					<a href="../search.php?category=abduction" class="list-group-item">Abduction</a><?php

		function getCategoryList()
		{
			global $db;
			$query = 'SELECT spotlight_category_id, spotlight_category_name FROM gv_spotlight_category where spotlight_category_lock = 0 ORDER BY spotlight_category_id';
			$result = mysql_query($query, $db) or die(mysql_error($db));

			$row = mysql_fetch_assoc($result);
			
			//Get modified_for_url function
			require_once('../../Server_Includes/scripts/common_scripts/modified_for_url.php');
			
			while($row = mysql_fetch_assoc($result))
			{
				extract($row);
				echo  '
			<a href="../search.php?category=' . modified_for_url($spotlight_category_name, ' ', '-', '') . '" class="list-group-item">' . $spotlight_category_name . '</a>';
			}
		}

		getCategoryList();

				?>
                </div>
            </div>

            <div class="col-md-9">
			
				<div class="row">
					<div class="col-md-12">
						<span class="pull-right"></span>
						<h3>New post category</h3>
					</div>
				</div>

                <hr>
				
				<div class="row">
					<div class="col-md-12">
						</span>
						<p class="alert alert-danger"><b>Posts not in appropriate category will be REMOVED.</b></p>
					</div>
				</div>
				
				<hr>
				
				<div class="row">
					<div class="col-md-12">
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
						<span class="pull-right"><input type="submit" name="submit" value="Submit category" /></span>
						<p><select name="category_id">
							 <option value="0">Select category</option>
							 <?php
								$query = 'SELECT spotlight_category_id, spotlight_category_name FROM gv_spotlight_category';
								$result = mysql_query($query, $db) or die (mysql_error($db));

								while($row = mysql_fetch_array($result))
								{
								?>
								<option value="<?php echo $row["spotlight_category_id"]; ?>"><?php echo $row["spotlight_category_name"] ?></option>
								<?php
								}
							?>
							</select></p>
						</form>
					</div>
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

<?php require_once('../../Server_Includes/scripts/common_scripts/member_page_common_before_body_end2.php'); ?>

</body>

</html><?php
	mysql_close($db);
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); 
	$_SESSION = array();
	}
?>
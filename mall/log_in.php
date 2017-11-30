<?php

	//Get custom error function script 
	require_once('../Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	//Filter incoming values
	$username = (isset($_POST["username"])) ? trim($_POST["username"]) : "Username";
	$password = (isset($_POST["password"])) ? trim($_POST["password"]) : "Password";
	$url_location = (isset($_GET["location"])) ? htmlspecialchars($_GET["location"]) : "";
	$previous_location = (isset($_POST["location"])) ? $_POST["location"] : $url_location;
	$default_destination = 'member/dashboard.php';
	
	if(isset($_SESSION["logged_in"]))
	{
		header("Location: member/dashboard.php");
		exit;
	}

	$no_robots = 'No crawling';

	require_once('../Server_Includes/scripts/common_scripts/log_in_processing_services.php');

	$extra_title = "Log in | Genieverse Mall - Free web market";

	//Get meta details
	require_once('../Server_Includes/scripts/mall_scripts/mall_meta.php');
	require_once('../Server_Includes/scripts/mall_scripts/mall_footer.php');

	$meta_keyword = $mall_meta_keywords;
	$meta_description = $mall_meta_description;
	$default_destination = 'member/dashboard.php';
	$login = "set";
	
	require_once('../Server_Includes/scripts/common_scripts/common_head2.php'); ?><body>
	<?php require_once('../Server_Includes/scripts/mall_scripts/mall_outer_page_header.php'); ?>
    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
				<br/>
				<br/>
                <h1 class="page-header"><small>Mall Log in</small></h1>		
            </div>
        </div>
        <!-- /.row -->
<?php
/*		<div class="row">
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
<?php
	if($errors)
	{
		echo '
			<div class="row">
				<div class="alert alert-danger col-sm-12 text-center">
					';
			foreach($errors as $error)
			{
				echo '<p class="intro-text text-center">' . $error . '</p>' . "\n";
			} 
		echo '
				</div>
			</div>';
	}
	
	
	if($_SESSION["errand"])
	{
		echo '
			<div class="row">
				<div class="alert alert-danger col-sm-12 text-center">
					';
			echo '<p class="intro-text text-center">' . $_SESSION["errand"] . '</p>';
		echo '
				</div>
			</div>';
	}
?>
		<?php
					$custom_forgotten_path = '../';
					require_once('../Server_Includes/scripts/common_scripts/log_in_form2.php');  
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
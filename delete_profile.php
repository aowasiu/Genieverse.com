<?php 

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== 1)
	{
		//Take this visitor to the log in page because he's not logged in
		header("Location: log_in.php?location=" . urlencode($_SERVER["REQUEST_URI"]));
		exit;
	}
	
	//Connect to the database
	require_once('Server_Includes/visitordbaccess.php');
	
	//Delete current member's account from the database
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Yes, I'm certain")
	{
		$query = 'DELETE * 
				FROM gv_mall_post, gv_mall_post_image 
				WHERE 
				gv_mall_post.mall_post_id = gv_mall_post_image.mall_post_id 
				AND gv_mall_post.member_id = ' . mysql_real_escape_string($_SESSION["member_id"], $db);
		mysql_query($query, $db) or die (mysql_error($db));

		$query = 'DELETE FROM gv_mall_post WHERE member_id = ' . mysql_real_escape_string($_SESSION["member_id"], $db);
		mysql_query($query, $db) or die (mysql_error($db));
		
		$query = 'DELETE FROM gv_member_profile WHERE member_id = ' . mysql_real_escape_string($_SESSION["member_id"], $db);
		mysql_query($query, $db) or die (mysql_error($db));
		
		$query = 'DELETE FROM gv_members WHERE member_id = ' . mysql_real_escape_string($_SESSION["member_id"], $db);
		mysql_query($query, $db) or die (mysql_error($db));

		//Log out this former member by emptying all session  variables
		$_SESSION = array();
		session_destroy();
		
		//Now send him out with a little request
		$_SESSION["errand_title"] = "Sadly, you've been removed from Genieverse.";
		$_SESSION["errand"] = "To help us improve our services, please click ";
		$_SESSION["errand"] .= '<a href="correspondence.php">Contact Us</a> to take a moment to tell us why you decided to leave.';
		header("Location: issues.php");
		exit;
	}
	
	//Get firstname function
	require_once('Server_Includes/scripts/common_scripts/get_name_functions.php');

	//
	$firstname = firstname($_SESSION["member_id"]);
	
	$extra_css = '<link href="css/genieverse_shell_css/centered_content_widescreen.css" rel="stylesheet" type="text/css" media="only screen and (min-device-width:421px),(min-width:421px),(orientation:landscape)">' . "\n" . '<link href="css/genieverse_shell_css/centered_content_mobile.css" rel="stylesheet" type="text/css" media="only screen and (max-device-width:420px),(max-width:420px),(orientation:portrait)">' . "\n" . '<link href="css/page_bottom_extension.css" rel="stylesheet" type="text/css" media="only screen and (min-device-width:421px),(min-width:421px),(orientation:landscape)">';

	//Set extra title
	$extra_title = "Deactivating " . $_SESSION['username'] . "'s Account ";

	//Set page template name
	$shop_item = "set";

	//Set extra script
	$extra_script = "\n" . "<script type=\"text/javascript\">
		 window.onLoad = function()
		 {	document.getElementById('cancel') .onclick = goBack;	}
		 function goBack()
		 {	history.go(-1);}
	</script>" . "\n";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">
    
		<div class="caption-full">
            <h5 class="pull-right"></h5>
			<h3>Warning <?php echo $firstname; ?>: Account Deactivation</h3>
			<hr>
		</div>
	
		<div class="col-lg-12">
			<div class="row text-center">
				<p><b><?php echo $firstname; ?></b>, You are about to deactivate your account.</p>
				<p>Are you sure you want to deactivate your account?</p>
				<p><strong>Once you confirm the deactivation, you won't be able to use any <a href="index.php">Genieverse</a> service anymore.</strong></p>
			</div>
		</div>
			
		<form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<div class="form-group col-lg-12">
			<div class="row text-center">
				<div class="col-md-12">
					<span class="pull-right"></span>
					<h4></h4>
					<input class="btn btn-default" type="submit" name="submit" value="Yes, I'm certain">
					<input class="btn btn-default"  type="button" id="cancel" value="No" onclick="history.go(-1);">
				</div>
			</div>
		</div>

		</form>

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

<?php require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_before_body_end.php'); ?>

</body>

</html><?php	mysql_close();	?>
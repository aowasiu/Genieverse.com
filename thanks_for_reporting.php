<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	//Filter incoming values
	$id = (isset($_GET['id'])) ? $_GET['id'] : 0;
	$the_table = (isset($_GET['db'])) ? $_GET['db'] : "";

	//Ensure there is no attempted hack
	if($id == 0 || $the_table == "")
	{
		header("Location: index.php");
		exit;
	}

	//Set extra title
	$extra_title = "Letter from the founder";	

	//Set page template name
	$shop_item = "set";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">
    
		<div class="caption-full">
            <h5 class="pull-right"></h5>
			<h3>Letter from the Founder</h3>
			<hr>
			<p>Thank you for helping us improve usage of <a href="index.php">Genieverse</a> services.</p>
			<p>Your report will be reviewed and the reported material will be deleted, consequently, if found in violation of <a href="terms_of_use.php">Terms of Use</a> or guilty of your report and/or comment.</p>
			<p>We, however, require your full name and an active email address to keep you informed on review decision and status of the reported material.</p>
			<p>It is worthy of note that reports lacking full name and an active email address will not be honoured.</p>
			<p>Once again, thank you.</p>
			<br/>
			<p>Wasiu Adisa</p>
			<p>Founder,</p>
			<p>Genieverse.</p>
			<hr>
			<p><a class="btn btn-info" href="report.php?id=<?php echo $id; ?>&db=<?php echo $the_table; ?>">Proceed with reporting</a></p>
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

<?php require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_before_body_end.php'); ?>

</body>

</html>
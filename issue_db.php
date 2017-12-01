<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');

	//Ensure there is a database problem, else redirect with a warning.
	if(!isset($_SESSION["db_issue"])
	{
		$_SESSION["errand_title"] = "Warning!";
		$_SESSION["errand"] = "You're attempting an illegal request. If you're lost, <a href='index.php'>click here to go to the Genieverse home page.</a>";
		header("Location: issues.php");
		exit;
	}

	//Set page template name
	$three_col_portfolio = "set";

	//Set extra title
	$extra_title = 'Database problem';

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><small>Sorry, there's a problem!</small></h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Projects Row -->

        <div class="row">
			<div class="col-lg-12">
				<p>Genieverse server is unreachable now. Please try later.</p>
			</div>
        </div>
		<!-- /.row -->

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

<?php require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_before_body_end.php'); ?>

</body>

</html><?php
unset($_SESSION["db_issue"]);
?>
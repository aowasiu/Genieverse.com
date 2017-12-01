<?php

	//Get custom error function script
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');

	//Set default issue title and issue message
	$issue_title = (isset($_SESSION["errand_title"])) ? $_SESSION["errand_title"] : "";
	$issue_message = (isset($_SESSION["errand"])) ? $_SESSION["errand"] : "";
	
	//Ensure issue title and issue message(s) are set, else redirect to index.php
	if($issue_title == "" || $issue_message == "")
	{
		$_SESSION["errand_title"] = "Welcome to Genieverse";
		$_SESSION["errand"] = "<a href='join_us.php'>Join us</a> to explore our virtual universe";
		$_SESSION = array();
		header("Location: index.php");
		exit;
	}

	//Set page template name
	$three_col_portfolio = "set";

	//Set extra title
	$extra_title = $issue_title;

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><small><?php echo $issue_title ?></small></h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Projects Row -->

        <div class="row">
			<div class="col-lg-12">
				<p><?php echo $issue_message; ?></p>
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
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
	if(isset($_SESSION["keep_session"])){ unset($_SESSION["keep_session"]); }
?>
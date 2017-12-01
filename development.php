<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');

	//Set extra title
	$extra_title = "Still in development " . " | Genieverse Mall - Free web market ";

	//Set page template name
	$three_col_portfolio = "set";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><small>Still in development</small></h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Projects Row -->

        <div class="row">
			<div class="col-lg-12">
				<p>Sorry, but this section of <a href="index.php">Genieverse</a> is still in development.<br>Check in December, <?php echo date('Y'); ?>.</p>
			</div>
        </div>
		<!-- /.row -->
        <hr>

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

</html>
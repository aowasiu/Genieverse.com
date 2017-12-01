<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');

	//Set page template name
	$three_col_portfolio = "set";

	//Set extra title
	$extra_title = "Contact us ";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body style="background-color:#FFF;">
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><small>Contact us</small></h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Projects Row -->

        <div class="row">
			<div class="col-lg-12 text-center">
				<p class="btn btn-default"><a href="correspondence.php">Click here for correspondence</a></p>
			</div>
        </div> 
		<br>
        <div class="row">
			<div class="col-lg-12 text-center">
				<p class="btn btn-default"><a href="correspondence.php">Click here to send us a complaint</a></p>
			</div>
        </div>
		<br>
        <div class="row">
			<div class="col-lg-12 text-center">
				<p class="btn btn-default"><a href="sitemap.php">Click here for site map</a></p>
			</div>
        </div>
		<br>
        <div class="row">
			<div class="col-lg-12 text-center">
				<p class="btn btn-default"><a href="marketing.php">Click here to buy AdSpace</a></p>
			</div>
        </div>
		<br>
        <div class="row">
			<div class="col-lg-12 text-center">
				<p class="btn btn-default"><a href="marketing.php">Click here to create AdPost</a></p>
			</div>
        </div>
		<br>
        <div class="row">
			<div class="col-lg-12 text-center">
				<p class="btn btn-default"><a href="development.php">Click here get update on your<br> AdPost and/or AdSpace</a></p>
			</div>
        </div>
		<br>
        <div class="row">
			<div style="margin-bottom: 5%;" class="col-lg-12 text-center">
				<p class="btn btn-default"><a href="thewebdeveloper/index.php">Meet the site owner</a></p>
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

</html>
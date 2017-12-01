<?php

	//Get custom error function script
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	if(!isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] !== 1)
	{
		//Take this visitor to the log in page because he's not logged in
		header("Location: log_in.php?location=" . urlencode($_SERVER["REQUEST_URI"]));
		exit();	
	}

	//Set extra title
	$extra_title = "Services";

	//Set page template name
	$shop_item = "set";

	//Get page head element properties	
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">
    
		<div class="caption-full">
            <h3 class="pull-right"></h3>
			<h3>Genieverse Services</h3>
			<hr>
		</div>

		    
		<?php
			if(isset($_SESSION["errand"]))
			{
				echo '<div class="caption-full">
			<h3 class="text-center alert alert-success">' . $_SESSION["errand"] . '</h3>
			<hr>
		</div>';
			} ?>

		<div class="col-md-12">

				<div class="row">
					<div class="col-md-12">
						<span class="btn btn-default pull-right"><b><a href="messages.php">Private Messages</a></b></span>
					</div>
				</div>

				<hr>

				<div class="row">
					<div class="col-md-12">
						<span class="pull-right btn btn-default"><a href="mall/member/dashboard.php">Dashboard</a></span>
						<p><b><a href="mall/home.php">Genieverse Mall - Free web market.</a></b></p>
					</div>
				</div>

				<br>
				
				<hr>

				<div class="row">
					<div class="col-md-12">
						<span class="pull-right btn btn-default"><a href="voice/member/dashboard.php">Dashboard</a></span>
						<p><b><a href="voice/home.php">Genieverse Voice - Tell the what's happening world.</a></b></p>
					</div>
				</div>

				<br>
				
				<hr>

				<div class="row">
					<div class="col-md-12">
						<span class="pull-right btn btn-default"><a href="spotlight/member/dashboard.php">Dashboard</a></span>
						<p><b><a href="spotlight/home.php">Genieverse Spotlight - Exposing criminals.</a></b></p>
					</div>
				</div>

				<br>
				
				<hr>

				<div class="row">
					<div class="col-md-12">
						<span class="pull-right btn btn-default"><a href="board/dashboard.php">Dashboard</a></span>
						<p><b><a href="board/home.php">Genieverse Board - Roundtable Forum.</a></b></p>
					</div>
				</div>

				<br>

				<hr>
<?php
/*				
		<h3 style="padding: 3%; margin-top: -5%;"></h3>
  <p style="padding: 3%; margin-top: -5%;"><a href="messages.php">Private Messages</a></p>
  <?php if(isset($_SESSION["errand"])){ ?><hr noshade style="color: #EEBC1D; width: 100%;"><p style="padding: 3%;"><b><?php echo $_SESSION["errand"]; ?></b></p><hr noshade style="color: #EEBC1D; width: 100%;"><br/><?php } ?>
  <p style="padding: 3%; margin-top: -5%;">Explore <a href="index.php">Genieverse</a> by selecting any of the services below:</p>
	<table id="language_choice"  style="margin-top: -5%; margin-
            
<?php
	*//*            <tr>
            	<td><p><a href="#">Construct</a>  </p></td>
                <td><p> <a href="#">Realty redefined</a></p></td>
				<td></td>
				<td><p><a href="#">Dashboard</a>  </p></td>
            </tr>
			<tr>
            	<td><p><a href="#">Hearts</a>  </p></td>
                <td><p>- <a href="#">Two hearts as one.</a></p></td>
            </tr>
            
            <tr>
            	<td><p><a href="p_dashboard.php">Parliament</a>  </p></td>
                <td><p>- <a href="p_dashboard.php">One nation, one people.</a></p></td>
            </tr>
			<tr>
            	<td><p><a href="#">Planet</a>  </p></td>
                <td><p> <a href="#">Virtual planet.</a></p></td>
				<td></td>
				<td><p><a href="#">Dashboard</a>  </p></td>
            </tr>
					*/
	?>
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

</html><?php 
	if(isset($_SESSION["errand"]) || isset($_SESSION["errand_title"])){
	unset($_SESSION["errand"]);
	unset($_SESSION["errand_title"]); } ?>
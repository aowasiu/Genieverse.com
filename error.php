<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	$no_robots = 'No crawling';

	//Set extra title
	$extra_title = "Genieverse Error page - " . $_SERVER["QUERY_STRING"] . ' Error';

	//Set extra title
	$three_col_portfolio = "set";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
                <?php
	switch($_SERVER["QUERY_STRING"])
	{
		case 400:
		 echo '<h1 class="page-header"><small>Bad Request</small></h1>';
		 echo '<h2>Error code 400</h2>';
		 echo '<p>You have made a bad request to Genieverse servers.</p>';
		 echo '<br><br>';
		 echo '<p>Please select links in the navigation to guide you to what you\'re looking for.</p>';
		 break;
		
		case 401:
		 echo '<h1 class="page-header"><small>Authorization Required</small></h1>';
		 echo '<h2>Error code 401</h2>';
		 echo '<p>Sorry! You provided wrong information while attempting to access secure resource.</p>';
		 break;
		
		case 403:
		 echo '<h1 class="page-header"><small>Access Forbidden</small></h1>';
		 echo '<h2>Error code 403</h2>';
		 echo '<p>You have been denied access to a secure resource.</p>';
		 break;
		
		case 404:
		 echo '<h1 class="page-header"><small>Page Not Found</small></h1>';
		 echo '<h2>Error code 404</h2>';
		 echo '<p>We are sorry. The page you want is not available anymore.</p>';
		 break;
		
		case 500:
		 echo '<h1 class="page-header"><small>Internal Server Error</small></h1>';
		 echo '<h2>Error Code 500</h2>';
		 echo '<p>Sorry! Genieverse servers encountered an internal server error.</p>';
		 break;
		
		default:
		 echo '<h1 class="page-header"><small>Error!</small></h1>';
		 echo '<p>Sorry, there was an error.</p>';
		 echo '<p>But we are looking into the problem.</p>';
	}
		echo '<p>If this problem persists,</p>';
		echo '<p>Click <a href="contact_us/correspondence.php">here to contact the technical staff</a></p>';
?>
            </div>
        </div>
        <!-- /.row -->

<?php		
		$now = (isset($_SERVER["REQUEST_URI"]))	?	$_SERVER["REQUEST_URI"]	:	time('g:i A');
		
		$page = (isset($_SERVER["QUERY_STRING"]))	?	$_SERVER["REQUEST_URI"]	:	"unknown page";
		
		if($_SERVER["QUERY_STRING"]	== "403" or "401")
		{
			$msg	=	wordwrap("A" . $_SERVER["QUERY_STRING"] . " error was encountered on " . date('l F d, Y') . " - " . $now . " because a visitor attempted to access secure resources via " . $page . ".");
		}
		else {
			$msg = wordwrap("A " . $_SERVER["QUERY_STRING"] . " error was encountered on " . date('l F d, Y') . " - " . $now . " when a visitor wanted to view " . $page . ".");
		}
		
		mail('error_report@genieverse.com', "Error report from Genieverse Custom Error page", $msg);
		
   ?> <hr>

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
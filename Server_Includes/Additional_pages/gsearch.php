<?php

	//Get custom error function script 
	require_once('../Server_Includes/scripts/common_scripts/feature_error_message.php');
		
	//Connect to the database
	require_once('../Server_Includes/visitordbaccess.php');
	
	require_once('../Server_Includes/scripts/voice_scripts/voice_meta.php');
	
	require_once('../Server_Includes/scripts/voice_scripts/voice_footer.php');
		
	$extra_title = "Google Search | Genieverse Voice - Tell the world what's happening.";

	$three_col_portfolio = "set";
	$meta_keyword = $voice_meta_keywords;
	$meta_description = $voice_meta_description;
	
	require_once('../Server_Includes/scripts/common_scripts/common_head.php'); ?><body>
<?php require_once('../Server_Includes/scripts/voice_scripts/voice_outer_page_header.php'); ?>
    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><small>Google Search</small></h1>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
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
        <hr>

		<!-- Projects Row -->
        <div class="row">
		<script>
		  (function() {
			var cx = '012613267761486233772:ghaxxv6xuaa';
			var gcse = document.createElement('script');
			gcse.type = 'text/javascript';
			gcse.async = true;
			gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
				'//cse.google.com/cse.js?cx=' + cx;
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(gcse, s);
		  })();
		 </script>
		<gcse:searchbox-only resultsUrl="m_gsearch.php"></gcse:searchbox-only>
		</div>
		<!-- /.row -->
        <hr>

        <!-- Projects Row -->
        <div class="row">
		<script>
			(function() {
			  var cx = '012613267761486233772:ghaxxv6xuaa';
			  var gcse = document.createElement('script');
			  gcse.type = 'text/javascript';
			  gcse.async = true;
			  gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
				  '//cse.google.com/cse.js?cx=' + cx;
			  var s = document.getElementsByTagName('script')[0];
			  s.parentNode.insertBefore(gcse, s);
			})();
		</script>
		<gcse:searchresults-only></gcse:searchresults-only>
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

<?php require_once('../Server_Includes/scripts/common_scripts/common_before_body_end.php'); ?>

</body>

</html><?php 
	mysql_close();
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
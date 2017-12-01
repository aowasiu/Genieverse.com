<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');

	//Set page template name
	$three_col_portfolio = "set";

	//Set extra title
	$extra_title = "Sitemap ";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body style="background-color:#fff;">
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><small>Sitemap</small></h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Projects Row -->

        <div class="row">
			<div class="col-lg-12 text-center">
				<h3><a href="index.php">Genieverse</a></h3> 
				<ul class="list-unstyled">
					<li><a href="index.php">Genieverse Home</a></li>
					<li><a href="join_us.php">Join us</a></li>
					<li><a href="log_in.php">Log in</a></li>
					<li><a href="forgotten_pass.php">Forgotten password</a></li>
					<li><a href="privacy_policy.php">Privacy policy</a></li>
					<li><a href="terms_of_use.php">Terms of Use</a></li>
					<li><a href="contact_us.php">Contact us</a></li>
					<li><a href="marketing.php">Advertisement</a></li>
				</ul>
			</div>
        </div>
		<br>
        <div class="row">
			<div class="col-lg-3 text-center">
				<h3><a href="mall/home.php">Genieverse Mall</a></h3> 
				<ul class="list-unstyled">
					<li><a href="mall/home.php">Mall Home</a></li>
					<li><a href="mall/log_in.php">Mall Log in</a></li>
					<li><a href="mall/recent.php">Recent Mall posts</a></li>
					<li><a href="mall/bargains.php">Mall Bargain posts</a></li>
					<li><a href="mall/rss.php">Mall RSS Feed</a></li>
				</ul>
			</div>
			<div class="col-lg-3 text-center">
				<h3><a href="voice/home.php">Genieverse Voice</a></h3> 
				<ul class="list-unstyled">
					<li><a href="voice/home.php">Voice Home</a></li>
					<li><a href="voice/log_in.php">Voice Log in</a></li>
					<li><a href="voice/recent.php">Recent Voice posts</a></li>
					<li><a href="voice/loud_voices.php">Loud Voice posts</a></li>
					<li><a href="voice/rss.php">Voice RSS Feed</a></li>
				</ul>
			</div>
			<div class="col-lg-3 text-center">
				<h3><a href="spotlight/home.php">Genieverse Spotlight</a></h3> 
				<ul class="list-unstyled">
					<li><a href="spotlight/home.php">Spotlights Home</a></li>
					<li><a href="spotlight/log_in.php">Spotlight Log in</a></li>
					<li><a href="spotlight/recent.php">Recent Spotlight posts</a></li>
					<li><a href="spotlight/following.php">Followed Spotlight posts</a></li>
					<li><a href="spotlight/rss.php">Spotlight RSS Feed</a></li>
				</ul>
			</div>
			<div class="col-lg-3 text-center">
				<h3><a href="board/home.php">Genieverse Board</a></h3> 
				<ul class="list-unstyled">
					<li><a href="board/home.php">Board Home</a></li>
					<li><a href="board/log_in.php">Board Log in</a></li>
					<li><a href="board/recent.php">Recent Board posts</a></li>
					<li><a href="board/mod_focus.php">Mod Focus</a></li>
					<li><a href="board/hot_topics.php">Hot Topics</a></li>
					<li><a href="board/rss.php">Board RSS Feed</a></li>
				</ul>
			</div>
			<div class="col-lg-3 text-center">
				<h3><a href="hearts/home.php">Genieverse Hearts</a></h3> 
				<ul class="list-unstyled">
					<li><a href="hearts/home.php">Hearts Home</a></li>
					<li><a href="hearts/log_in.php">Hearts Log in</a></li>
					<li><a href="hearts/recent.php">Recent Hearts Profiles</a></li>
					<li><a href="hearts/feature_profiles.php">Featured Profiles</a></li>
					<li><a href="hearts/rss.php">Hearts RSS Feed</a></li>
				</ul>
			</div>
        </div>
		<br>
		<br>
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

</html><?php mysql_close(); ?>
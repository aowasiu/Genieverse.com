
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
        <div class="container topnav">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <?php echo $mobile_view_menu; ?>
                </button>
                <a class="navbar-brand topnav" href="../index.php">Genieverse</a>
                <a class="navbar-brand topnav" href="home.php">Hearts</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li<?php if(isset($search)){ echo ' style="background-color: #000;"'; } ?>>
                        <a href="search.php">Search</a>
                    </li>
                    <li<?php if(isset($featured_submit)){ echo ' style="background-color: #000;"'; } ?>>
                        <a href="featured_submit.php">Submit for Feature</a>
                    </li>
                    <li<?php if(isset($featured)){ echo ' style="background-color: #000;"'; } ?>>
                        <a href="featured.php">Featured Profiles</a>
                    </li>
					<li<?php if(isset($sweethearts)){ echo ' style="background-color: #000;"'; } ?>>
                        <a href="sweethearts.php">Sweethearts</a>
                    </li>
                    <li<?php if(isset($latest)){ echo ' style="background-color: #000;"'; } ?>>
                        <a href="latest.php">Latest Profiles</a>
                    </li><?php
		if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == 1)
		{
?>
					<li>
						<a href="member/dashboard.php"><?php if(isset($dashboard)){echo $_SESSION["username"];}else{ echo "Dashboard";} ?></a>
					</li>
					<li>
						<a href="member/log_out.php">Log out</a>
					</li><?php
			}
			else {
?>
                    <li>
                        <a href="../join_us.php">Join us</a>
                    </li>
                    <li<?php if(isset($log_in)){ echo ' style="background-color: #000;"'; } ?>>
                        <a href="log_in.php">Log in</a>
                    </li><?php
			}
?>
                    <li>
                        <a href="../correspondence.php">Contact us</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

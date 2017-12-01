
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Genieverse Spotlight</span>
                    <?php echo $mobile_view_menu; ?>
                </button>
                <a class="navbar-brand" href="../../index.php">Genieverse </a><a class="navbar-brand" href="../home.php">Spotlight</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li<?php if(isset($special)){ echo ' class="active"'; } ?>>
                        <a href="../following.php">Following</a>
                    </li>
					<li<?php if(isset($recent)){ echo ' class="active"'; } ?>>
                        <a href="../recent.php">Recent Spotlights</a>
                    </li><?php
			if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == 1)
			{
?>
					<li>
						<a href="dashboard.php"><?php if(isset($dashboard)){echo ucfirst($_SESSION["username"]);}else{ echo "Dashboard";} ?></a>
					</li><li>
						<a href="new_category.php">Create new post</a>
					</li>
					<li>
						<a href="../../services.php">Explore Genieverse</a>
					</li>
					<li>
						<a href="log_out.php">Log out</a>
					</li><?php
			}
			else {
?>
                    <li>
                        <a href="../../join_us.php">Join us</a>
                    </li>
                    <li>
                        <a href="../log_in.php">Log in</a>
                    </li><?php
			}
?>
                    <li>
                        <a href="../rss.php">Spotlight RSS</a>
                    </li>
					<li>
                        <a href="../../marketing.php">Advertise</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

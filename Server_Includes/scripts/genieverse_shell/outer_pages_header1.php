
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Genieverse</span>
                    <?php echo $mobile_view_menu; ?>
                </button>
                <a class="navbar-brand" href="index.php">Genieverse </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
					<?php
		if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == 1)
		{
?>
					<li<?php if(isset($services)){ echo ' class="active"'; } ?>>
						<a href="services.php"><?php if(isset($services)){echo $_SESSION["username"];}else{ echo "Explore Genieverse";} ?></a>
					</li>
					<li>
						<a href="profile.php"><b><?php echo ucfirst($_SESSION["username"]); ?>'s profile</b></a>
					</li>
					<li>
						<a href="password_change.php">Change password</a>
					</li>
					<li>
						<a href="log_out.php">Log out</a>
					</li><?php
			}
			else {
?>
                    <li>
                        <a href="join_us.php">Join us</a>
                    </li>
                    <li>
                        <a href="log_in.php">Log in</a>
                    </li><?php
			}
?>
					<li>
                        <a href="marketing.php">Advertise</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

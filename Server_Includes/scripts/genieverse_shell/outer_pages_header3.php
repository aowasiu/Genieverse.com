
    <div class="brand" style="background: #EEE;"><a href="index.php">Genieverse</a></div>
    <div class="address-bar">Virtual universe</div>

	<!-- Navigation -->
    <nav class="navbar navbar-default" role="navigation" style="background: #EEE;">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Genieverse</span>
                    <?php echo $mobile_view_menu; ?>
                </button>
                <!-- navbar-brand is hidden on larger screens, but visible when the menu is collapsed -->
                <a class="navbar-brand" href="index.php">Genieverse</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
					<li>
                        <a href="mall/home.php">Mall</a>
                    </li>
					<li>
                        <a href="voice/home.php">Voice</a>
                    </li>
					<li>
                        <a href="spotlight/home.php">Spotlight</a>
                    </li>
					<li>
                        <a href="board/home.php">Board</a>
                    </li>
					<li>
                        <a href="hearts/home.php">Hearts</a>
                    </li>
					<li> </li><?php
		if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == 1)
   		{
?>
					
                    <li>
						<a href="services.php">Explore</a>
					</li>
					<li>
                        <a href="profile.php"><?php echo ucfirst($_SESSION["username"]); ?></a>
                    </li>
                    <li>
                        <a href="log_out.php">Log out</a>
                    </li><?php
			}
			else {
?>

                    <li<?php if(isset($join_us)){ echo ' class="active"';} ?>>
                        <a href="join_us.php">Join us</a>
                    </li>
                    <li<?php if(isset($log_in)){ echo ' class="active"';} ?>>
                        <a href="log_in.php">Log in</a>
                    </li><?php
			}
?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

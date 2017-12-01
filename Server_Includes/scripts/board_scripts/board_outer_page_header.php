
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Genieverse Board</span>
                    <?php echo $mobile_view_menu; ?>
				</button>
                <a class="navbar-brand" href="../index.php">Genieverse </a><a class="navbar-brand" href="home.php">Board</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
					<li<?php if(isset($mod_focus)){ echo ' class="active"'; } ?>>
                        <a href="mod_focus.php">Mod Focus</a>
                    </li>
                    <li<?php if(isset($special)){ echo ' class="active"'; } ?>>
                        <a href="hot_topics.php">Hot Topics</a>
                    </li>
                    <li<?php if(isset($new)){ echo ' class="active"'; } ?>>
                        <a href="new_topics.php">New Topics</a>
                    </li>
					<li<?php if(isset($recent)){ echo ' class="active"'; } ?>>
                        <a href="recent.php">Recent</a>
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
                    <li>
                        <a href="log_in.php">Log in</a>
                    </li><?php
			}
?>
                    <li>
                        <a href="rss.php">Board RSS</a>
                    </li>
					<li>
                        <a href="../marketing.php">Advertise</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

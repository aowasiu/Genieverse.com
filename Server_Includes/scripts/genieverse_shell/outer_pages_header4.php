
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
						<div id="malltag" class="menu" style="display:none;">
						<ul class="taglist" style="display:none;">
							<li class="menu-item"><a href="mall/home.php">Mall Home</a></li>
							<li class="menu-item"><a href="mall/bargains.php">Mall Bargains</a></li>
							<li class="menu-item"><a href="mall/recent.php">Recent listings</a></li>
							<li class="menu-item"><a href="mall/rss.php"></a>Mall RSS</li>
							<li class="menu-item"><a href="mall/log_in.php"></a>Log into Mall</li>
						</ul>
						</div>
                    </li>
					<li>
                        <a href="voice/home.php">Voice</a>
						<div id="voicetag" class="menu" style="display:none;">
						<ul class="taglist" style="display:none;">
							<li class="menu-item"><a href="voice/home.php">Voice Home</a></li>
							<li class="menu-item"><a href="voice/loud_voices.php">Loud Voices</a></li>
							<li class="menu-item"><a href="voice/recent.php">Recent Voices</a></li>
							<li class="menu-item"><a href="voice/rss.php"></a>Voice RSS</li>
							<li class="menu-item"><a href="voice/log_in.php"></a>Log into Voice</li>
						</ul>
						</div>
                    </li>
					<li>
                        <a href="Spotlight/home.php">Spotlight</a>
						<div id="Spotlighttag" class="menu" style="display:none;">
						<ul class="taglist" style="display:none;">
							<li class="menu-item"><a href="Spotlight/home.php">Spotlight Home</a></li>
							<li class="menu-item"><a href="Spotlight/following.php">Spotlight Following</a></li>
							<li class="menu-item"><a href="Spotlight/recent.php">Recent Spotlight</a></li>
							<li class="menu-item"><a href="Spotlight/rss.php"></a>Spotlight RSS</li>
							<li class="menu-item"><a href="Spotlight/log_in.php"></a>Log into Spotlight</li>
						</ul>
						</div>
                    </li>
					<li>
                        <a href="board/home.php">Board</a>
						<div id="boardtag" class="menu" style="display:none;">
						<ul class="taglist" style="display:none;">
							<li class="menu-item"><a href="board/home.php">Board Home</a></li>
							<li class="menu-item"><a href="board/hot_topics.php">Board Hot Topics</a></li>
							<li class="menu-item"><a href="board/recent.php">Recent posts</a></li>
							<li class="menu-item"><a href="board/rss.php"></a>Board RSS</li>
							<li class="menu-item"><a href="board/log_in.php"></a>Log into Board</li>
						</ul>
						</div>
                    </li>
					<li>
                        <a href="hearts/home.php">Hearts</a>
						<div id="heartstag" class="menu" style="display:none;">
						<ul class="taglist" style="display:none;">
							<li class="menu-item"><a href="hearts/home.php">Hearts Home</a></li>
							<li class="menu-item"><a href="hearts/bargains.php">Featured Hearts</a></li>
							<li class="menu-item"><a href="hearts/recent.php">Recent Hearts</a></li>
							<li class="menu-item"><a href="hearts/rss.php"></a>Hearts RSS</li>
							<li class="menu-item"><a href="hearts/log_in.php"></a>Log into Hearts</li>
						</ul>
						</div>
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
					<li>
                       <a href="rss_list.php">RSS</a>
						<div id="rsstag" class="menu" style="display:none;">
						<ul class="taglist" style="display:none;">
							<li class="menu-item"><a href="mall/rss.php"></a>Mall RSS</li>
							<li class="menu-item"><a href="voice/rss.php"></a>Voice RSS</li>
							<li class="menu-item"><a href="spotlight/rss.php"></a>Spotlight RSS</li>
							<li class="menu-item"><a href="board/rss.php"></a>Board RSS</li>
							<li class="menu-item"><a href="hearts/rss.php"></a>Hearts RSS</li>
						</ul>
						</div>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

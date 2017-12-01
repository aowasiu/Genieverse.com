<?php

	//Get custom error function script
	require_once('../Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	//Connect to the database
	require_once('../Server_Includes/visitordbaccess.php');

	$errors = array();
	
	//Get truncate_text function
	require_once('../Server_Includes/scripts/common_scripts/text_truncate_function.php');

	//Get functions
	require_once('../Server_Includes/scripts/common_scripts/modified_for_url.php');
	require_once('../Server_Includes/scripts/common_scripts/country_name.php');
	
	//Get name functions
	require_once('../Server_Includes/scripts/spotlight_scripts/category_name.php');
	
	//Get meta details
	require_once('../Server_Includes/scripts/spotlight_scripts/spotlight_meta.php');

	//Get meta details
	require_once('../Server_Includes/scripts/spotlight_scripts/spotlight_footer.php');

	$extra_title = "Genieverse Spotlight - " . $spotlight_title_description;
	$meta_keyword = $spotlight_meta_keywords;
	$meta_description = $spotlight_meta_description;
	
	$header_background_color = '#FFF'; //#FFF(white) #000(black) #EEE(light ash) none(default background)
	
?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keyword" content="<?php echo $meta_keyword; ?>">
	<meta name="description" content="<?php echo $meta_description; ?>">
	<meta name="author" content="Wasiu Adisa">

    <title><?php
	if(isset($extra_title))
	{
		echo $extra_title . " | ";
	}
	else{
			//Do nought
	} ?> Genieverse - Virtual universe</title>

    <!-- Bootstrap Core CSS -->
	<link rel="icon" type="image/png" href="../images/genieverse_logos/favicon.ico">
    <link href="../Server_Includes/API_and_plug_ins/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/bootstrap_custom_css/heroic-features.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../index.php">Genieverse</a> <a class="navbar-brand" href="home.php">Spotlight</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                     <?php if(!isset($_SESSION["logged_in"])){ ?><li><a href="../join_us.php">Join us</a></li><?php } ?>
                    <li>
                     <?php if(isset($_SESSION["logged_in"])){ ?><li><a href="member/dashboard.php"><?php echo ucfirst($_SESSION ["username"]); ?>'s Dashboard</a></li>
					 <li><a href="member/log_out.php">Log out</a></li><?php }else{ ?><li><a href="log_in.php">Log in</a></li><?php } ?>
                    <li>
                        <a href="recent.php">Recent posts</a>
                    </li>
                    <li>
                        <a href="following.php">Followed posts</a>
                    </li>
                    <li>
                        <a href="rss.php">Spotlight RSS</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <!-- Jumbotron Header -->
        <header class="jumbotron hero-spacer">
            <h1>Welcome to Genieverse Spotlight!</h1>
            <p><?php echo $spotlight_title_description; ?></p>
        </header>

        <hr>
		
        <!-- Page Features -->
        <div class="row text-center">

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="http://placehold.it/800x500" alt="">
                    <div class="caption">
                        <h3>Categories</h3>
						<ul class="list-unstyled">
					<a href="search.php?category=Abduction" class="list-group-item<?php if($the_category_name == "Abduction"){ echo ' active'; }?>">Abduction</a><?php
						
		function getCategoryList($beforeUrl, $afterUrl)
		{
			global $db;
			$query = 'SELECT spotlight_category_name FROM gv_spotlight_category where spotlight_category_lock = 0 ORDER BY spotlight_category_id ASC';
			$result = mysql_query($query, $db) or die(mysql_error($db));

			$row = mysql_fetch_assoc($result);
			
			//Get modified_for_url function
			require_once('../Server_Includes/scripts/common_scripts/modified_for_url.php');
			
			while($row = mysql_fetch_assoc($result))
			{
				extract($row);
				echo  '
			' . $beforeUrl . '<a href="search.php?category=' . modified_for_url($spotlight_category_name, ' ', '-', '') . '" class="list-group-item">' . $spotlight_category_name . '</a>' . $afterUrl;
			}
		}

		getCategoryList('<li>', '</li>');
		?>
		</ul>
                    </div>
                </div>
            </div>

			<div class="pull-left col-lg-9">
				<h3>Trending posts</h3>
			</div>
			<?php
			
						$query = 'SELECT spotlight_post_id, spotlight_category_id, cc_id, spotlight_post_title, spotlight_post_body, spotlight_post_state, spotlight_post_city FROM gv_spotlight_post WHERE (spotlight_member = 1 AND spotlight_post_block = 0) AND (spotlight_post_investigated = 0) ORDER BY spotlight_post_id DESC LIMIT 10';
						$result =  mysql_query($query, $db) or die(mysql_error($db));
						
						if(mysql_num_rows($result) !== 0)
						{	
							while($row = mysql_fetch_assoc($result))
							{
								extract($row);
				?>

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="http://placehold.it/800x500" alt="">
                    <div class="caption">
                        <h3><a href="post_view.php?id=<?php
						echo  $row["spotlight_post_id"];
//						echo modified_for_url(category_name($row["spotlight_category_id"]), ' ', '-', '&Category=');
	//					echo modified_for_url($row["spotlight_post_title"], ' ', '-', '&Post=');
						?>"><?php echo $row["spotlight_post_title"]; ?></a></h3>
                        <p><?php echo
truncate_text(html_entity_decode($row["spotlight_post_body"]), 50, "No detail."); ?></p>
						<p><?php echo country_name($row["cc_id"]) . ' - ' . $row["spotlight_post_state"] . ' - ' . $row["spotlight_post_city"]; ?></p>
                    </div>
                </div>
            </div><?php
							}
						}
						else {
								echo '

            <div class="col-md-9 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="http://placehold.it/800x500" alt="">
                    <div class="caption">
                        <h3>' . "There're no posts yet." . '</h3>
                        <p></p>
                    </div>
                </div>
            </div>';
						}
					?>

        </div>
        <!-- /.row -->
		
        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p><?php

	$footer = '&copy; ' . $the_year . ' <a href="../index.php">Genieverse</a> <a href="home.php">Spotlight</a> ' . ' is a <b><a href="../index.php">Genieverse</a></b> site<br>' . ' <a href="../terms_of_use.php">Terms of Use</a>	|	<a href="../privacy_policy.php">Privacy Policy</a>	|	<a href="../contact_us.php">Contact us</a>		|	<a href="../sitemap.php">Site map</a>';
				echo $footer; ?></p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="../Server_Includes/API_and_plug_ins/jQuery/jquery-2.1.4.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../Server_Includes/API_and_plug_ins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

</body>

</html>
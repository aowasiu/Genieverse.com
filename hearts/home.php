<?php

	//Get meta details
	require_once('../Server_Includes/scripts/hearts_scripts/hearts_meta.php');

	//Set extra title	
	$extra_title = "Genieverse Hearts - Meet the right person | " . $hearts_title_description;

	//Set meta details
	$meta_keyword = $hearts_meta_keywords;
	$meta_description = $hearts_meta_description;

	//Set page background colour
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
	<link rel="icon" type="image/png" href="../images/genieverse_logos/favicon.ico">

    <!-- Bootstrap Core CSS -->
    <link href="../Server_Includes/API_and_plug_ins/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/bootstrap_custom_css/landing-page.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
        <div class="container topnav">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header" <?php /* style="background-color: #000;" */ ?>>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand topnav" href="../index.php">Genieverse</a>
                <a class="navbar-brand topnav" href="home.php">Hearts</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="search.php">Categories</a>
                    </li>
                    <li>
                        <a href="featured_submit.php">Submit for Feature</a>
                    </li>
                    <li>
                        <a href="featured.php">Feature Profiles</a>
                    </li>
					<li>
                        <a href="sweethearts.php">Sweethearts</a>
                    </li>
                    <li>
                        <a href="latest.php">Latest Profiles</a>
                    </li>
					<li>
                        <a href="log_in.php">Log in</a>
                    </li>
                    <li>
                        <a href="../join_us.php">Register</a>
                    </li>
                    <li>
                        <a href="../correspondence.php">Contact us</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>


    <!-- Header -->
    <a name="about"></a>
    <div class="intro-header">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <div class="intro-message">
                        <h1>Genieverse Hearts</h1>
                        <h3>Find, meet and chat with love. Send messages or find love... online.</h3>
                        <hr class="intro-divider">
                        <ul class="list-inline intro-social-buttons">
                            <li>
                                <a href="../index.php" class="btn btn-default btn-lg"><i class="fa fa-google fa-fw"></i> <span class="network-name">Genieverse</span></a>
                            </li>
                            <li>
                                <a href="home.php" class="btn btn-default btn-lg"><i class="fa fa-home fa-fw"></i> <span class="network-name">Hearts</span></a>
                            </li>
                            <li>
                                <a href="log_in.php" class="btn btn-default btn-lg"> <span class="network-name">Log in</span></a>
                            </li>
                            <li>
                                <a href="../join_us.php" class="btn btn-default btn-lg"> <span class="network-name">Join us</span></a>
                            </li>
                            <li>
                                <a href="../contact_us.php" class="btn btn-default btn-lg"> <span class="network-name">Contact us</span></a>
                            </li>
                            <li>
                                <a href="../tell_a_friend.php" class="btn btn-default btn-lg"> <span class="network-name">Tell a friend about us</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>
    <!-- /.intro-header -->

    <!-- Page Content -->

	<a  name="services"></a>

    <div class="content-section-b">

        <div class="container">

            <div class="row">
                <div class="col-lg-5 col-lg-offset-1 col-sm-push-6  col-sm-6">
                    <hr class="section-heading-spacer">
                    <div class="clearfix"></div>
                    <h2 class="section-heading">End loneliness! End sorrow! Find your mate now</h2>
                    <h3><a href="search.php">Search through various members... <br>Singles, Widows, Widowers, Divorcees and Married but polygamous</a>.</h3>
                </div>
                <div class="col-lg-5 col-sm-pull-6  col-sm-6">
                    <img class="img-responsive" src="../images/templates/lonely_hearts.png" alt="">
                </div>
            </div>

        </div>
        <!-- /.container -->

    <div class="content-section-a">

        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-sm-6">
                    <hr class="section-heading-spacer">
                    <div class="clearfix"></div>
                    <h2 class="section-heading">Christian or Muslim.. <br>find that wonderful person you've been looking. <br><a href="search.php">Search now</a>.</h2>
                    <p class="lead">.</p>
                </div>
                <div class="col-lg-5 col-lg-offset-2 col-sm-6">
                    <img class="img-responsive" src="../images/templates/ipad.png" alt="">
                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>
    <!-- /.content-section-a -->

    <div class="content-section-b">

        <div class="container">

            <div class="row">
                <div class="col-lg-5 col-lg-offset-1 col-sm-push-6  col-sm-6">
                    <hr class="section-heading-spacer">
                    <div class="clearfix"></div>
                    <h2 class="section-heading">End loneliness! End sorrow! Find your mate now</h2>
                    <h3><a href="search.php">Search through various members... <br>Singles, Widows, Widowers, Divorcees and Married but polygamous</a>.</h3>
                </div>
                <div class="col-lg-5 col-sm-pull-6  col-sm-6">
                    <img class="img-responsive" src="../images/templates/dog.png" alt="">
                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>
    <!-- /.content-section-b -->

    <div class="content-section-a">

        <div class="container">

            <div class="row">
                <div class="col-lg-5 col-sm-6">
                    <hr class="section-heading-spacer">
                    <div class="clearfix"></div>
                    <h2 class="section-heading">Keep in touch<br>via mobile-friendly views</h2>
                    <p class="lead">Don't hold back. Don't wait. Find your better half now on <a href="home.php">Genieverse Hearts</a></p>
                </div>
                <div class="col-lg-5 col-lg-offset-2 col-sm-6">
                    <img class="img-responsive" src="../images/templates/phones.png" alt="">
                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>
    <!-- /.content-section-a -->

	<a  name="contact"></a>
    <div class="banner">

        <div class="container">

            <div class="row">
                <div class="col-lg-3">
                    <h2>Log in with:</h2>
                </div>
                <div class="col-lg-9">
                    <ul class="list-inline banner-social-buttons">
						<li>
							<a href="https://twitter.com" class="btn btn-default btn-lg"><i class="fa fa-twitter fa-fw"></i> <span class="network-name">Twitter</span></a>
						</li>
						<li>
							<a href="https://github.com" class="btn btn-default btn-lg"><i class="fa fa-github fa-fw"></i> <span class="network-name">Github</span></a>
						</li>
						<li>
							<a href="https://linkedin.com" class="btn btn-default btn-lg"><i class="fa fa-linkedin fa-fw"></i> <span class="network-name">Linkedin</span></a>
						</li>
						<li>
							<a href="https://facebook.com" class="btn btn-default btn-lg"><i class="fa fa-facebook fa-fw"></i> <span class="network-name">Facebook</span></a>
						</li>
						<li>
							<a href="https://plus.google.com" class="btn btn-default btn-lg"><i class="fa fa-google-plus fa-fw"></i> <span class="network-name">Google+</span></a>
						</li>
						<li>
							<a href="https://personal.yahoo.com" class="btn btn-default btn-lg"><i class="fa fa-yahoo fa-fw"></i> <span class="network-name">Yahoo</span></a>
						</li>
                    </ul>
                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>
    <!-- /.banner -->

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="list-inline">
                        <li>
                            <a href="#services"> &copy; 2016</a>
                        </li>
                        <li>
                            <a href="home.php">Genieverse Planet</a> is a <a href="../index.php">Genieverse</a> site
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                            <a href="../privacy_policy.php">Privacy policy</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                            <a href="../terms_of_use.php">Terms of Use</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                            <a href="../correspondence.php">Contact us</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="../Server_Includes/API_and_plug_ins/jQuery/jquery-2.1.4.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../Server_Includes/API_and_plug_ins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

</body>

</html>
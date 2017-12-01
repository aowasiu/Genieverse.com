<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	$recaptcha_here = "here";

	$your_names_default = "Your full name";
	$your_friends_email_default = "Your friend's email address";
	$senders_name = (isset($_POST["senders_name"])) ? $_POST["senders_name"]: $your_names_default;
	$recipients_email = (isset($_POST["recipients_email"])) ? $_POST["recipients_email"]: $your_friends_email_default;
	$recipients_phone = (isset($_POST["recipients_phone"])) ? $_POST["recipients_phone"]: "Your friend's phone number";
	
	//Connect to database
	 require_once('Server_Includes/visitordbaccess.php');

	//Errors definition as an array
	$errors = array();
	
	//Get geolocation_finder script
//	require_once('Server_Includes/scripts/common_scripts/geolocation_finder.php');
	
	//Get truncate_text function
	require_once('Server_Includes/scripts/common_scripts/text_truncate_function.php');

	//Get modified_for_url function
	require_once('Server_Includes/scripts/common_scripts/get_page_features.php');

	//Get all meta data
	require_once('Server_Includes/scripts/common_scripts/all_services_meta.php');

	//Set all meta details
	$meta_keyword = $all_meta_keywords;
	$meta_description = $all_meta_description;

	//Set extra title
	$extra_title = "Genieverse Home";

?><!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="keyword" content="genieverse classified, genieverse forum, genieverse whistleblower, genieverse expose, <?php echo $meta_keyword; ?>">
		<meta name="description" content="Genieverse portal for Genieverse Mall, Genieverse Voice, Genieverse Offender and Genieverse Board, <?php echo $meta_description; ?>">
		<meta name="author" content="Wasiu Adisa">
		<link rel="icon" type="image/png" href="images/genieverse_logos/favicon.ico">
		<title><?php
	if(isset($extra_title))
	{
		echo $extra_title . " | ";
	}
	else{
			//Do nought
	} ?> Genieverse - Virtual universe</title>
		<link rel="stylesheet" href="css/bootstrap_custom_css/components.css">
		<link rel="stylesheet" href="css/bootstrap_custom_css/responsee.css">
		<link rel="stylesheet" href="Server_Includes/API_and_plug_ins/Owl-Carousel/owl.carousel.css">
		<link rel="stylesheet" href="Server_Includes/API_and_plug_ins/Owl-Carousel/owl.theme.css">
<?php /* If the site looks different than the one on local host, the stylesheet below might be responsible <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.0.1/"> */ ?>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.0.1/">
		<!-- CUSTOM STYLE -->
		<link rel="stylesheet" href="css/bootstrap_custom_css/template-style.css">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
		<script type="text/javascript" src="Server_Includes/API_and_plug_ins/jQuery/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="Server_Includes/API_and_plug_ins/jQuery/jquery-ui.min.js"></script>    
		<script type="text/javascript" src="Server_Includes/API_and_plug_ins/Modernizr/modernizr.js"></script>
		<script type="text/javascript" src="Server_Includes/API_and_plug_ins/Responsee/responsee.js"></script>
		<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
		<![endif]-->
	</head>
	<body class="size-1140">
      <!-- TOP NAV WITH LOGO -->
      <header>
         <nav>
            <div class="line">
               <div class="s-12 l-2">
                  <img class="s-12 l-12 center"src="images/templates/logo_genieverse2.fw.png" alt="Genieverse">
               </div>
               <div class="top-nav s-12 l-10 right">
                  <p class="nav-text">Menu</p>
                  <ul class="right">
                     <li><a href="index.php">Genieverse</a></li>
                     <li>
                        <a>Services</a>
                        <ul>
                           <li>
                              <a>Mall</a>
                              <ul>
                                 <li><a href="mall/home.php">Mall Home</a></li>
                                 <li><a href="mall/bargains.php">Bargains</a></li>
                                 <li><a href="mall/recent.php">Recent Mall listings</a></li>
                                 <?php if(!isset($_SESSION["logged_in"])){ ?><li><a href="mall/log_in.php">Log in</a></li><?php }else{ ?><li><a href="mall/member/dashboard.php">Dashboard</a></li><?php } ?>
                              </ul>
                           </li>
                           <li>
                              <a>Voice</a>
                              <ul>
                                 <li><a href="voice/home.php">Voice Home</a></li>
                                 <li><a href="voice/loud_voices.php">Loud Voices</a></li>
                                 <li><a href="voice/recent.php">Recent Voices</a></li>
                                 <?php if(!isset($_SESSION["logged_in"])){ ?><li><a href="voice/log_in.php">Log in</a></li><?php }else{ ?><li><a href="voice/member/dashboard.php">Dashboard</a></li><?php } ?>
								 
                              </ul>
                           </li>
                           <li>
                              <a>Spotlight</a>
                              <ul>
                                 <li><a href="spotlight/home.php">Spotlight Home</a></li>
                                 <li><a href="spotlight/following.php">Following</a></li>
                                 <li><a href="spotlight/recent.php">Recent Spotlight</a></li>
                                 <?php if(!isset($_SESSION["logged_in"])){ ?><li><a href="spotlight/log_in.php">Log in</a></li><?php }else{ ?><li><a href="spotlight/member/dashboard.php">Dashboard</a></li><?php } ?>
                              </ul>
                           </li>
                           <li>
                              <a>Board</a>
                              <ul>
                                 <li><a href="board/home.php">Board Home</a></li>
                                 <li><a href="board/mod_focus.php">Mod Focus</a></li>
                                 <li><a href="board/hot_topics.php">Hot topics</a></li>
                                 <li><a href="board/recent.php">Recent Board posts</a></li>
                                 <?php if(!isset($_SESSION["logged_in"])){ ?><li><a href="board/log_in.php">Log in</a></li><?php }else{ ?><li><a href="board/member/dashboard.php">Dashboard</a></li><?php } ?>
                              </ul>
                           </li>
                           <li>
                              <a>Hearts</a>
                              <ul>
                                 <li><a href="hearts/home.php">Hearts Home</a></li>
                                 <li><a href="hearts/sweethearts.php">Sweethearts</a></li>
                                 <li><a href="hearts/featured.php">Featured Profiles</a></li>
                                 <li><a href="hearts/latest.php">Latest Profiles</a></li>
                                 <?php if(!isset($_SESSION["logged_in"])){ ?><li><a href="hearts/log_in.php">Log in</a></li><?php }else{ ?><li><a href="hearts/member/dashboard.php">Dashboard</a></li><?php } ?>
                              </ul>
                           </li>
                        </ul>
                     </li>
                     <?php if(!isset($_SESSION["logged_in"])){ ?><li><a href="log_in.php">Log in</a></li>
<li><a href="join_us.php">Register</a></li>
<?php }else{ ?><li><a href="services.php"><?php echo ucfirst($_SESSION ["username"]); ?></a></li>
<li><a href="log_out.php">Log out</a></li>
<?php } ?>
                     <li><a href="wasiu.php">Meet the Founder</a></li>
                     <li><a href="contact_us.php">Contact us</a></li>
                  </ul>
               </div>
            </div>
         </nav>
      </header>
      <section>
         <!-- CAROUSEL --> 
         <div id="carousel">
            <div id="owl-demo" class="owl-carousel owl-theme">
 
               <div class="item">
                  <img src="images/Genieverse_Background/Computer/category_laptop1.png" alt="Genieverse Mall - Computer category image">
                  <div class="line">
                     <h2><a href="m_home.php">Genieverse Mall - Free classified-ad web market.</a></h2>
                  </div>
               </div>
               <div class="item">
                  <img src="images/Genieverse_Background/Cars/category_cars1.png" alt="Genieverse Mall - Vehicles category image">
                  <div class="line">
                     <h2><a href="m_home.php">Genieverse Mall - Free classified-ad web market.</a> <a href="join_us.php">Click here to Join Now</a></h2>
                  </div>
               </div><?php
/*               <div class="item">
                  <img src="images/templates/1500x606-2.png" alt="Genieverse Voice, Genieverse Board, Genieverse Planet and Genieverse Heart  image">
                  <div class="line">
                     <h2>Genieverse Hearts and Genieverse Planet</h2>
                  </div>
               </div>*/
?>            </div>
         </div>
         <!-- FIRST BLOCK -->
         <div id="first-block">
            <div class="line">
               <div class="margin">
                  <div class="s-12 m-6 l-4 margin-bottom">
                     <i class="icon-star icon3x"></i>
                     <h2>About Genieverse</h2>
                     <p><a href="index.php">Genieverse</a> is a portal of free web services. <a href="#services">Genieverse Services</a> don't require payment for membership, nor restriction to any country, race or ethnicity.</p>
                  </div>
                  <div class="s-12 m-6 l-4 margin-bottom">
                     <i class="icon-star icon3x"></i>
                     <h2><a name="services">Genieverse Services</a></h2>
                     <p><a href="m_home.php">Genieverse Mall</a> is a free classified-ad web market.</p>
					 <p><a href="development.php">Genieverse Voice</a> is a reports, complaints and exposure service.</p>
					 <p><a href="development.php">Genieverse Board</a> is a web forum for general discussion.</p>
					 <p><a href="development.php">Genieverse Spotlight</a> is a medium for exposing criminals and criminal activites. You don't even need to tell your name.</p>
					 <p><a href="development.php">Genieverse Hearts</a> is a service dedicated to meeting your soul mate.</p>
					 <p><a href="development.php">Genieverse Planet</a> is a social network modelled as a virtual democracy.</p>
                  </div>
                  <div class="s-12 m-6 l-4 margin-bottom">
                     <i class="icon-mail icon3x"></i>
                     <h2>Contact us</h2>
                     <p><a href="correspondence">Talk to us</a></p>
					 <p><a href="marketing.php">Advertise on an Advert space</a></p>
					 <p><a href="marketing.php">Advertise as a post</a></p>
					 <p><a href="development.php">Get update on your advert</a></p>
					 <p><a href="development.php">Mail Founder</a></p>
                  </div>
                  <div class="s-12 m-12 l-12 margin-bottom">
                     <i class="icon-star icon3x"></i>
                     <h2><a href="join_us.php">Click here to Join Genieverse Now</a></h2>
                     <p><a href="join_us.php">Register Now</a></a>
                  </div>
 
<?php /*
                  <div class="s-12 m-12 l-12 margin-bottom">
                     <i class="icon-star icon3x"></i>
                     <h2>Founder</h2>
                     <p>Wasiu Adisa holds a degree in Business Administration.</p>
				    	 <p>He's been a self-taught web developer since 2011. He knows HTML, CSS, jQuery, JavaScript, Bootstrap, PHP, MySQL. WordPress and OpenCart</p>
                     <p>Before the year runs out, he intends to  learn OOP, MVC, and Yii.</p>
                     <p>And in the near future, learn Linux (Ubuntu, CentOs), Perl, Catalyst, MongoDB, Hbase, VoltDB, OCAML, Opa and ... Network Forensics.</p>
                     <p>All he needs now is time to learn all the above or turn 20 again to have time to learn and be proficient in them all.</p>
                    <p>Presently, he's busy refactoring <a href="index.php">Genieverse</a>.</p>
</p>
			     		<p>Wasiu is charming, friendly, funny, "huggable", "lovable" ....  Really, he is.</p>
                  </div>
*/
?>               </div>
            </div>
         <!-- MIDDLE BLOCK -->
         <div id="first-block">
            <div class="line">
               <div class="margin">
                  <div class="s-12 m-12 l-12 margin-bottom">
                     <i class="icon-star icon3x"></i>
                     <h2>Newest members</h2>
                     <p> |<?php
echo get_newest_members();
?> </p>
               </div>
             </div>
           </div>
         </div>
         <!-- SECOND BLOCK -->
         <div id="second-block">
            <div class="line">
               <div class="margin-bottom">
                  <div class="margin">
                     <article class="s-12 m-6 l-6">
                        <h2><a href="join_us.php">Genieverse Mall is live! Click here to Join Now</a></h2>
                        <p><a href="m_home.php">Create classified-ad listings. Buy cars, sell phones, meet people, rent office space, buy pets, buy office supplies and do lots more, <b>at no hidden cost</b>.</a></p>
                        <p><a href="voice/home.php">Create classified-ad listings. Buy cars, sell phones, meet people, rent office space, buy pets, buy office supplies and do lots more, <b>at no hidden cost</b>.</a></p>
                     </article>
                     <article class="s-12 m-6 l-6">
                        <h2><a href="join_us.php">Genieverse Voice is live! Click here to Join Now</a></h2>
                        <p><a href="voice/home.php"><?php echo $voice_title_description; ?>.<b> <?php echo $voice_meta_description; ?></b>.</a></p>
                     </article>
                  </div>
				  <br/>
				  <hr/>
				  <br/>
                  <div class="margin">
                     <article class="s-12 m-6 l-6">
                        <h2><a href="join_us.php">Genieverse Spotlight is live! Click here to Join Now</a></h2>
                        <p><a href="spotlight/home.php"><?php echo $spotlight_title_description; ?>.<b> <?php echo $spotlight_meta_description; ?></b>.</a></p>
                     </article>
                     <article class="s-12 m-6 l-6">
                        <h2><a href="join_us.php">Genieverse Board is live! Click here to Join Now</a></h2>
                        <p><a href="board/home.php"><?php echo $board_title_description; ?>.<b> <?php echo $board_meta_description; ?></b>.</a></p>
                     </article>
				  </div>
				  <br/>
				  <hr/>
               </div>
            </div>
         </div><?php
/*         <!-- GALLERY -->
         <div id="third-block">
            <div class="line">
               <h2>Gallery</h2>
               <div class="margin">
                  <div class="s-12 m-6 l-3">
                     <img class="margin-bottom" src="images/templates/330x190.jpg">
                  </div>
                  <div class="s-12 m-6 l-3">
                     <img class="margin-bottom" src="images/templates/330x190-2.jpg">
                  </div>
                  <div class="s-12 m-6 l-3">
                     <img class="margin-bottom" src="images/templates/330x190-3.jpg">
                  </div>
                  <div class="s-12 m-6 l-3">
                     <img class="margin-bottom" src="images/templates/330x190.jpg">
                  </div>
               </div>
            </div>
         </div> */
?>         <div id="fourth-block">
            <div class="line">
               <div id="owl-demo2" class="owl-carousel owl-theme">
                  <div class="item">
                     <h2>Coming Soon!!!</h2>
                     <p>Genieverse Hearts - Find your soul mate. Find love, meet someone. Find your spouse today.
                     </p>
                  </div>
                  <div class="item">
                     <h2>Coming Soon!!!</h2>
                     <p>Genieverse Planet -  
                        Living online. Chat with friends. Meet people. Get busy.
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- FOOTER -->
      <footer><?php

  	$copyright_date = date('Y');
	
  	if($copyright_date < 2015)
	{
		$the_year = 2015;
	}
	elseif(	$copyright_date > 2015	)
	{	$the_year = "2015 - $copyright_date";	}
	else	{	
				$the_year = $copyright_date;
	}
	//<b><a href="https://www.facebook.com/Adisawasiuolayemi">Wasiu Adisa</a></b>
	?>
         <div class="line">
            <div class="s-12 l-6">
				<p>© <?php echo $the_year; ?>. <b><a href="index.php" title="Genieverse">Genieverse</a></b> is owned by <a href="thewebdeveloper/index.php"><b>Wasiu Adisa</b></a></p>
            </div>
            <div class="s-12 l-6">
				<div class="right">
					<a href="terms_of_use.php" title="Terms of Use">Terms of Use	|	</a>
					<a href="privacy_policy.php" title="Privacy Policy">Privacy Policy	|	</a>
					<a href="contact_us.php" title="Contact us">Contact us	|	</a>
					<a href="sitemap.php" title="Site map">Site map	</a>
				</div>
			</div>
         </div>
      </footer>
      <script type="text/javascript" src="Server_Includes/API_and_plug_ins/Owl-Carousel/owl.carousel.js"></script>
      <script type="text/javascript">
         jQuery(document).ready(function($) {	  
           $("#owl-demo").owlCarousel({		
         	navigation : true,
         	slideSpeed : 600,
         	paginationSpeed : 800,
         	autoPlay : true,
         	singleItem:true
           });

           $("#owl-demo2").owlCarousel({
        		slideSpeed : 600,
        		autoPlay : true,
        		navigation : true,
        		pagination : true,
        		singleItem:true
        	  });
         });	 
      </script>
   </body>
</html><?php mysql_close($db); ?>
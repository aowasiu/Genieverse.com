<?php

	//Get custom error function script 
	require_once('../Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	//Connect to the database
	require_once('../Server_Includes/visitordbaccess.php');

	//Set errors as array
	$errors = array();
	
	//Get truncate_text function
	require_once('../Server_Includes/scripts/common_scripts/text_truncate_function.php');

	//Get functions
	require_once('../Server_Includes/scripts/common_scripts/modified_for_url.php');
	require_once('../Server_Includes/scripts/common_scripts/country_name.php');
		
	//Get total unread message(s) function
	require_once('../Server_Includes/scripts/common_scripts/get_common_functions.php');

	//Get meta details
	require_once('../Server_Includes/scripts/mall_scripts/mall_meta.php');

	//Initiate count of unread messages 
	$unread_general_messages = unread_general_messages();
	
	//Set extra title	
	$extra_title = "Genieverse Mall - Free web market | " . $mall_title_description;

	//Set meta details
	$meta_keyword = $mall_meta_keywords;
	$meta_description = $mall_meta_description;

	//Set page background colour
	$header_background_color = '#FFF'; //#FFF(white) #000(black) #EEE(light ash) none(default background)
	
?><!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
		<link rel="stylesheet" href="../css/bootstrap_custom_css/components.css">
		<link rel="stylesheet" href="../css/bootstrap_custom_css/responsee.css">
		<link rel="stylesheet" href="../Server_Includes/API_and_plug_ins/Owl-Carousel/owl.carousel.css">
		<link rel="stylesheet" href="../Server_Includes/API_and_plug_ins/Owl-Carousel/owl.theme.css">
		<!-- CUSTOM STYLE -->
		<link rel="stylesheet" href="../css/bootstrap_custom_css/template-style.css">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
		<script type="text/javascript" src="../Server_Includes/API_and_plug_ins/jQuery/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="../Server_Includes/API_and_plug_ins/jQuery/jquery-ui.min.js"></script>    
		<script type="text/javascript" src="../Server_Includes/API_and_plug_ins/Modernizr/modernizr.js"></script>
		<script type="text/javascript" src="../Server_Includes/API_and_plug_ins/Responsee/responsee.js"></script>
		<script src="https://www.google.com/recaptcha/api.js"></script>
		<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
		<![endif]-->
	</head>
	<body class="size-960"><?php /*Use width of 960 or 1000*/ ?>
      <!-- HEADER -->
      <header>
         <div class="line">
            <div class="box" style="background: #FFF;">
               <div class="s-12 l-5">
				  <a href="home.php"><img src="../images/templates/logo_mall.png" width="200" height="200" alt="Genieverse Mall"></a>
               </div>
            </div>
         </div>
         <!-- TOP NAV -->  
         <div class="line">
            <nav class="margin-bottom">
               <p class="nav-text">Menu</p>
               <div class="top-nav s-12 l-12">
                  <ul>
					<li><a href="../index.php">Genieverse</a></li>
					<li><a href="home.php">Mall</a></li>
					<?php if(!isset($_SESSION["logged_in"])){ ?><li><a href="../join_us.php">Join us</a></li><?php } ?>
					<li>
						<a>Categories</a>
						<ul> 
							<li><a href="search.php?category=Appliances">Appliances</a></li> 
							<?php

		function getCategoryList($beforeUrl, $afterUrl)
		{
			global $db;
			$query = 'SELECT mall_category_name FROM gv_mall_category where mall_category_lock = 0 ORDER BY mall_category_id ASC';
			$result = mysql_query($query, $db) or die(mysql_error($db));

			$row = mysql_fetch_assoc($result);
			
			//Get modified_for_url function
			require_once('../Server_Includes/scripts/common_scripts/modified_for_url.php');
			
			while($row = mysql_fetch_assoc($result))
			{
				extract($row);
				echo  '
			' . $beforeUrl . '<a href="search.php?category=' . modified_for_url($mall_category_name, ' ', '-', '') . '" class="list-group-item">' . $mall_category_name . '</a>' . $afterUrl;
			}
		}

		getCategoryList('<li>', '</li>');

				?>
				</ul>
                    </li>
					<?php if(isset($_SESSION["logged_in"])){ ?><li><a href="member/dashboard.php">Dashboard</a></li>
					 <?php if($unread_general_messages < 1){ ?><li><a href="../messages.php"><?php echo $unread_general_messages; ?> Messages</a></li><?php }else{ ?><li style="background:#FF0000;"><a href="../messages.php"><?php echo $unread_general_messages; ?> Messages</a></li><?php } ?>
					 <li><a href="member/log_out.php">Log out</a></li><?php }else{ ?><li><a href="log_in.php">Log in</a></li><?php } ?>
					<li><a href="bargains.php">Bargains</a></li>
					<li><a href="recent.php">Recent</a></li>
					<li><a href="rss.php">RSS</a></li>
                  </ul>
               </div>
            </nav>
         </div>
      </header>
      <section>
         <!-- CAROUSEL -->  
         <div class="line" style="background:none;">
            <div id="owl-demo" class="owl-carousel owl-theme  margin-bottom">
               <div class="item"><img src="../images/Genieverse_Background/Computer/category_laptop2.png" alt=""></div> 
<?php /*               <div class="item"><img src="../images/Genieverse_Background/Cars/category_cars4.png" alt=""></div>*/ ?>
               <div class="item"><img src="../images/Genieverse_Background/Phone/category_phone1.png" alt=""></div>
               <div class="item"><img src="../images/Genieverse_Background/Computer/category_laptop1.png" alt=""></div>
             </div>
         </div>
         <!-- HOME PAGE BLOCK -->      
         <div class="line">
            <div class="margin">
               <div class="s-12 m-6 l-6 margin-bottom">
                  <div class="box">
                     <h2><a href="home.php">Genieverse Mall</a></h2>
                     <p><a href="home.php">Genieverse Mall</a> is a free classified-ad web market. </p>
					 <p>Buy, sell or exchange phones, cars, flat-screen TV, DVD player, fridge, blender, oven, freezer. Buy a house or land. Rent apartment, room or office space. Meet boys or girls. Find wanted items. Sell bizarre items.</p>
                  </div>
               </div>
               <div class="s-12 m-6 l-6 margin-bottom">
                  <div class="box">
                     <h2><a href="bargains.php">Genieverse Bargains</a></h2>
                     <p>Stop searching the web for good deals. </p>
					 <p><a href="bargains.php">Genieverse Mall Bargains</a> is a list of selected good deals from all <a href="home.php">Genieverse Mall</a> listings<br>
					 'Can't find a good deal? Post in Wanted category asking for anyone who has what you want. Yes, it's that simple.</p>
                  </div>
               </div>
            </div>
         </div>

         <!-- ASIDE NAV AND CONTENT -->
         <div class="line">
            <div class="box margin-bottom">
               <div class="margin">
                  <!-- ASIDE NAV -->
                  <div class="s-12 m-5 l-4">
                     <div class="aside-nav">
                     <h3><a href="../tell_a_friend.php">Click here to Tell a friend about Genieverse</a></h3>
                        </div>
                    </div>
                  <!-- CONTENT -->
                  <article class="s-12 m-7 l-8">
                     <h3>Available categories</h3>
					 <p>| <a href="search.php?category=Appliances">Appliances</a> |<?php
		function CategoryList($beforeUrl, $afterUrl)
		{
			global $db;
			$query = 'SELECT mall_category_name FROM gv_mall_category where mall_category_lock = 0 ORDER BY mall_category_id ASC';
			$result = mysql_query($query, $db) or die(mysql_error($db));

			$row = mysql_fetch_assoc($result);
			
			//Get modified_for_url function
			require_once('../Server_Includes/scripts/common_scripts/modified_for_url.php');
			
			while($row = mysql_fetch_assoc($result))
			{
				extract($row);
				echo  '
			' . $beforeUrl . ' <a href="search.php?category=' . modified_for_url($mall_category_name, ' ', '-', '') . '">' . $mall_category_name . '</a> |' . $afterUrl;
			}
		}
		
		echo CategoryList();
					 ?> </p>
                     <h3>Recent posts</h3>
                     <p><a href="recent.php">CLICK HERE NOW to view all recently posted listings.</a></p>
                  </article>
                  <article class="s-12 m-12 l-12">
                     <h3>Newest Members</h3>
                     <p> |<?php
	function get_newest_members($docLevelUrlPrefix)
	{
		global $db;
		$query = 'SELECT member_id, username FROM gv_members WHERE (deactivated = 0 AND banned = 0) AND suspended = 0 ORDER BY member_id ASC LIMIT 20';
		$resultNewest = 
		mysql_query($query, $db) or die(mysql_error($db));
	
		if(mysql_num_rows($resultNewest) !== 0)
		{
			while($newestMember = mysql_fetch_assoc($resultNewest))
			{
				extract($newestMember);
				echo ' <a href="' . $docLevelUrlPrefix . 'member_profile.php?id=' . $newestMember["member_id"] . '">' . ucfirst($newestMember["username"]) . '</a> |';
			}
		}
		else{ echo " No user yet "; }
	}

   echo get_newest_members('../'); ?> </p>
                  </article>
               </div>
            </div>
         </div>
         <!--POST GALLERY CAROUSEL -->
         <div class="line">
            <h2>Recent posts</h2>
            <div id="owl-demo2" class="owl-carousel margin-bottom">
				<?php
	$query = 'SELECT mall_post_id, mall_category_id, mall_post_price, cc_id, mall_category_id, mall_post_title, mall_post_description, mall_post_state, mall_post_city, mall_post_created_on FROM gv_mall_post WHERE (mall_member = 1 AND mall_post_block = 0) ORDER BY mall_post_id DESC LIMIT 10';
	$result =  mysql_query($query, $db) or die(mysql_error($db));
			
	if(mysql_num_rows($result) !== 0)
	{	
		while($row = mysql_fetch_assoc($result))
		{
			extract($row);
				?><div class="item">
					<p><a href="post_view.php?id=30&Category=Electronics&post=Haier-thermo-cool-HI-Fi-for-sale">Haier thermo cool HI-Fi for sale</a><br>
					Nigeria - Lagos - </p>
				</div>
				<?php
		}
	}
	else {
			echo "\n" . '<div class="item">
					<p>There\'re no posts yet.</p>
				</div>';
	}
				?>
			</div>
         </div>
 
            </div>
         </div>
<br><br><br><br>
      </section>
      <!-- FOOTER -->
      <footer class="line">
		<div class="box">
            <div class="s-12 l-6">
               <?php /*Copyright: &#169; &copy; */ ?><p>&copy; 2015 - 2016. <b><a href="http://www.genieverse.com/m_home.php" title="Genieverse Mall">Genieverse Mall</a></b> is a <b><a href="../index.php" title="Genieverse">Genieverse</a></b> site</p>
            </div>
            <div class="s-12 l-6">
				<div class="right">
					<a href="../terms_of_use.php" title="Terms of Use">Terms of Use	|	</a>
					<a href="../privacy_policy.php" title="Privacy Policy">Privacy Policy	|	</a>
					<a href="../contact_us.php" title="Contact us">Contact us	|	</a>
					<a href="../sitemap.php" title="Site map">Site map	</a>
				</div>
            </div>
         </div>
      </footer>
      <script type="text/javascript" src="../Server_Includes/API_and_plug_ins/Owl-Carousel/owl.carousel.js"></script>  
      <script type="text/javascript">
         jQuery(document).ready(function($) {	  
           $("#owl-demo").owlCarousel({		

         	navigation : true,
         	slideSpeed : 100,
         	paginationSpeed : 200,
         	autoPlay : true,
         	singleItem:true
           });
           $("#owl-demo2").owlCarousel({
         	items : 4,
         	lazyLoad : true,
         	autoPlay : true,
         	navigation : true,
         	pagination : false
           });
         });	 
      </script>
   </body>
</html>
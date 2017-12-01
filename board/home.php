<?php

	//Get custom error function script 
	require_once('../Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	//Connect to the database
	require_once('../Server_Includes/visitordbaccess.php');

	$errors = array();

	//Get truncate_text function
	require_once('../Server_Includes/scripts/common_scripts/text_truncate_function.php');

	//Get page functions
	require_once('../Server_Includes/scripts/board_scripts/functions_get_page_features_for_board.php');
	
	//Get functions
	require_once('../Server_Includes/scripts/common_scripts/get_newest_members.php');
	require_once('../Server_Includes/scripts/common_scripts/modified_for_url.php');
	require_once('../Server_Includes/scripts/common_scripts/country_name.php');
	
	//Get name functions
	require_once('../Server_Includes/scripts/board_scripts/category_name.php');
	
	//Get meta details
	require_once('../Server_Includes/scripts/board_scripts/board_meta.php');

	$extra_title = "Genieverse Board - World's free forum | " . $board_title_description;
	$meta_keyword = $board_meta_keywords;
	$meta_description = $board_meta_description;
	
	$background_color = '#000'; //#FFF(white) #000(black) #EEE(light ash) none(default background)

	function getCategoryList($beforeUrl, $afterUrl)
	{
		global $db;
		$query = 'SELECT board_category_name FROM gv_board_category where board_category_lock = 0 ORDER BY board_category_id ASC';
		$result = mysql_query($query, $db) or die(mysql_error($db));

		$row = mysql_fetch_assoc($result);
		
		//Get modified_for_url function
		require_once('../Server_Includes/scripts/common_scripts/modified_for_url.php');
		
		while($row = mysql_fetch_assoc($result))
		{
			extract($row);
			echo  '
          ' . $beforeUrl . '<a href="search.php?category=' . modified_for_url($board_category_name, ' ', '-', '') . '" class="list-group-item">' . $board_category_name . '</a>' . $afterUrl;
		}
	}//End of getCategoryList

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
		<link rel="stylesheet" type="text/css" href="../css/bootstrap_custom_css/responsiva-style1.css">
		<link rel="stylesheet" type="text/css" href="../Server_Includes/API_and_plug_ins/bootstrap-3.3.5-dist/css/bootstrap.min.css">
	</head>
	<body style="background: <?php echo $background-color; ?>;"><?php /*style="background: url(../images/templates/responsiva-images/boardBackGround.png);"*/ ?>
		<div class="resize"></div>
		<!-- Div Wrapper Element Starts Here -->
		<div id="Wrapper" style="background-color: #FFF;">
		  <!-- Header Element Starts Here -->
		  <header id="header">
			<!-- Hgroup Element Starts Here -->
			<hgroup id="title">
			  <div id="board_logo"><a href="../index.php"><img src="../images/service_images/boardGenieverse.png" /></a><a href="home.php"><img src="../images/service_images/board-logo1.png" /></a><?php /**/ ?> <a href="home.php"><img src="../images/service_images/boardHomeAdSpace.png" /></a><?php /**/ ?> <a href="home.php"><img src="../images/service_images/boardHomeAdSpace1.png" /></a> </div>
			  <h2 style="display: none;">Genieverse Board - World's free forum!</h2>
			  <hr/>
			</hgroup>
			<!-- Hgroup Element Ends Here -->
			<!-- Nav Element Starts Here -->
			<nav class="navigation"> <a href="../index.php">Genieverse</a> <a href="home.php">Board</a> <a href="mod_focus.php">Mod Focus</a> <a href="hot_topics.php">Hot Topics</a> <a href="new_topics.php">New Topics</a> <a href="recent.php">Recent Posts</a> <?php if(isset($_SESSION["logged_in"])){ ?><a href="member/dashboard.php"><?php echo ucfirst($_SESSION ["username"]); ?>'s Dashboard</a> <?php }else{ ?><a href="../join_us.php">Join us</a> <a href="log_in.php">Log in</a> <?php } ?> <a href="rss.php">Board RSS</a> <a href="../contact_us.php">Contact Us</a> </nav>
		  </header>
		  <!-- Header Element Ends Here -->
		  <!-- Div wrapper2 starts here -->
		  <div id="Wrapper2">
			<!-- Sidebar Element Starts Here -->
			<aside id="sidebar-wrapper">
			  <nav class="sidebar">
				<h1>Categories</h1>
				<ul class="list-unstyled ">
				  <li class="col-lg-6 col-md-6 col-sm-12"><a href="search.php?category=Agriculture" class="list-group-item">Agriculture</a></li><?php
			getCategoryList('<li class="col-lg-6 col-md-6 col-sm-12">', '</li>');
			?>
				</ul>
			  </nav>
		<?php /*      <nav class="sidebar">
				<h1>We are very Social!</h1>
				<ul class="list-unstyled">
				  <li class="col-lg-6 col-md-6 col-sm-12"><a href="http://www.facebook.com/" class="list-group-item">Facebook</a></li>
				  <li class="col-lg-6 col-md-6 col-sm-12"><a href="http://www.twitter.com/" class="list-group-item">Twitter</a></li>
				</ul>
			  </nav>*/
		?>      <nav class="sidebar">
				<h1>Recent Comments</h1>
				<ul class="list-unstyled">
					<?php
					function getComments()
					{
						global $db;
						$query = 'SELECT board_comment_id, board_post_id, member_id, board_comment FROM gv_board_post_comments WHERE (board_comment_block = 0 AND board_comment_delete = 0)';
						$result =  mysql_query($query, $db) or die(mysql_error($db));
						if(mysql_num_rows($result) !== 0)
						{
							//Get text truncate function
							require_once('../Server_Includes/scripts/common_scripts/text_truncate_function.php');

							while($row = mysql_fetch_assoc($result))
							{
								extract($row);
								echo '<li><a href="comments.php?id=' . $row["board_post_id"] . '&comment=' . $row["board_comment_id"] . '">' . text_truncate_function($row["board_comment"], 50, "No comment") . '</a></li>
				  ';
							}
						}
						else {
							echo '<li><a href="#">There are no comments yet.</a></li>
				  <li><a href="#">Gliding Divs Using jParallax Plugin [Tutorial]</a></li>
				  <li><a href="#">Comparing HTML5 Mobile Web Framework</a></li>
				  <li><a href="#">Free Logo PSD Templates [Freebies]</a></li>
				  <li><a href="#">Stunning Modal Web PopUp [Freebies]</a></li>';
						}
					}
					
					//Initialize the getComments function 
					getComments();
				?>
				</ul>
			  </nav>
			  <!-- Sidebar Element Ends Here -->
			</aside>
			<!-- Another Sidebar Element Ends Here -->
			<!-- Article Element Starts Here -->
			<article id="contents">
			  <?php
					$query = 'SELECT board_post_id, board_post_tags, board_category_id, board_post_title, board_post_body FROM gv_board_post WHERE board_member = 1 AND board_post_block = 0 ORDER BY board_post_id DESC LIMIT 11';
					$result =  mysql_query($query, $db) or die(mysql_error($db));

					if(mysql_num_rows($result) !== 0)
					{	
						while($row = mysql_fetch_assoc($result))
						{
							extract($row);
							echo "<!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href=" . '"post_view.php?id=' . $row["board_post_id"] . '">' . $row["board_post_title"] . ' [' . $row["board_post_tags"] . ']</a></h1>
			  </header>
			  <!-- Article' . "'s Header Element Ends Here -->
			  <img " . 'src="../images/service_images/board/' . post_image($row["board_post_id"]) . '" alt="">
			  <p>' . truncate_text(html_entity_decode($row["board_post_body"]), 170, "No detail.") . '<br>
				<br>
				[<a href="post_view.php?id=' . $row["board_post_id"] . '">Read more</a>]</p>
				<hr/>
			  ';
						}
						
						echo '
			  <header>
				<h3><a href="recent.php">Recent posts</a> | <a href="hot_topics.php">Hot Topics</a> | <a href="mod_focus.php">Mod Focus</a></h3>
			  </header>';
					}
					else { 
			  ?>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">How to make your website Readable [Tips]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/featured_Image.jpg" alt="">
			  <p>An important aspect of font selection while designing a website, for one reason or another, I didn’t take in consideration is the readability of the “fonts”: “now” that you have the possibility to choose the font you want for your website, you have to be careful not to base the choice only on the aesthetic ( excited for the new CSS3), since the ability to set a certain font doesn’t necessarily mean that it’s the right choice, simply because some fonts are more readable than others [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">Gliding Divs Using jParallax Plugin [Tutorial]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/glding_divs.jpg"  alt="">
			  <p>jParallax turns a selected element into a ‘window’, or viewport, and all its children into absolutely positioned layers that can be seen through the viewport. These layers move in response to the mouse, and, depending on their dimensions (and options for layer initialisation), they move by different amounts, in a parallaxy kind of way [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">How to make your website Readable [Tips]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/featured_Image2.jpg" alt="">
			  <p>An important aspect of font selection while designing a website, for one reason or another, I didn’t take in consideration is the readability of the “fonts”: “now” that you have the possibility to choose the font you want for your website, you have to be careful not to base the choice only on the aesthetic ( excited for the new CSS3), since the ability to set a certain font doesn’t necessarily mean that it’s the right choice, simply because some fonts are more readable than others [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">Gliding Divs Using jParallax Plugin [Tutorial]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/glding_divs2.jpg"  alt="">
			  <p>jParallax turns a selected element into a ‘window’, or viewport, and all its children into absolutely positioned layers that can be seen through the viewport. These layers move in response to the mouse, and, depending on their dimensions (and options for layer initialisation), they move by different amounts, in a parallaxy kind of way [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">How to make your website Readable [Tips]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/featured_Image.jpg" alt="">
			  <p>An important aspect of font selection while designing a website, for one reason or another, I didn’t take in consideration is the readability of the “fonts”: “now” that you have the possibility to choose the font you want for your website, you have to be careful not to base the choice only on the aesthetic ( excited for the new CSS3), since the ability to set a certain font doesn’t necessarily mean that it’s the right choice, simply because some fonts are more readable than others [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">Gliding Divs Using jParallax Plugin [Tutorial]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/glding_divs.jpg"  alt="">
			  <p>jParallax turns a selected element into a ‘window’, or viewport, and all its children into absolutely positioned layers that can be seen through the viewport. These layers move in response to the mouse, and, depending on their dimensions (and options for layer initialisation), they move by different amounts, in a parallaxy kind of way [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">How to make your website Readable [Tips]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/featured_Image2.jpg" alt="">
			  <p>An important aspect of font selection while designing a website, for one reason or another, I didn’t take in consideration is the readability of the “fonts”: “now” that you have the possibility to choose the font you want for your website, you have to be careful not to base the choice only on the aesthetic ( excited for the new CSS3), since the ability to set a certain font doesn’t necessarily mean that it’s the right choice, simply because some fonts are more readable than others [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">Gliding Divs Using jParallax Plugin [Tutorial]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/glding_divs2.jpg"  alt="">
			  <p>jParallax turns a selected element into a ‘window’, or viewport, and all its children into absolutely positioned layers that can be seen through the viewport. These layers move in response to the mouse, and, depending on their dimensions (and options for layer initialisation), they move by different amounts, in a parallaxy kind of way [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">How to make your website Readable [Tips]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/featured_Image.jpg" alt="">
			  <p>An important aspect of font selection while designing a website, for one reason or another, I didn’t take in consideration is the readability of the “fonts”: “now” that you have the possibility to choose the font you want for your website, you have to be careful not to base the choice only on the aesthetic ( excited for the new CSS3), since the ability to set a certain font doesn’t necessarily mean that it’s the right choice, simply because some fonts are more readable than others [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">Gliding Divs Using jParallax Plugin [Tutorial]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/glding_divs.jpg"  alt="">
			  <p>jParallax turns a selected element into a ‘window’, or viewport, and all its children into absolutely positioned layers that can be seen through the viewport. These layers move in response to the mouse, and, depending on their dimensions (and options for layer initialisation), they move by different amounts, in a parallaxy kind of way [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">How to make your website Readable [Tips]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/featured_Image2.jpg" alt="">
			  <p>An important aspect of font selection while designing a website, for one reason or another, I didn’t take in consideration is the readability of the “fonts”: “now” that you have the possibility to choose the font you want for your website, you have to be careful not to base the choice only on the aesthetic ( excited for the new CSS3), since the ability to set a certain font doesn’t necessarily mean that it’s the right choice, simply because some fonts are more readable than others [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">Gliding Divs Using jParallax Plugin [Tutorial]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/glding_divs2.jpg"  alt="">
			  <p>jParallax turns a selected element into a ‘window’, or viewport, and all its children into absolutely positioned layers that can be seen through the viewport. These layers move in response to the mouse, and, depending on their dimensions (and options for layer initialisation), they move by different amounts, in a parallaxy kind of way [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">How to make your website Readable [Tips]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/featured_Image.jpg" alt="">
			  <p>An important aspect of font selection while designing a website, for one reason or another, I didn’t take in consideration is the readability of the “fonts”: “now” that you have the possibility to choose the font you want for your website, you have to be careful not to base the choice only on the aesthetic ( excited for the new CSS3), since the ability to set a certain font doesn’t necessarily mean that it’s the right choice, simply because some fonts are more readable than others [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">Gliding Divs Using jParallax Plugin [Tutorial]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/glding_divs.jpg"  alt="">
			  <p>jParallax turns a selected element into a ‘window’, or viewport, and all its children into absolutely positioned layers that can be seen through the viewport. These layers move in response to the mouse, and, depending on their dimensions (and options for layer initialisation), they move by different amounts, in a parallaxy kind of way [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">How to make your website Readable [Tips]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/featured_Image2.jpg" alt="">
			  <p>An important aspect of font selection while designing a website, for one reason or another, I didn’t take in consideration is the readability of the “fonts”: “now” that you have the possibility to choose the font you want for your website, you have to be careful not to base the choice only on the aesthetic ( excited for the new CSS3), since the ability to set a certain font doesn’t necessarily mean that it’s the right choice, simply because some fonts are more readable than others [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <!-- Article's Header Element Starts Here -->
			  <header>
				<h1><a href="#">Gliding Divs Using jParallax Plugin [Tutorial]</a></h1>
			  </header>
			  <!-- Article's Header Element Ends Here -->
			  <img src="../images/templates/responsiva-images/glding_divs2.jpg"  alt="">
			  <p>jParallax turns a selected element into a ‘window’, or viewport, and all its children into absolutely positioned layers that can be seen through the viewport. These layers move in response to the mouse, and, depending on their dimensions (and options for layer initialisation), they move by different amounts, in a parallaxy kind of way [...]<br>
				<br>
				[ <a href="#">Read more</a> | Advice | Romance | Dating ]</p>
				<hr/>
			  <header>
				<h3><a href="new_topics.php">New topics</a> | <a href="recent.php">Recent posts</a> | <a href="hot_topics.php">Hot Topics</a> | <a href="mod_focus.php">Mod Focus</a></h3>
			  </header><?php } ?>
			</article>
			<!-- Article Element Ends Here -->
		  </div>
		  <!-- Div wrapper2 ends here -->
		  <!-- Footer Element Starts Here -->
		  <footer id="copyrights">
			<p><?php

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
			
			$the_footer1 = '&copy; ' . $the_year . ' <a href="home.php">Genieverse Board</a> is a <a href="../index.php">Genieverse</a> site';
			echo $the_footer1;
		?></p>
			<p></p>
			<address>
				<?php
			$the_footer2 = ' <a href="../terms_of_use.php">Terms of Use</a>	|	<a href="../privacy_policy.php">Privacy Policy</a>	|	<a href="../contact_us.php">Contact us</a>	|	<a href="../sitemap.php">Site map</a>';
			echo $the_footer2; ?>
			</address>
		  </footer>
		  <!-- Footer Element Ends Here -->
		</div>
		<!-- Div Wrapper Element ends Here -->
	</body>
</html><?php mysql_close(); ?>
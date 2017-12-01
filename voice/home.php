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
	
	//Get name functions
	require_once('../Server_Includes/scripts/voice_scripts/category_name.php');
	
	//Get meta details
	require_once('../Server_Includes/scripts/voice_scripts/voice_meta.php');

	//Initiate count of unread messages 
	$unread_general_messages = unread_general_messages();
	
	//Set extra title	
	$extra_title = "Genieverse Voice - Tell the world what's happening | " . $voice_title_description;

	//Set meta details
	$meta_keyword = $voice_meta_keywords;
	$meta_description = $voice_meta_description;

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
		<!-- CUSTOM STYLE -->
		<link rel="stylesheet" href="../css/bootstrap_custom_css/template-style2.css">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
		<script type="text/javascript" src="../Server_Includes/API_and_plug_ins/jQuery/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="../Server_Includes/API_and_plug_ins/jQuery/jquery-ui.min.js"></script>    
		<script type="text/javascript" src="../Server_Includes/API_and_plug_ins/Modernizr/modernizr.js"></script>
		<script type="text/javascript" src="../Server_Includes/API_and_plug_ins/Responsee/responsee.js"></script>
		<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
		<![endif]-->
   </head>
   <body class="size-1000"><?php /*Use width of 1140 or 1000*/ ?>
      <!-- TOP NAV WITH LOGO -->
      <header>
         <nav>
            <div class="line">
               <div class="s-12 l-2">
                  <a href="home.php"><img class="s-12 l-12 center" src="../images/templates/logo_voice1.png"></a>
               </div>
               <div class="top-nav s-12 l-10 right">
                  <p class="nav-text">Menu</p>
                  <ul class="right">
					 <li><a href="../index.php">Genieverse</a></li>
					 <li><a href="home.php">Voice</a></li>
                     <?php if(!isset($_SESSION["logged_in"])){ ?><li><a href="../join_us.php">Join us</a></li><?php } ?>
					 <li><a href="recent.php">Recent</a></li>
					 <li><a href="loud_voices.php">Loud</a></li>
					 <li>
                        <a>Categories</a>
                        <ul>
                           <li><a href="search.php?category=Head-of-State">Head Of State</a></li><?php

		function getCategoryList($beforeUrl, $afterUrl)
		{
			global $db;
			$query = 'SELECT voice_category_name FROM gv_voice_category WHERE voice_category_lock = 0 ORDER BY voice_category_id ASC';
			$result = mysql_query($query, $db) or die(mysql_error($db));

			$row = mysql_fetch_assoc($result);
			
			//Get modified_for_url function
			require_once('../Server_Includes/scripts/common_scripts/modified_for_url.php');
			
			while($row = mysql_fetch_assoc($result))
			{
				extract($row);
				echo  '
			' . $beforeUrl . '<a href="search.php?category=' . modified_for_url($voice_category_name, ' ', '-', '') . '" class="list-group-item">' . $voice_category_name . '</a>' . $afterUrl;
			}
		}

		getCategoryList('<li>', '</li>');

				?>
                       </ul>
                    </li>
                     <?php if(isset($_SESSION["logged_in"])){ ?><li><a href="member/dashboard.php"><?php echo ucfirst($_SESSION ["username"]); ?>'s Dashboard</a></li>
					 <?php if($unread_general_messages < 1){ ?><li><a href="../messages.php"><?php echo $unread_general_messages; ?> Messages</a></li><?php }else{ ?><li style="background:#FF0000;"><a href="../messages.php"><?php echo $unread_general_messages; ?> Messages</a></li><?php } ?>
					 <li><a href="member/log_out.php">Log out</a></li><?php }else{ ?><li><a href="log_in.php">Log in</a></li><?php } ?>
					<li><a href="rss.php">RSS</a></li>
                  </ul>
               </div>
            </div>
         </nav>
      </header>
      
      <!-- ASIDE NAV AND CONTENT -->
      <div class="line">
         <div class="box  margin-bottom">
            <div class="margin">
               <!-- ASIDE NAV 1 -->
               <aside class="s-12 l-3">
                  <h3>Menu</h3>
                  <div class="aside-nav">
                     <ul>
                        <li><a href="recent.php">Recent Voices</a></li>
                        <li><a href="loud_voices.php">Loud Voices</a></li>
                        <li>
                            <a>Categories</a>
                            <ul>
                                <li><a href="search.php?category=Head-Of-State">Head Of State</a></li><?php

		getCategoryList('<li>', '</li>');

				?>
							</ul>
						</li>
						<?php if(isset($_SESSION["logged_in"])){ ?><li><a href="member/dashboard.php"><?php echo ucfirst($_SESSION ["username"]); ?>'s Dashboard</a></li>
						<?php if($unread_general_messages < 1){ ?><li><a href="../messages.php"><?php echo $unread_general_messages; ?> Messages</a></li><?php }else{ ?><li style="background:#FF0000;"><a href="../messages.php"><?php echo $unread_general_messages; ?> Messages</a></li><?php } ?>
						<li><a href="member/log_out.php">Log out</a></li><?php }else{ ?><li><a href="log_in.php">Log in</a></li><?php } ?>
						<li><a href="rss.php">Voice RSS</a></li>
					</ul>
				</div>
               </aside>

               <!-- CONTENT -->
               <section class="s-12 l-6">
                 <h1 style="background:#EEE;">About Genieverse Voice</h1>
                  <p style="text-align: justify">Create a new post, read or comment on posts about corrupt government officials, corrupt workers, indiscipline in a work place, extortions, scams, fraudulent companies and newest corrupt practices. Meet like-minded people.</p> 
                     <p style="text-align: justify">Join us in supporting and promoting Discipline, Morality, Honesty and other good virtues.</p> 
                     <p style="text-align: justify"><a href="loud_voice.php">Genieverse Voice Loud Voices</a> is a list of trending and serious Genieverse Voice posts that are pending settlement between concerned parties.</p> 
                     <p style="text-align: justify">'Can't find a post that relates to an experience, concern or complaint? Post in New Cause category asking for people with similar interests to champion your new cause who has what you want.</p>
                   <br>
				   <a href="../marketing.php"><img class="s-12 m-12 l-12" src="../images/templates/voice_adspace2.png" alt="Genieverse Voice - Adspace for sale"></a>
				  <a href="../marketing.php"><p style="color: #FFFFFF;">Click here to advertise on Genieverse Voice</p></a>
                  <h2 style="background:#EEE;">Trending posts</h2>
                  <?php
						$query = 'SELECT voice_post_id, voice_category_id, cc_id, voice_post_title, voice_post_body, voice_post_state, voice_post_city FROM gv_voice_post WHERE (voice_member = 1 AND voice_post_block = 0) AND (voice_post_settlement = 0) ORDER BY voice_post_id DESC LIMIT 10';
						$result =  mysql_query($query, $db) or die(mysql_error($db));
						
						if(mysql_num_rows($result) !== 0)
						{	
							while($row = mysql_fetch_assoc($result))
							{
								extract($row);
				?><h3><a href="post_view.php?id=<?php
						echo  $row["voice_post_id"];
//						echo modified_for_url(category_name($row["voice_category_id"]), ' ', '-', '&Category=');
	//					echo modified_for_url($row["voice_post_title"], ' ', '-', '&Post=');
						?>"><?php echo $row["voice_post_title"]; ?></a></h3>
                 <?php echo
truncate_text(html_entity_decode($row["voice_post_body"]), 170, "No detail."); ?>
						<p><?php echo country_name($row["cc_id"]) . ' - ' . $row["voice_post_state"] . ' - ' . $row["voice_post_city"]; ?></p><br><?php
							}			
						}
						else {
								echo '<h3>' . "There're no posts yet.</h3><br>";
						}
					?>
               </section>
               <!-- ASIDE NAV 2 -->
               <aside class="s-12 l-3">
                  <h3>Resolved matters</h3>
                  <div class="aside-nav">
                     <ul>
                        <?php
						$query = 'SELECT voice_post_id, voice_category_id, voice_post_title FROM gv_voice_post WHERE (voice_member = 1 AND voice_post_block = 0) AND (voice_post_settlement = 1) ORDER BY voice_post_id DESC LIMIT 10';
						$result =  mysql_query($query, $db) or die(mysql_error($db));

						if(mysql_num_rows($result) !== 0)
						{	
							while($row = mysql_fetch_assoc($result))
							{
								extract($row);
				?><li><a href="post_view.php?id=<?php
						echo  $row["voice_post_id"];
		//				echo modified_for_url(category_name($row["voice_category_id"]), "&Category=");
		//				echo modified_for_url($row["voice_post_title"], "&post=");
						?>"><?php echo $row["voice_post_title"]; ?></a></li><?php
							}			
						}
						else {
								echo '<li><a>' . "There're no resolved matters yet.</a></li>";
						}
					?>
                     </ul>
					 <br/> 
                     <ul>
                        <a><h3 style="background:#FFF;">Newest Members</h3></a>
                       <li> <?php

					   	//Get list of newest members
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
				echo ' <a href="' . $docLevelUrlPrefix . 'member_profile.php?id=' . $newestMember["member_id"] . '">' . ucfirst($newestMember["username"]) . '</a> ';
			}
		}
		else{ echo " No user yet "; }
	}

   echo get_newest_members('../');
?></li>
                     </ul>
                  </div>
               </aside>
            </div>
         </div>
      </div>
      <!-- FOOTER -->
      <footer class="box">
         <div class="line">
            <div class="s-12 l-6">
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
	 /*Copyright: &#169; &copy; */ ?>
<?php	$the_footer1 = '&copy; ' . $the_year . ' <a href="home.php">Genieverse Voice</a> is owned by <a href="../index.php">Genieverse</a>';
	echo $the_footer1;
?></p>
            </div>
            <div class="s-12 l-6">
               <a class="right" href="http://www.myresponsee.com" title="Genieverse Terms of Use, Privacy policy and Contact us."><?php
	$the_footer2 = ' <a href="../terms_of_use.php">Terms of Use</a>	|	<a href="../privacy_policy.php">Privacy Policy</a>	|	<a href="../contact_us.php">Contact us</a>	|	<a href="../sitemap.php">Site map</a>';
	echo $the_footer2; ?></a>
            </div>
         </div>
      </footer>
   </body>
</html><?php mysql_close(); ?>
<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');

	$service = (isset($_GET["service"])) ? trim($_GET["service"]) : "";
	
	if($service == "")
	{
		//Do nought
		header('Location: index.php');
		exit;
	}
    
	//Connect to the database
	require_once('Server_Includes/visitordbaccess.php');
	
	//Get modified_for_url function
	require_once('Server_Includes/scripts/common_scripts/modified_for_url.php');

	//Set page template
	$three_col_portfolio = "set";

	//Set extra title
	$extra_title = "Categories ";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body style="background-color:#fff;">
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">

        <!-- Page Header -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Categories</h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Projects Row -->
        <div class="row">
			<div class="col-sm-12 col-lg-12 text-center"><?php
	if($service == 'mall'){
?>
				<h3><a href="mall/home.php">Genieverse Mall</a></h3> 
				<ul class="list-unstyled">
	             <?php 
  
       $queryMall = 'SELECT mall_category_name FROM gv_mall_category';
       $resultMall =  mysql_query($queryMall, $db) or die(mysql_error($db));
						
		if(mysql_num_rows($resultMall) !== 0)
	   {
           while($rowMall = mysql_fetch_assoc($resultMall))
	        {
             echo '<li class="col-sm-12 col-lg-12"><a href="mall/search.php?category=' .  $rowMall["mall_category_name"] . '">' . ucfirst($rowMall["mall_category_name"]) . '</a></li> 
	             ' . "\n";
			 }
		}
		else{
	   	           echo '<li>There are no categories for Genieverse Mall</li> 
	             ';
		}

 ?>
				</ul>
			</div>
			<div class="col-sm-12 col-lg-12 text-center">
				<h4><a href="categories.php?service=voice">Genieverse Voice</a> | <a href="categories.php?service=board">Genieverse Board</a> | <a href="categories.php?service=spotlight">Genieverse Spotlight</a> | <a href="categories.php?service=hearts">Genieverse Hearts</a></h4>
			</div>
        </div><hr/><?php } ?>

        <!-- Projects Row -->
        <div class="row">
			<div class="col-sm-12 col-lg-12 text-center"><?php
	if($service == 'voice'){
?>
				<h3><a href="voice/home.php">Genieverse Voice</a></h3> 
				<ul class="list-unstyled">
					<?php 
  
       $queryVoice = 'SELECT voice_category_name FROM gv_voice_category';
       $resultVoice =  mysql_query($queryVoice, $db) or die(mysql_error($db));
						
		if(mysql_num_rows($resultVoice) !== 0)
		{
           while($rowVoice = mysql_fetch_assoc($resultVoice))
	        {
             echo '<li class="col-sm-12 col-lg-12"><a href="voice/search.php?category=' .  modified_for_url($rowVoice["voice_category_name"], ' ', '-', '') . '">' . ucwords($rowVoice["voice_category_name"]) . '</a></li> 
	             ' . "\n";
			 }
		}
		else{
	   	           echo '<li>There are no categories for Genieverse Voice</li> 
	             ';
		}

 ?>
				</ul>
			</div>
			<div class="col-sm-12 col-lg-12 text-center">
				<h4><a href="categories.php?service=mall">Genieverse Mall</a> | <a href="categories.php?service=board">Genieverse Board</a> | <a href="categories.php?service=spotlight">Genieverse Spotlight</a> | <a href="categories.php?service=hearts">Genieverse Hearts</a></h4>
			</div>
        </div><hr/><?php } ?>

        <!-- Projects Row -->
        <div class="row">
			<div class="col-sm-12 col-lg-12 text-center"><?php
	if($service == 'board'){
?>
				<h3><a href="board/home.php">Genieverse Board</a></h3> 
				<ul class="list-unstyled">
	             <?php 
  
       $queryBoard = 'SELECT board_category_name FROM gv_board_category';
       $resultBoard =  mysql_query($queryBoard, $db) or die(mysql_error($db));
						
		if(mysql_num_rows($resultBoard) !== 0)
	   {
           while($rowBoard = mysql_fetch_assoc($resultBoard))
	        {
             echo '<li class="col-sm-12 col-lg-12"><a href="board/search.php?category=' .  $rowBoard["board_category_name"] . '">' . ucfirst($rowBoard["board_category_name"]) . '</a></li> 
	             ' . "\n";
			 }
		}
		else{
	   	           echo '<li>There are no categories for Genieverse Board</li> 
	             ';
		}

 ?>
				</ul>
			</div>
			<div class="col-sm-12 col-lg-12 text-center">
				<h4><a href="categories.php?service=mall">Genieverse Mall</a> | <a href="categories.php?service=voice">Genieverse Voice</a> | <a href="categories.php?service=spotlight">Genieverse Spotlight</a> | <a href="categories.php?service=hearts">Genieverse Hearts</a></h4>
			</div>
        </div><hr/><?php } ?>

        <!-- Projects Row -->
        <div class="row">
			<div class="col-sm-12 col-lg-12 text-center"><?php
	if($service == 'spotlight'){
?>
				<h3><a href="spotlight/home.php">Genieverse Spotlight</a></h3> 
				<ul class="list-unstyled">
	             <?php 
  
		$querySpotlight = 'SELECT spotlight_category_name FROM gv_spotlight_category';
		$resultSpotlight =  mysql_query($querySpotlight, $db) or die(mysql_error($db));

		if(mysql_num_rows($resultSpotlight) !== 0)
		{
           while($rowSpotlight = mysql_fetch_assoc($resultSpotlight))
	        {
             echo '<li class="col-sm-12 col-lg-12"><a href="spotlight/search.php?category=' .  $rowSpotlight["spotlight_category_name"] . '">' . ucfirst($rowSpotlight["spotlight_category_name"]) . '</a></li> 
	             ' . "\n";
			 }
		}
		else{
	   	           echo '<li>There are no categories for Genieverse Spotlight</li> 
	             ';
		}

 ?>
				</ul>
			</div>
			<div class="col-sm-12 col-lg-12 text-center">
				<h4><a href="categories.php?service=mall">Genieverse Mall</a> | <a href="categories.php?service=voice">Genieverse Voice</a> | <a href="categories.php?service=board">Genieverse Board</a> | <a href="categories.php?service=hearts">Genieverse Hearts</a></h4>
			</div>
        </div><hr/><?php } ?>

        <!-- Projects Row -->
        <div class="row">
			<div class="col-sm-12 col-lg-12 text-center"><?php
	if($service == 'hearts'){
?>
				<h3><a href="hearts/home.php">Genieverse Hearts</a></h3> 
				<ul class="list-unstyled">
	             <?php 
  
		$queryHearts = 'SELECT hearts_category_name FROM gv_hearts_category';
		$resultHearts =  mysql_query($queryHearts, $db) or die(mysql_error($db));

		if(mysql_num_rows($resultHearts) !== 0)
		{
           while($rowHearts = mysql_fetch_assoc($resultHearts))
	        {
             echo '<li class="col-sm-12 col-lg-12"><a href="hearts/search.php?category=' .  $rowHearts["hearts_category_name"] . '">' . ucfirst($rowHearts["hearts_category_name"]) . '</a></li> 
	             ' . "\n";
			 }
		}
		else{
	   	           echo '<li>There are no categories for Genieverse Hearts</li> 
	             ';
		}

 ?>
				</ul>
			</div>
			<div class="col-sm-12 col-lg-12 text-center">
				<h4><a href="categories.php?service=mall">Genieverse Mall</a> | <a href="categories.php?service=voice">Genieverse Voice</a> | <a href="categories.php?service=board">Genieverse Board</a> | <a href="categories.php?service=spotlight">Genieverse Spotlight</a></h4>
			</div>
        </div><hr/><?php } ?>

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p><?php echo $the_footer; ?></p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

<?php require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_before_body_end.php'); ?>

</body>

</html><?php
   mysql_close();
?>
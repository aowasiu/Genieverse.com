<?php

	//Get footer script
	require_once('Server_Includes/scripts/genieverse_shell/outer_page_footer1.php');

	//Get all meta data
	require_once('Server_Includes/scripts/common_scripts/all_services_meta.php');

	//Set meta details
	$meta_keyword = $all_meta_keywords;
	$meta_description = $all_meta_description;

?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keyword" content="genieverse classified, genieverse forum, genieverse whistleblower, genieverse expose, <?php echo $meta_keyword; ?>">
    <meta name="description" content="Genieverse portal for Genieverse Mall, Genieverse Voice, Genieverse Offender and Genieverse Board, <?php echo $meta_description; ?>">
    <meta name="author" content="Wasiu Adisa">

    <title><?php
	if(isset($extra_title))
	{
		echo $extra_title . " | ";
	}
	else{
			//Do nought
	} ?> Genieverse - Virtual universe</title>
	<link rel="icon" type="image/png" href="images/genieverse_logos/favicon.ico">

	<!-- Bootstrap Core CSS -->
    <?php
	if($_SERVER['HTTP_HOST'] == 'www.genieverse.com')
	{ ?><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<?php
	}
	else{	?><link href="Server_Includes/API_and_plug_ins/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
	<?php
	} ?>
<!--Genieverse Custom CSS -->
	<?php
	if(isset($blog_home))
	{ ?><link href="css/bootstrap_custom_css/blog-home.css" rel="stylesheet">
	<?php
	}
	elseif(isset($business_casual))
	{ ?><link href="css/bootstrap_custom_css/business-casual.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic" rel="stylesheet" type="text/css">
	<?php
	}
	elseif(isset($grayscale))
	{ ?><link href="css/bootstrap_custom_css/grayscale.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
	<?php
	}
	elseif(isset($shop_item))
	{ ?><link href="css/bootstrap_custom_css/shop-item.css" rel="stylesheet">
	<?php
	}
	elseif(isset($thumbnail_gallery))
	{ ?><link href="css/bootstrap_custom_css/thumbnail-gallery.css" rel="stylesheet">
	<link href="Server_Includes/API_and_plug_ins/bootstrap_lightbox/bootstrap-lightbox.min.css" rel="stylesheet">
	<link type="text/javascript" href="Server_Includes/API_and_plug_ins/bootstrap_lightbox/bootstrap-lightbox.min.js" >
	<?php
	}
	elseif(isset($three_col_portfolio))
	{ ?><link href="css/bootstrap_custom_css/3-col-portfolio.css" rel="stylesheet">
	<?php
	}
	elseif(isset($stylish_portfolio))
	{
	?><link href="css/stylish-portfolio.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
	<?php
	}
	else{
	//Do nought
	}	?>

	<?php if(isset($fancy_box)){ ?>
	<?php if($_SERVER['HTTP_HOST'] == 'www.genieverse.com'){ ?><script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
	<?php }else{ ?><script type="text/javascript" src="Server_Includes/API_and_plug_ins/jQuery/jquery-2.1.4.min.js"></script>
	<?php } ?>
		<link type="text/css" media="screen" rel="stylesheet" href="Server_Includes/API_and_plug_ins/fancyapps-fancyBox-18d1712/source/jquery.fancybox.css">
		<script type="text/javascript" src="Server_Includes/API_and_plug_ins/fancyapps-fancyBox-18d1712/source/jquery.fancybox.pack.js"></script>
	<?php
		}//End of if(isset($fancy_box)){
	?>

	<?php
		if(isset($ckeditor))
		{ ?>
	<script <?php if($_SERVER['HTTP_HOST'] == 'www.genieverse.com'){ ?> src="//cdn.ckeditor.com/4.5.3/standard/ckeditor.js" <?php }else{ ?> src="Server_Includes/API_and_plug_ins/ckeditor/ckeditor.js" <?php } ?>></script>
	<?php
	} ?>
	
	<?php
	if(isset($recaptcha_here))
	{
	?>	
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<?php
	}
	?>
	
	<?php
	if(isset($extra_script))
	{
		echo "\n" . $extra_script;
	}
	?>
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

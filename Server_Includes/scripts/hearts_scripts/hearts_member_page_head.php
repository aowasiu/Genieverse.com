<!DOCTYPE html>
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
	<link rel="icon" type="image/png" href="../../images/genieverse_logos/favicon.ico">

    <!-- Bootstrap Core CSS -->
    <link href="../../Server_Includes/API_and_plug_ins/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../css/bootstrap_custom_css/landing-page.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
	
	<script  src="../../Server_Includes/API_and_plug_ins/ckeditor/ckeditor.js" ></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

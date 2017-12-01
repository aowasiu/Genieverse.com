<?php

	if(!isset($_SESSION["logged_in"]))
	{
		session_start();
	}
	else{
		//Carry on
	}

	//Create your error handle function
	function gv_error_handler($e_type, $e_message, $e_file, $e_line)
	{
		$msg	=	'Errors occured while opening a page:<br>';
		$msg	.=	'Type of Error: ' . $e_type . '<br>';
		$msg	.=	'Message about the error:<br>';
		$msg	.=	$e_message . '<br>';
		$msg	.=	'Name of the file in which the error occurred in:<br>';
		$msg	.=	'The error occurred on this line of the file: ' . $e_number . '<br>';
		$msg	=	wordwrap($msg, 75);
		
		switch($error_type)
		{
			case E_ERROR:
			 mail('error_report@genieverse.com', 'Fatal Error encountered on Genieverse - ' . date('l F d, Y. g:i A'), $msg);
			 die();
			 break;
			
			case E_WARNING:
			 mail('error_report@genieverse.com', 'Warning generated on Genieverse - ' . date('l F d, Y. g:i A'), $msg);
			 break;
		}
	}

	/*Set error handling to 0(zero) because all error reporting will be handled by this script and the Administrator will be consequently notified of warnings and fatal errors.*/
	
	//Set the error handler to be used
	set_error_handler('gv_error_handler');
	
	/*
	If there is a serious debug problem and you can't find it or you're getting white space on the browser, simply change the code below to  error_reporting(-1); and restart Apache server if you can. After finishing the troubleshooting, revert to error_reporting(1);  
	*/
	error_reporting(1);

	if(!isset($mobile_view_menu_colour))
	{
		$mobile_view_menu_colour = '#ffffff';
	}
	$mobile_view_menu = '<span style="color: ' . $mobile_view_menu_colour . ';">Menu</span>
                    <span class="icon-bar"></span>';
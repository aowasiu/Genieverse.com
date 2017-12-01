<?php

	/* Get Genieverse Mall, Genieverse Voice, Genieverse Spotlight, Genieverse Board and Genieverse Hearts meta details.	*/
	require_once('Server_Includes/scripts/mall_scripts/mall_meta.php');
	require_once('Server_Includes/scripts/voice_scripts/voice_meta.php');
	require_once('Server_Includes/scripts/spotlight_scripts/spotlight_meta.php');
	require_once('Server_Includes/scripts/board_scripts/board_meta.php');
//	require_once('Server_Includes/scripts/hearts_scripts/hearts_meta.php');

	/*	Combine all meta details as a single variable, respectively.	*/
	$all_meta_keywords = $mall_meta_keywords . ', ' . $voice_meta_keywords . ', ' . $spotlight_meta_keywords . ', ' . $board_meta_keywords;
	$all_meta_description = $mall_meta_description . ', ' . $voice_meta_description . ', ' . $spotlight_meta_description . ', ' . $board_meta_description;
	$all_title_description = $mall_title_description . ', ' . $voice_title_description . ', ' . $spotlight_title_description . ', ' . $board_title_description;
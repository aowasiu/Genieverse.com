<?php

	//This must come before any script
	$time_start = microtime(true);

	//This must come at the end of all script
	$time_end = microtime(true);
	
	//Dividing with 60 will output the result in minutes, otherwise, it'll be in seconds
	$execution_time = ($time_end - $time_start) / 60;
	
	echo ' This script took ' . round($execution_time, 4) . ' seconds to execute.';

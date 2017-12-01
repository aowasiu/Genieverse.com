<?php

	/* Method 1
	function getUserIP()
	{
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if(filter_var($client, FILTER_VALIDATE_IP))
		{
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP))
		{
			$ip = $forward;
		}
		else
		{
			$ip = $remote;
		}

		return $ip;
	}
	 = 41.58.3.26
	*/
	
	/* Method 2
	function getUserIP() {
		if( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
			if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
				$addr = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
				return trim($addr[0]);
			} else {
				return $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
		}
		else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}
	*/
	
	/* Method 3
	function getUserIP()
	{
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'Unknown IP Address';

		return $ipaddress;
	}
	$user_ip = getUserIP();

	echo $user_ip; // Output IP address [Ex: 177.87.193.134]
	*/
	
	/* Method 4
	$ip = getenv('HTTP_CLIENT_IP')?:
	getenv('HTTP_X_FORWARDED_FOR')?:
	getenv('HTTP_X_FORWARDED')?:
	getenv('HTTP_FORWARDED_FOR')?:
	getenv('HTTP_FORWARDED')?:
	getenv('REMOTE_ADDR');

	echo $ip . " --- <br/>";
	*/

	/* Method 5 */
	function get_IP_address()
	{
		foreach (array('HTTP_CLIENT_IP',
					   'HTTP_X_FORWARDED_FOR',
					   'HTTP_X_FORWARDED',
					   'HTTP_X_CLUSTER_CLIENT_IP',
					   'HTTP_FORWARDED_FOR',
					   'HTTP_FORWARDED',
					   'REMOTE_ADDR') as $key){
			if (array_key_exists($key, $_SERVER) === true){
				foreach (explode(',', $_SERVER[$key]) as $IPaddress){
					$IPaddress = trim($IPaddress); // Just to be safe

					if (filter_var($IPaddress,
								   FILTER_VALIDATE_IP,
								   FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
						!== false) {

						return $IPaddress;
					}
				}
			}
		}
	}
	$remote = get_IP_address();

	//$remote	= $_SERVER["REMOTE_ADDR"];
<?php

	if(!filter_var($email_address, FILTER_VALIDATE_EMAIL))
	{
		$errors[] =  'The email address <b>' . $email_address . '</b> is not acceptable.';
	}
	else {
			$safe_email = $email_address;
	}
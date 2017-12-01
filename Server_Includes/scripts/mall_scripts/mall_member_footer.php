<?php

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
	
	$the_footer = '<a href="../../index.php">Genieverse</a> <a href="../home.php">Mall</a> &copy; ' . $the_year . ' is owned by <b>Wasiu Adisa</b><br>' . ' <a href="../../terms_of_use.php">Terms of Use</a>	|	<a href="../../privacy_policy.php">Privacy Policy</a>	|	<a href="../../contact_us.php">Contact us</a>	|	<a href="../../sitemap.php">Site map</a>';

	//<a href="https://www.facebook.com/Adisawasiuolayemi">Wasiu Adisa</a>	

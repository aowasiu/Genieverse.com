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

?>
    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="list-inline">
                        <li>
                            <a href="#services"> &copy; <?php echo $the_year; ?></a>
                        </li>
                        <li>
                            <a href="home.php">Genieverse Hearts</a> is a <a href="../index.php">Genieverse</a> site
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                            <a href="../privacy_policy.php">Privacy policy</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                            <a href="../terms_of_use.php">Terms of Use</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                            <a href="../correspondence.php">Contact us</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="../Server_Includes/API_and_plug_ins/jQuery/jquery-2.1.4.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../Server_Includes/API_and_plug_ins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

<?php

	if($_SERVER['HTTP_HOST'] == 'www.genieverse.com')
	{ ?>
	<!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	
    <!-- Bootstrap Core JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<?php
	}
	else{
?>	<!-- jQuery -->
    <script src="Server_Includes/API_and_plug_ins/jQuery/jquery-2.1.4.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="Server_Includes/API_and_plug_ins/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<?php
			if(isset($blog_home)){ ?>
			<!--Default scripts already set-->
<?php
			}//End of if(isset($blog_home))
			elseif(isset($business_casual)){ ?>
	
	<!-- Script to Activate the Carousel -->
    <script>
    $('.carousel').carousel({
        interval: 1250 //changes the speed, default=5000
    })
    </script>
<?php
			}//End of if(isset($business_casual))
			elseif(isset($grayscale)){ ?>
	
    <!-- Plugin JavaScript -->
    <script src="Server_Includes/API_and_plug_ins/bootstrap-3.3.5-dist/custom_bootstrap/jquery.easing.min.js"></script>

    <!-- Google Maps API Key - Use your own API key to enable the map feature. More information on the Google Maps API can be found at https://developers.google.com/maps/ -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRngKslUGJTlibkQ3FkfTxj3Xss1UlZDA&sensor=false"></script>

    <!-- Custom Theme JavaScript -->
    <script src="Server_Includes/API_and_plug_ins/bootstrap-3.3.5-dist/custom_bootstrap/grayscale.js"></script>
<?php
			}//End of if(isset(grayscale))
			elseif(isset($shop_item)){ ?>
			<!--Default scripts already set-->
<?php
			}//End of if(isset($shop_item))
			elseif(isset($three_col_portfolio)){ ?>
			<!--Default scripts already set-->
<?php
			}//End of if(isset($three_col_portfolio))
			elseif(isset($stylish_portfolio)){ ?>

    <!-- Custom Theme JavaScript -->
    <script>
    // Closes the sidebar menu
    $("#menu-close").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Opens the sidebar menu
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Scrolls to the selected menu item on the page
    $(function() {
        $('a[href*=#]:not([href=#])').click(function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {

                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                    return false;
                }
            }
        });
    });
    </script>
<?php
			}//End of isset($stylish_portfolio)
			else{
					//Do nought
			}
	}//End of if($_SERVER['HTTP_HOST'] == 'www.genieverse.com')
	
?>
<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');

	//Set default values for variables
	$your_names_default = "Your full name";
	$your_friends_email_default = "Your friend's email address";
	$senders_name = (isset($_POST["senders_name"])) ? $_POST["senders_name"]: $your_names_default;
	$recipients_email = (isset($_POST["recipients_email"])) ? $_POST["recipients_email"]: $your_friends_email_default;
	$recipients_phone = (isset($_POST["recipients_phone"])) ? $_POST["recipients_phone"]: "Your friend's phone number";
	
	//Connect to the database
	require_once('Server_Includes/visitordbaccess.php');

	//Set errors as arrays
	$errors = array();
	
	//Get tell_a_friend_processing script
	require_once('Server_Includes/scripts/common_scripts/tell_a_friend_processing.php');

	//Call reCATPCHA plugin for this page
	$recaptcha_here = "here";

	//Set extra title
	$extra_title = "Tell a friend about Genieverse";

	//Set page template name
	$shop_item = "set";

	//Set email address field default string
	$submitted_email_default = "Your email address";

	//Get page head element properties	
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>

<?php require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php'); ?>
    <!-- Page Content -->
    <div class="container">
    
		<div class="caption-full">
            <h5 class="pull-right"></h5>
			<h3>Tell a friend about <a href="index.php">Genieverse</a></h3>
			<hr>
		</div>

		<?php
		if($errors)
		{
			echo '<div class="row">
            <div class="col-lg-12 text-center">
				<div class="alert alert-danger">';
					foreach($errors as $error)
					{
						echo '<p>' . $error . '</p>';
					}
			echo '				</div>
			</div>
		</div>';
		}
?>
		
		<?php	if($errors)
				{
					$senders_name = $your_names_default;
				   $recipients_email = $your_friends_email_default;
				}	?><form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
		<div class="form-group col-lg-12">
            <div class="col-lg-6 col-lg-offset-3 text-center">
				<div>
					<noscript><label>Your full name</label></noscript>
					<input type="text" class="form-control" maxlength="45" name="senders_name" placeholder="<?php echo $senders_name; ?>">
				</div>
			</div>
		</div>
		
		<div class="form-group col-lg-12">
            <div class="col-lg-6 col-lg-offset-3 text-center">
				<div>
					<noscript><label>Your friend's email</label></noscript>
					<input type="text" class="form-control" maxlength="50" name="recipients_email" placeholder="<?php echo $recipients_email; ?>">
				</div>
			</div>
		</div>
		
		<div class="form-group col-lg-12">
            <div class="col-lg-6 col-lg-offset-3 text-center">
				<div class="panel panel-info">
					<div class="panel-heading">
						<p class="panel-title">reCATPCHA</p>
						<div class="panel-body">
							<div class="embed-responsive embed-responsive-16by9 form-group col-lg-3">
								<div class="g-recaptcha" data-sitekey="6Lec2A4TAAAAALqB68TEFCSwZJZLQVK8FnzOhJKK">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group col-lg-12">
			<div class="row text-center">
				<div class="col-md-12">
					<span class="pull-right"></span>
					<input class="btn btn-info" type="submit" name="submit" value="Tell my friend">
				</div>
			</div>
		</div>

	</form>

    </div>
    <!-- /.container -->

	<div class="container">

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p><?php echo $the_footer; ?></p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

<?php require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_before_body_end.php'); ?>

</body>

</html><?php 
	if(isset($_SESSION["errand_title"])){ unset($_SESSION["errand_title"]); }
	if(isset($_SESSION["errand"])){ unset($_SESSION["errand"]); }
?>
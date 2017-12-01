	
        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                    <hr>
                    <h2 class="intro-text text-center">Log in</h2>
                    <hr>
                    <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label>Username or Email address:</label>
                                <input type="text" class="form-control" maxlength="50" name="username" placeholder="<?php echo $username; ?>">
                            </div>
                            <div class="form-group col-lg-5">
                                <label>Password:</label>
								<input type="hidden" name="location" value="<?php echo $previous_location; ?>">
                                <input type="password" class="form-control" name="password" maxlength="15" placeholder="<?php echo $password; ?>">
                            </div>
                            <div class="form-group col-lg-1">
                                <br>
                                <input class="btn btn-default" type="submit" name="submit" value="Log in">
                            </div>
							<div class="col-lg-12 text-center">
								<p class="btn btn-default"><a href="<?php echo $custom_forgotten_path; ?>">Forgotten Password?<br>Click here to get a new one</a></p>
								<p class="btn btn-default pull-right"><a href="<?php echo $custom_forgotten_path; ?>join_us.php">Not a member, click here to join us</a></p>
							</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
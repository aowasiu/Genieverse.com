<?php

	//Get custom error function script 
	require_once('Server_Includes/scripts/common_scripts/feature_error_message.php');
	
	if(!isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] !== 1)
	{
		//Take this visitor to the log in page because he's not logged in
		header("Location: log_in.php?location=" . urlencode($_SERVER["REQUEST_URI"]));
		exit;
	}
	
	//Connect to the database
	require_once('Server_Includes/visitordbaccess.php');

	$delete = (isset($_GET["delete"])) ? $_GET["delete"] : 0;
	if(isset($delete) && $delete !== 0) 
	{	
		$query = 'DELETE FROM gv_general_messages_reply_rights WHERE general_message_id = ' . mysql_real_escape_string($delete, $db);
		mysql_query($query, $db) or die (mysql_error($db));

		$query = 'DELETE FROM gv_general_messages WHERE general_message_id = ' . mysql_real_escape_string($delete, $db) . ' AND gv_gen_message_to = ' . $_SESSION["member_id"];
		mysql_query($query, $db) or die (mysql_error($db));
		
		$_SESSION["errand"] = "The message was deleted successfully";
	}
	
	//Get date format
	require_once('Server_Includes/scripts/common_scripts/date_formats.php');
	
	//Get "text truncator"
	require_once('Server_Includes/scripts/common_scripts/text_truncate_function.php');

	//Get the_username function
	require_once('Server_Includes/scripts/common_scripts/get_name_functions.php');
	
	$query = 'SELECT general_message_id, gv_gen_replied, gv_gen_read, gv_gen_message_created_on, gv_gen_message_body, gv_gen_message_from FROM gv_general_messages WHERE gv_gen_blocked = 0 AND gv_gen_message_to = ' . $_SESSION["member_id"] . ' ORDER BY gv_gen_message_created_on DESC ';

	$data = mysql_query($query) or die(mysql_error());
	$rows = mysql_num_rows($data); //This is also the value in " for number_format($rows) results."
	
	if($rows == 0)
	{
		$_SESSION["errand"] = "You have no private message.";
		header("Location: services.php");
		exit;
		//$zero_row_count = "There're no message.";
	}
	else {
		//This checks to see if ther's a page number. If not, it will set it to page 1
		$pagenum = (isset($_GET["pagenum"])) ? $_GET["pagenum"] : 1;
		
		//This checks to see if ther's a page number. If not, it will set it to page 1
		if(!(isset($pagenum)))
		{
			$pagenum = 1;
		}
		
		//This is the number of rows to be displayed as results per page
		$page_rows = 17;
		
		//This tells us the page number of our last page
		$last = ceil($rows/$page_rows); //This is " Last "
		$next = $pagenum + 1;
		$previous = $pagenum - 1;
		
		//This makes sure the page number isn't below one, or more than the maximum pages
		if($pagenum < 1)
		{
			$pagenum = 1;
		}
		elseif($pagenum > $last)
		{
			$pagenum = $last;
		}
		
		//This sets the range of results to display per page for any page view.	
		$max = ' LIMIT ' . ($pagenum - 1)*$page_rows . ', ' . $page_rows;
		
		//This is the query again, the same one but with the $max variable incorporated.
		$data_p = mysql_query($query . $max) or die(mysql_error($db));	
	}	

	//Get declaration of pagination function
	require_once('Server_Includes/scripts/common_scripts/pagination2.php');

	//Set extra title
	$extra_title = ucfirst($_SESSION["username"]) . "'s messages";

	//Set page template name
	$three_col_portfolio = "set";

	//Get page head element properties
	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_common_member_head.php'); ?><body>
<?php	require_once('Server_Includes/scripts/genieverse_shell/outer_pages_header1.php');	?>
    <!-- Page Content -->
    <div class="container">

		<div class="caption-full">
            <?php if(isset($_SESSION["errand"])){ echo '<h5 class="pull-right alert alert-success">' . $_SESSION["errand"] . '</h5>';
					unset($_SESSION["errand"]); }?>
		</div>
		
<?php
	if(isset($_SESSION["errand"]))
	{
		echo '
		<div class="row">
            <div class="col-lg-12 text-center">
				<div class="alert alert-danger">
					<p class="text-center">' . $_SESSION["errand"] . '</p>
				</div>
			</div>
		</div>';
		unset($_SESSION["errand"]);
	}
?>
            <div class="col-md-12">

				<div class="caption-full">
					<h3><?php echo ucfirst($_SESSION["username"]); ?>'s Private Messages</h3>
				</div>
				<hr>
				<br>

<?php /* <form role="form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> */ ?>
			<?php
	if($rows !== 0)
	{
?><table class="table table-hover col-md-9">
					<thead>
						<tr>
							<th>Sender</th>
							<th>Message</th>
							<th>Date</th>
							<th>Action</th>
						</tr>
				</thead>
				<tbody>
<?php
		while($row = mysql_fetch_assoc($data_p))
		{
			extract($row);
			if($row["gv_gen_read"] == 1)
			{ $the_css = 'active ';}
			else { 	$the_css = ""; }
?>				<tr class="<?php echo $the_css; ?>caption-full">
					<td><?php echo the_username($row["gv_gen_message_from"]); ?></td>
					<td><a href="message_view.php?id=<?php echo $row["general_message_id"]; ?>"><?php echo truncate_text(html_entity_decode($row["gv_gen_message_body"]), 15, "Blank message."); ?></a></td>
					<td><?php echo change_date_long($row["gv_gen_message_created_on"]); ?></td>
					<td><a class="btn btn-default" href="messages.php?delete=<?php echo $row["general_message_id"]; ?>">Delete</a></td>
				</tr>
<?php 
		}//End of while($row = mysql_fetch_assoc($data_p))
	
	/*
				<div class="form-group caption-full">
					<h5 class="pull-right"></h5>
					<h5><input class="form-control" type="submit" name="delete_all" value="Delete selected posts"></h5>
					<hr>
				</div>
				
				<div class="form-group caption-full">
					<h5 class="pull-right"></h5>
					<h5><input class="form-control" type="submit" name="delete_all" value="Delete selected posts"></h5>
					<hr>
				</div>
				
		</form> */ ?>
			</tbody>
		</table>
        
		</div>
		<!-- /.row -->
        <hr>
	
<?php
		$adjacents = 3;
		//$reload = $_SERVER['PHP_SELF'];
	echo paginate_two($page_rows, $pagenum, $rows, $adjacents, $previous, $next, $last);

	}//End of if($rows !== 0)
	
	if($rows == 0)
	{
?>			
				<div class="form-group caption-full">
					<h5 class="pull-right"></h5>
					<h5><?php echo $zero_row_count; ?></h5>
					<hr>
				</div>
<?php
	}
?>
<?php /*	
				<div class="form-group caption-full">
					<h5 class="pull-right"></h5>
					<h5><input class="form-control" type="submit" name="delete_all" value="Delete selected posts"></h5>
					<hr>
				</div>
		</form> */ ?>
	
        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-8 col-md-offset-1">
                    <p><?php echo $the_footer; ?></p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

<?php require_once('Server_Includes/scripts/common_scripts/member_page_common_before_body_end.php'); ?>

</body>

</html><?php mysql_close($db); ?>
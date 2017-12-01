<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
 <table>
  <tr>
   <td><input type="text" name="search_keywords"
   <?php
   
   		if(isset($_POST['search_keywords']))
		{
			echo ' value="' . htmlspecialchars($_POST['search_keywords']) . '"';
		}
   
   ?> /> <input type="submit" value="Search Voice" /></td>
  </tr>
 </table>
</form>
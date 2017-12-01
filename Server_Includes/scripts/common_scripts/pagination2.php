<?php
/*************************************************************************
php easy :: pagination scripts set - Version Two
==========================================================================
Author:      php easy code, www.phpeasycode.com
Web Site:    http://www.phpeasycode.com
Contact:     webmaster@phpeasycode.com
*************************************************************************/
	
	function paginate_two($page_rows, $pagenum, $rows, $adjacents, $previous, $next, $last)
	{
		$firstlabel = "&laquo;";
		$prevlabel  = "&lsaquo;";
		$nextlabel  = "&rsaquo;";
		$lastlabel  = "&raquo;";
		
		$out = '<!-- Pagination -->
        <div class="row text-center">
            <div class="col-lg-12">
                <ul class="pagination">' . "\n";

		// First link
		if($pagenum > ($adjacents+1))
		{
			$out.= "<li><a href='{$_SERVER['PHP_SELF']}?pagenum=1'>" . $firstlabel . "</a></li>";
			$out .= "\n";
		}
		else {
			$out.= "<li><a href=\"#\">" . $firstlabel . "</a></li>";
			$out .= "\n";
		}
		
		// Previous link
		if($pagenum == 1)
		{
			$out .= "<li><a href=\"#\">" . $prevlabel . "</a></li>";
			$out .= "\n";
		}
		elseif($pagenum == 2)
		{
			$out.= "<li><a href='{$_SERVER['PHP_SELF']}?pagenum=$previous'>" . $prevlabel . "</a></li>";
			$out .= "\n";
		}
		else{
			$out.= "<li><a href='{$_SERVER['PHP_SELF']}?pagenum=$previous'>" . $prevlabel . "</a></li>";
			$out .= "\n";
		}

		// 1 2 3 4 etc
		$pmin = ($pagenum > $adjacents) ? ($pagenum - $adjacents) : 1;
		$pmax = ($pagenum < ($rows - $adjacents)) ? ($pagenum + $adjacents) : $rows;
		for($i=$pmin; $i<=$pmax; $i++)
		{
			if($i == $pagenum)
			{
				$out.= "<li class=\"active\"><a href=\"#\">" . $i . "</a></li>";
				$out .= "\n";
			}
			elseif($i == 1)
			{
				$out.= "<li><a href=\"{$_SERVER['PHP_SELF']}\">" . $i . "</a></li>";
				$out .= "\n";
			}
			else {
					if($i == $last)
					{
						//Do nought
						$out.= "<li><a href=\"{$_SERVER['PHP_SELF']}?pagenum=" . $i . "\">" . $i . "</a></li>";
						$out .= "\n";
					}
					else{	
							$out.= "<li><a href=\"{$_SERVER['PHP_SELF']}?pagenum=" . $i . "\">" . $i . "</a></li>";
							$out .= "\n";
					}
			}
		}
		
		//Next link
		if($rows > $page_rows && $pagenum !== $last)
		{
			$out.= "<li><a href='{$_SERVER['PHP_SELF']}?pagenum=$next'>" . $nextlabel . "</a></li>";
			$out .= "\n";
		}
		elseif($rows > $page_rows && $pagenum == $last)
		{
			$out.= "<li><a href='{$_SERVER['PHP_SELF']}?pagenum=$next'>" . $nextlabel . "</a></li>";
			$out .= "\n";
		}
		elseif($rows < $page_rows)
		{
			$out.= "<li><a href='#'>" . $nextlabel . "</a></li>";
			$out .= "\n";
		}
		
		//Last link
		if($pagenum == $last)
		{
			$out.= "<li><a href=\"#\">" . $lastlabel . "</a></li>";
			$out .= "\n";
		}
		else {
			$out.= "<li><a href='{$_SERVER['PHP_SELF']}?pagenum=$last'>" . $lastlabel . "</a></li>";
			$out .= "\n";
		}
		
		$out.= "  </ul>
            </div>
        </div>
        <!-- /.row -->";
		
		$out .= '<!-- Pagination -->
        <div class="row text-center">
            <div class="col-lg-12">
                <ul class="pagination">';

		if($last > 1){ $pages_or_not = "pages"; }else{ $pages_or_not = "page"; }
		$out .= "
					<li> Page " . number_format($pagenum) . " of " . number_format($last) . " $pages_or_not for " . number_format($rows) . " results.</li>
				</ul>";
		
		$out.= "
            </div>
        </div>
        <!-- /.row -->";
		
		return $out;

	}

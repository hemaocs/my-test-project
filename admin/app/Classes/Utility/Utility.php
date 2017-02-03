<?php
namespace Appsolute\Backend\Classes\Utility;

class Utility {
	
	public function pagination($total_pages, $page, $start, $limit, $page_name){ 
		$i=1;
		$targetpage = $page_name;
		$adjacents = 3;
		if ($page == 0) $page = 1;
		$prev = $page - 1;
		$next = $page + 1;
		$lastpage = ceil($total_pages/$limit);
		$lpm1 = $lastpage - 1;
		$pagination = "";
		if($lastpage > 1){
			//previous button
			if ($page > 1) {
				//$pagination.= "<li><a href=\"$page_name\"> First </span>";
				$pagination.= "<li><a href=\"$page_name/$prev/limit/$limit\"> Prev </li></a>";
			}else{
				//$pagination.= "<span class=\"disabled\"> First </span>";
				$pagination.= "<li><span class=\"disabled\"> Prev </span></li>";
			}
			//pages	
			if ($lastpage < 7 + ($adjacents * 2))
			{	
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class=\"active\"><span>$counter</span></li>";
					else
						$pagination.= "<li><a href=\"$page_name/$counter/limit/$limit\">$counter</a></li>";					
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2))
			{
				if($page < 1 + ($adjacents * 2))		
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li class=\"active\"><span>$counter</span></li>";
						else
							$pagination.= "<li><a href=\"$page_name/$counter/limit/$limit\">$counter</a></li>";				
					}
					$pagination.= "<li><a>...</a></li>";
					$pagination.= "<li><a href=\"$page_name/$lpm1/limit/$limit\">$lpm1</a></li>";
					$pagination.= "<li><a href=\"$page_name/$lastpage/limit/$limit\">$lastpage</a></li>";		
				}
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<li><a>...</a></li>";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li class=\"active\"><span>$counter</span></li>";
						else
							$pagination.= "<li><a href=\"$page_name/$counter/limit/$limit\">$counter</a></li>";					
					}
					$pagination.= "<li><a>...</a></li>";
					$pagination.= "<li><a href=\"$page_name/$lpm1/limit/$limit\">$lpm1</a></li>";
					$pagination.= "<li><a href=\"$page_name/$lastpage/limit/$limit\">$lastpage</a></li>";		
				}
				else
				{
					$pagination.= "<li><a>...</a></li>";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li class=\"active\"><span>$counter</span></li>";
						else
							$pagination.= "<li><a href=\"$page_name/$counter/limit/$limit\">$counter</a></li>";			
					}
				}
			}
			
			if ($page < $counter - 1) {
				$pagination.= "<li><a href=\"$page_name/".($page + 1)."/limit/".$limit."\">Next</a></li>";
				//$pagination.= "<a href=\"$page_name/$lastpage\">Last</a>";
			}else{
				$pagination.= "<li><span class=\"disabled\">Next</span></li>";
				//$pagination.= "<span class=\"disabled\">Last</span>";
			}
			
			return $pagination;
		}
	}

	public function singlePagination($count, $page, $limit) {
		$i=1;
		$targetpage = '';
		$adjacents = 3;
		$prev = $page - 1;
		$next = $page + 1;
		$total_pages = $count;
		$lastpage = ceil($total_pages/$limit);
		$lpm1 = $lastpage - 1;
		$pagination = "";
		if($lastpage > 1){
			//previous button
			if ($page > 1) {
				//$pagination.= "<li><a href=\"$page_name\"> First </span>";
				$pagination.= "<li class='nohighlight' value='".($page-1)."'> <span>Prev</span> </li>";
			}else{
				//$pagination.= "<span class=\"disabled\"> First </span>";
				$pagination.= "<li class='nohighlight' value='1'><span class=\"disabled\"> Prev </span></li>";
			}
			//pages	
			if ($lastpage < 7 + ($adjacents * 2))
			{	
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li value='$counter' class=\"active\"><span>$counter</span></li>";
					else
						$pagination.= "<li value='$counter'><span>$counter</span></li>";					
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2))
			{
				if($page < 1 + ($adjacents * 2))		
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li value='$counter' class=\"active\"><span>$counter</span></li>";
						else
							$pagination.= "<li value='$counter'><span>$counter</span></li>";				
					}
					$pagination.= "<li><span>...</span></li>";
					$pagination.= "<li value='$lpm1'><span>$lpm1</span></li>";
					$pagination.= "<li value = '$lastpage'><span>$lastpage</span></li>";		
				}
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<li><span>...</span></li>";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li value='$counter' class=\"active\"><span>$counter</span></li>";
						else
							$pagination.= "<li value='$counter'><span>$counter</span></li>";					
					}
					$pagination.= "<li><span>...</span></li>";
					$pagination.= "<li value='$lpm1'><span>$lpm1</span></li>";
					$pagination.= "<li value='$lastpage'><span>$lastpage</span></li>";		
				}
				else
				{
					$pagination.= "<li><span>...</span></li>";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li value='$counter' class=\"active\"><span>$counter</span></li>";
						else
							$pagination.= "<li value='$counter'><span>$counter</span></li>";			
					}
				}
			}
			
			if ($page < $counter - 1) {
				$pagination.= "<li class='nohighlight' value ='".($page + 1)."'><span>Next</span></li>";
				//$pagination.= "<a href=\"$page_name/$lastpage\">Last</a>";
			}else{
				$pagination.= "<li value ='0' class='nohighlight' ><span class=\"disabled\">Next</span></li>";
				//$pagination.= "<span class=\"disabled\">Last</span>";
			}
			
			return $pagination;
		}
	}
	
	public function sanitizeFilename($filename) {
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$utimestamp = microtime(true);    
		$timestamp = floor($utimestamp);
		$milliseconds = round(($utimestamp - $timestamp) * 1000000);
		$name = date(preg_replace('`(?>!\\\\)u`', $milliseconds, 'YmdHisu'), $timestamp);									
		return $name.'.'.$ext;
	}
	
	public function doStripContent($str, $start, $end) {
		$content = $stip_str = "";
		$arr_str = array();
		if (strlen($str) < $end) {
				$content = $str;
		} else {			
			$stip_str = mb_substr($str, 0, $end, "UTF-8");				
			$content = rtrim($stip_str," ").'.';
		}
		return rtrim($content," ");
	}
	
	public function hoursToMinutes($hours) {
		if (strstr($hours, ':')) {
			# Split hours and minutes.
			$separatedData = explode(':', $hours);

			$minutesInHours    = $separatedData[0] * 60;
			$minutesInDecimals = $separatedData[1];

			$totalMinutes = $minutesInHours + $minutesInDecimals;
		} else {
			$totalMinutes = $hours * 60;
		}
		return $totalMinutes;
	}

	public function doGetMonths() {
		$months = array('january' => 'Janvier', 'february' => 'Février', 'march' => 'Mars', 'april' => 'Avril',
			            'may' => 'Mai', 'june' => 'Juin', 'july' => 'Juillet', 'august' => 'Août',
			            'september' => 'Septembre', 'october' => 'Octobre', 'november' => 'Novembre', 'december' => 'Décembre');
		return $months;
	}
		
}

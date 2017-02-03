<?php

namespace Appsolute\Api\Classes;

use Appsolute\Api\Models;

Class Utils {

	private $tables;

		public function sanitizeFilename($filename) {
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
        	$utimestamp = microtime(true);    
      		$timestamp = floor($utimestamp);
      		$milliseconds = round(($utimestamp - $timestamp) * 1000000);
      		$name = date(preg_replace('`(?>!\\\\)u`', $milliseconds, 'YmdHisu'), $timestamp);									
			return $name.'.'.$ext;
		}
		
		public function doStripContent($str, $start, $end)
		{
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
}
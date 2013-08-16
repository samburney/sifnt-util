<?php
class sU
{
	// Quick function to call print_r within a HTML <pre>
	public static function debug($arr){
		$backtrace = debug_backtrace();
		$file = $backtrace[0]['file'];
		$line = $backtrace[0]['line'];
		
		echo '<pre>';
		echo "<b>$file:$line</b>\n";
		print_r($arr);
		echo '</pre>';
	}

	// Convert Bytes to prettier number
	public static function bytesToSize($bytes, $precision = 2, $multiplier = 1024)
	{  
	    $kilobyte = $multiplier;
	    $megabyte = $kilobyte * $multiplier;
	    $gigabyte = $megabyte * $multiplier;
	    $terabyte = $gigabyte * $multiplier;
	   
	    if (($bytes >= 0) && ($bytes < $kilobyte)) {
	        return $bytes . ' B';
	 
	    } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
	        return round($bytes / $kilobyte, $precision) . ' KB';
	 
	    } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
	        return round($bytes / $megabyte, $precision) . ' MB';
	 
	    } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
	        return round($bytes / $gigabyte, $precision) . ' GB';
	 
	    } elseif ($bytes >= $terabyte) {
	        return round($bytes / $terabyte, $precision) . ' TB';
	    } else {
	        return $bytes . ' B';
	    }
	}	
}
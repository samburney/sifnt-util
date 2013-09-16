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
	        return round($bytes / $kilobyte, $precision) . ' kB';
	 
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
	
	/*
	 * Function to Convert stdClass Objects to Multidimensional Arrays
	 * http://www.if-not-true-then-false.com/2009/php-tip-convert-stdclass-object-to-multidimensional-array-and-convert-multidimensional-array-to-stdclass-object/
	 */
	public static function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(array('sU', 'objectToArray'), $d);
		}
		else {
			// Return array
			return $d;
		}
	}
	 
	/*
	 * Function to Convert Multidimensional Arrays to stdClass Objects
	 * http://www.if-not-true-then-false.com/2009/php-tip-convert-stdclass-object-to-multidimensional-array-and-convert-multidimensional-array-to-stdclass-object/
	 */
	public static function arrayToObject($d) {
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return (object) array_map(array('sU', 'objectToArray'), $d);
		}
		else {
			// Return object
			return $d;
		}
	}
	
	// Check if IPv4 address is in given prefix
	public static function inIpPrefix($cidr, $ip)
	{
		list($prefix, $subnet) = explode('/', $cidr);

		$subnet_bin = str_pad('', $subnet, '1') . str_pad('', 32 - $subnet, '0');

		$prefix_dec = ip2long($prefix);
		$prefix_bin = str_pad(decbin($prefix_dec), '32', '0', STR_PAD_LEFT) & $subnet_bin;

		$ip_dec = ip2long($ip);
		$ip_bin = str_pad(decbin($ip_dec), '32', '0', STR_PAD_LEFT);

		return $prefix_bin == ($ip_bin & $subnet_bin);
	}
}
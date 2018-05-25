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
	
	// Convert prettier number to bytes
	public static function sizeToBytes($size, $multiplier = 1024)
	{  
		preg_match('/([.0-9]+) *(|[kmgt])(|ib|b)$/i', $size, $result);
		
		$number = $result[1];
		$unit = strtolower($result[2]);

		switch($unit) {
			case 'k':
				return $number * $multiplier;
				break;

			case 'm':
				return $number * $multiplier * $multiplier;
				break;

			case 'g':
				return $number * $multiplier * $multiplier * $multiplier;
				break;

			case 't':
				return $number * $multiplier * $multiplier * $multiplier * $multiplier;
				break;

			default:
				return $number;
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
	
	// Check if IPv6 address is in given prefix
	public static function inIp6Prefix($cidr, $ip)
	{
		list($prefix, $subnet) = explode('/', $cidr);

		$subnet_bin = str_pad('', $subnet, '1') . str_pad('', 128 - $subnet, '0');

		$prefix_dec = ip2long($prefix);
		$prefix_bin = str_pad(decbin($prefix_dec), '128', '0', STR_PAD_LEFT) & $subnet_bin;

		$ip_dec = ip2long($ip);
		$ip_bin = str_pad(decbin($ip_dec), '128', '0', STR_PAD_LEFT);

		return $prefix_bin == ($ip_bin & $subnet_bin);
	}

	// Convert CIDR notation to Address + Subnet Mask
	public static function cidrToSubnet($cidr) {
		list($prefix, $subnet) = explode('/', $cidr);

		$subnet_bin = str_pad('', $subnet, '1') . str_pad('', 32 - $subnet, '0');
		$subnet_dec = bindec($subnet_bin);
		$subnet_mask = long2ip($subnet_dec);

		return "$prefix $subnet_mask";
	}

	// Convert CIDR notation to first Subnet ID
	public static function cidrToSubnetId($cidr)
	{
		list($prefix, $subnet) = explode('/', $cidr);

		$subnet_bin = str_pad('', $subnet, '1') . str_pad('', 32 - $subnet, '0');

		$prefix_dec = ip2long($prefix);
		
		$subnetid_bin = str_pad(decbin($prefix_dec), '32', '0', STR_PAD_LEFT) & $subnet_bin;
		$subnetid_dec = bindec($subnetid_bin);
		$subnetid = long2ip($subnetid_dec);

		return($subnetid . '/' . $subnet);
	}

	// Convert Subnet mask to Wildcard mask
	public static function subnetToWildcardMask($subnet_mask) {
		$subnet_dec = ip2long($subnet_mask);
		$subnet_bin = str_pad(decbin($subnet_dec), '32', '0', STR_PAD_LEFT);

		$wildcard_bin = str_replace('0', '2', $subnet_bin);
		$wildcard_bin = str_replace('1', '0', $wildcard_bin);
		$wildcard_bin = str_replace('2', '1', $wildcard_bin);

		$wildcard_dec = bindec($wildcard_bin);
		$wildcard_mask = long2ip($wildcard_dec);

		return $wildcard_mask;
	}

	// Get number of week in a month - https://stackoverflow.com/questions/32615861/get-week-number-in-month-from-date-in-php/32624747
	public static function weekOfMonth($date) {
	    //Get the first day of the month.
	    $firstOfMonth = strtotime(date("Y-m-01", $date));
	    //Apply above formula.
	    return intval(date("W", $date)) - intval(date("W", $firstOfMonth)) + 1;
	}
	
	/** 
	* Like vsprintf, but accepts $args keys instead of order index. 
	* Both numeric and strings matching /[a-zA-Z0-9_-]+/ are allowed. 
	* 
	* Example: vskprintf('y = %y$d, x = %x$1.1f', array('x' => 1, 'y' => 2)) 
	* Result:  'y = 2, x = 1.0' 
	* 
	* $args also can be object, then it's properties are retrieved 
	* using get_object_vars(). 
	* 
	* '%s' without argument name works fine too. Everything vsprintf() can do 
	* is supported. 
	* 
	* @author Josef Kufner <jkufner(at)gmail.com> 
	*/ 
	public static function vksprintf($str, $args) 
	{ 
	    if (is_object($args)) { 
	        $args = get_object_vars($args); 
	    } 
	    $map = array_flip(array_keys($args)); 
	    $new_str = preg_replace_callback('/(^|[^%])%([a-zA-Z0-9_-]+)\$/', 
	            function($m) use ($map) { return $m[1].'%'.($map[$m[2]] + 1).'$'; }, 
	            $str); 
	    return vsprintf($new_str, $args); 
	} 
}
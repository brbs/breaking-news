<?php defined('ABSPATH') || exit;

if(!function_exists('printaj')) {

	function printaj($var) {
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}
}

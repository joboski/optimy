<?php 

namespace optimy\app\core;

class Helper
{
	
	public static function pre($data)
	{
		echo "<pre>";
		echo "===========<br>";
		print_r($data);
		echo "<br>===========";
		echo "</pre>";
	}

	public static function dump($data)
	{
		echo "<pre>";
		var_dump($data);
		echo "</pre>";
		exit;
	}
}

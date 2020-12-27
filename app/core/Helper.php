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

	public static function dump($data1, $data2 = null, $data3 = null)
	{
		echo "<pre>";
		var_dump($data1, $data2, $data3);
		echo "</pre>";
		exit;
	}
}

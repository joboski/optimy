<?php 

namespace optimy\app\core;

class Helper
{
	
	public static function pre($data)
	{
		echo "<pre>";
		print_r($data);
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

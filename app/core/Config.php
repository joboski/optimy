<?php  

namespace optimy\app\core;

class Config
{
	public static function get($path)
	{
		if ($path) {
			$config = $GLOBALS['config'];
			$arrayPath = explode("/", $path);

			foreach ($arrayPath as $value) {
				// check the path if is defined or existing
				if (isset($config[$value])) {
					$config = $config[$value];
				}
				return $config;
			}
		}
	}
}

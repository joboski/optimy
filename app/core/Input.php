<?php 

namespace optimy\app\core;

use optimy\app\core\Helper;

class Input
{

	private static function filter($body, $input)
	{
		Helper::pre($body);
		foreach ($body as $key => $value) {
			$body[$key] = filter_input($input, $key, FILTER_SANITIZE_SPECIAL_CHARS);
		}

		return $body;
	}

	public static function clean($method)
	{
		if ($method === "get") {
		 	return self::filter($_GET, INPUT_GET);
		}

		return self::filter($_POST, INPUT_POST);
	}
}

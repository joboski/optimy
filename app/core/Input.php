<?php 

namespace optimy\app\core;

class Input
{
	private static function filter($body, $input)
	{
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

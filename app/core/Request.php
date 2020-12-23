<?php 

namespace optimy\app\core;

use optimy\app\core\Config;
use optimy\app\core\Helper;
use optimy\app\core\Input;

class Request
{
	public function __construct()
	{
		Helper::pre("Constructing Request");
	}

	public function path()
	{
		// Helper::dump("Testing path");
		$base_url= Config::get("base_url"); // get the define base url on the env

		$path = str_replace($base_url, "", $_SERVER['REQUEST_URI']) ?? "/";
		$pos = strpos($path, "?");

		if (!$pos) {
			return $path;
		}
	
		return substr($path, 0, $pos); // use case: /blog?id=1 will return /blog
	}

	/**
	*  return HTTP_METHOD = [get, post, update]
	*/
	public function method()
	{
		return strtolower($_SERVER['REQUEST_METHOD']);
	}

	public function isPost()
	{
		return $this->method() === "post";
	}

	public function isGet()
	{
		return $this->method() === "get";
	}

	/*
	 * returns the body from the request
	 *
	 */
	public function body()
	{
		Helper::pre($_POST);

		$method = $this->method();

		$body = Input::clean($method);

		return $body;
	}
}

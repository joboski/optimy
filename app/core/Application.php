<?php

namespace optimy\app\core;

use optimy\app\core\Router;
use optimy\app\core\Request;
use optimy\app\core\Response;
use optimy\app\core\Helper;


class Application
{
	private $controller;
	public $router;
	public $request;
	public $response;
	public static $ROOT_PATH;
	public static $app;

	private function __construct()
	{
		Helper::pre("constructing Application");
		
		$this->response = new Response();
		$this->request = new Request();
		$this->router = new Router($this->request, $this->response);
	}

	public function run()
	{
		echo $this->router->resolve();
	}

	public function setRootPath($rootPath)
	{ 
		self::$ROOT_PATH = $rootPath;
	}

	public static function instance()
	{
		if(!isset(self::$app)) {
			return self::$app = new Application();
		}
		return self::$app;
	}

	public function getController()
	{
		return $this->controller;
	}

	public function setController($controller)
	{
		$this->controller = $controller;
	}
}

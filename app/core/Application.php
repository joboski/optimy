<?php

namespace optimy\app\core;

use optimy\app\core\Router;
use optimy\app\core\Request;
use optimy\app\core\Response;
use optimy\app\core\Session;
use optimy\app\connections\MyConnection;


class Application
{
	public $controller;
	public $router;
	public $request;
	public $response;
	public $session;
	public $db;
	public static $ROOT_PATH;
	public static $app;

	private function __construct()
	{
		
		$this->db = MyConnection::getConnection(); // MySQL 
		$this->response = new Response();
		$this->request = new Request();
		$this->router = new Router($this->request, $this->response);
		$this->session = new Session();
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

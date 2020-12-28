<?php

namespace optimy\app\core;

use optimy\app\core\Config;
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
	public $user;
	public $userRepo;
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
		
		$class = Config::get('class');
		$repo = Config::get('repo');

		$value = $this->session->get('user');

		// Helper::pre("Application");
		if ($value) {
			// Helper::pre("Inside value");
			$this->user = new $class;
			$key = $this->user->primaryKey();

			$this->userRepo = new $repo($this->user);
			$this->user = $this->userRepo->findUserById($value);
		}	
	}

	public static function instance()
	{
		if(!isset(self::$app)) {
			return self::$app = new Application();
		}
		return self::$app;
	}

	public static function isGuest(){
		return !self::$app->user;
	}

	public function run()
	{
		echo $this->router->resolve();
	}

	public function setRootPath($rootPath)
	{ 
		self::$ROOT_PATH = $rootPath;
	}

	

	public function getController()
	{
		return $this->controller;
	}

	public function setController($controller)
	{
		$this->controller = $controller;
	}

	public function login($user)
	{
		// Helper::pre("Inside Application Login");
		$this->user = $user;
		// Helper::pre($this->user);
		$primaryKey = $this->user->primaryKey();
		$value = $user->{$primaryKey};

		$this->session->set('user', $value);
		$this->session->setFlash("success" , "Successfully login");

		return true;
	}

	public function logout()
	{
		$this->user = null;
		$this->session->remove('user');
	}
}

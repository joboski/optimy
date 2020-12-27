<?php 

namespace optimy\app\controllers;

use optimy\app\controllers\Controller;
use optimy\app\services\UserService;
use optimy\app\services\LoginService;
use optimy\app\core\Application;
use optimy\app\core\Request;
use optimy\app\core\Helper;


class AuthController extends Controller 
{
	private $service;
	private $loginService;

	public function __construct()
	{
		$this->service = new UserService();
	}

	public function login(Request $request)
	{
		$this->setLayout('auth');
		$this->service = new LoginService();


		if ($request->isPost()) {

			if ($this->service->login($request->body())) {
				Application::$app->session->setFlash("login" , "Successfully login");
				Application::$app->response->redirect("/"); // home
				exit;
			}
			Application::$app->session->setFlash("fail" , "Failed to login. Incorrect email or password.");
			// return Helper::pre("Handling submitted data");
			return $this->view("login", ["model" => $this->service->model()]);
		}

		return $this->view("login", ["model" => $this->service->model()]);
	}

	public function register(Request $request)
	{
		$this->setLayout('auth');

		if ($request->isPost()) {

			if ($this->service->save($request->body())) {
				Application::$app->session->setFlash("success" , "Thank you for registering");
				Application::$app->response->redirect("/"); // home
				exit;
			}
			Application::$app->session->setFlash("fail" , "Failed to register.");
			return $this->view("register", ["model" => $this->service->model()]);
		}
		
		return $this->view("register", ["model" => $this->service->model()]);
	}
}

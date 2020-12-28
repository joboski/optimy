<?php 

namespace optimy\app\controllers;

use optimy\app\controllers\Controller;
use optimy\app\services\UserService;
use optimy\app\models\Login;
use optimy\app\models\User;
use optimy\app\core\Application;
use optimy\app\core\Request;
use optimy\app\core\Response;
use optimy\app\core\Helper;


class AuthController extends Controller 
{
	private $service;
	private $request;
	private $response;
	private $loginModel;
	private $userModel;

	public function __construct()
	{
		$this->loginModel = new Login();
		$this->userModel = new User();
	}

	public function register(Request $request, Response $response)
	{
		$this->request = $request;
		$this->response = $response;

		$this->setLayout('auth');

		// Helper::pre($this->request->body());
		$this->userModel->load($this->request->body());
		$this->service = new UserService($this->userModel);

		if ($request->isPost()) {

			if ($this->userModel->validate() && $this->service->save()) {
				Application::$app->session->setFlash("success" , "Thank you for registering");
				$this->response->redirect("/"); // home
				exit;
			}
			Application::$app->session->setFlash("fail" , "Failed to register.");
			return $this->view("register", ["model" => $this->userModel]);
		}
		
		return $this->view("register", ["model" => $this->userModel]);
	}


	public function login(Request $request, Response $response)
	{
		$this->request = $request;
		$this->response = $response;

		$this->setLayout('auth');
		$this->loginModel->load($request->body());
		$this->service = new UserService($this->userModel);

		if ($request->isPost()) {
			// 		
			Helper::pre("AuthController: Data has been loaded to login model");
			if ($this->loginModel->validate() && $this->service->login($this->loginModel)) {
				Application::$app->session->setFlash("login" , "Successfully login");
				// Application::$app->session->set("user", )
				$this->response->redirect("/"); // home
				return;
			}
		
			Application::$app->session->setFlash("fail" , "Failed to login. Incorrect email or password.");
			// return Helper::pre("Handling submitted data");
			return $this->view("login", ["model" => $this->loginModel]);
		}

		return $this->view("login", ["model" => $this->loginModel]);
	}

	public function logout(Request $request, Response $response)
	{
		Application::$app->logout();
		$response->redirect("/");
	}
}

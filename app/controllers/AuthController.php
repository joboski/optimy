<?php 

namespace optimy\app\controllers;

use optimy\app\controllers\Controller;
use optimy\app\services\UserService;
use optimy\app\core\Request;
use optimy\app\core\Helper;


class AuthController extends Controller 
{
	private $service;

	public function __construct()
	{
		$this->service = new UserService();
	}

	public function login(Request $request)
	{
		$this->setLayout('auth');

		if ($request->isPost()) {

			return Helper::pre("Handling submitted data");
		}

		return $this->view("login", ["model" => $this->service->model()]);
	}

	public function register(Request $request)
	{
		$this->setLayout('auth');

		if ($request->isPost()) {
			return $this->service->register($request->body());
		}
		
		return $this->view("register", ["model" => $this->service->model()]);
	}
}

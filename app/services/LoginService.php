<?php

namespace optimy\app\services;

use optimy\app\models\Login;
use optimy\app\repositories\UserRepository;


class LoginService 
{
	private $model;
	private $repo;

	public function __construct()
	{
		$this->model = new Login();
		$this->repo = new UserRepository($this->model);
	}

	public function login($data)
	{
		$this->model->load($data);
		$this->model->validate($data);

		if (empty($this->model->getErrors()) && $this->repo->login()) {
			return true;
		}
		return false;
	}

	public function model()
	{
		return $this->model;
	}
}

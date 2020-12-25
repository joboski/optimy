<?php 

namespace optimy\app\services;

use optimy\app\models\User;

class UserService 
{
	private $model;

	public function __construct()
	{
		$this->model = new User();
	}	

	public function register($data)
	{
		$this->model->load($data);

		if ($this->model->validate() && $this->loaded()) {
			return true;
		}

		return false;
	}

// Let the service call the model and not the controller
	public function model()
	{
		return $this->model;
	}

	private function loaded()
	{
		Helper::pre("loading registration!");

		return true;
	}
}

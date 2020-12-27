<?php 

namespace optimy\app\services;

use optimy\app\models\User;
use optimy\app\repositories\UserRepository;
use optimy\app\core\Helper;


class UserService 
{
	private $model;
	private $repo;

	public function __construct()
	{
		$this->model = new User();
		$this->repo = new UserRepository($this->model);
	}	

	public function save($data)
	{
		$this->model->load($data);
		$this->model->validate();
		
		if (empty($this->model->getErrors()) && $this->repo->save()) {
			return true;
		}

		return false;
	}

// Let the service call the model and not the controller
	public function model()
	{
		return $this->model;
	}
}

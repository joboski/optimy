<?php 

namespace optimy\app\services;

use optimy\app\models\User;
use optimy\app\repositories\UserRepository;
use optimy\app\core\Application;
use optimy\app\core\Helper;


class UserService 
{
	private $model;
	private $repo;

	public function __construct(User $model)
	{
		$this->model = $model;
		$this->repo = new UserRepository($this->model);
	}	

	public function save()
	{	
		// Helper::pre("Inside service save");
		$this->model->password = password_hash($this->model->password, PASSWORD_DEFAULT);
		$this->model->status = $this->model->defaultStatus();
		$this->repo->setModel($this->model);
		
		$model = $this->model;
		$values = array_map(function($a) use ($model){
			return $model->{$a};
		}, $this->model->attributes());
		
		return $this->repo->save($values);
	}

	public function login($loginModel)
	{
		$user = $this->repo->getUserObject($loginModel->email);

		if (!$user) {
			$loginModel->addError('email', 'User does not exist.');
			return false;
		}

		if (!password_verify($loginModel->password, $user->password)) {
			$loginModel->addError('password', 'Password for the Username is incorrect.');
			$loginModel->addError('email', 'Username for the Password is incorrect.');
			return false;
		}
		Application::$app->login($user);

		return true;
	}
}

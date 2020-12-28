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
		// Helper::dump($model->status);
		$attributes = $this->model->attributes();

		$model = $this->model;
		$values = array_map(function($a) use ($model){
			return $model->{$a};
		}, $attributes);
		
		return $this->repo->save($attributes, $values);
	}

	public function login($loginModel)
	{
		$user = $this->repo->findUser($loginModel->email);

		if (!$user) {
			$loginModel->addError('email', 'User does not exist.');
			// Helper::pre($this->model->errors);
			return false;
		}

		if (!password_verify($loginModel->password, $user->password)) {
			$loginModel->addError('password', 'Password for the Username is incorrect.');
			$loginModel->addError('email', 'Username for the Password is incorrect.');
			// Helper::pre("Password failed");
			return false;
		}
		// Application::$app->session->set("user", $user->id);
		Application::$app->login($user);

		return true;
	}
}

<?php 

namespace optimy\app\repositories;

use optimy\app\connections\MyConnection;
use optimy\app\models\User;
use optimy\app\repositories\Repository;
use optimy\app\core\Helper;

class UserRepository extends Repository
{
	public function __construct($model)
	{
		$this->pdo = MyConnection::getConnection()->pdo;
		$this->model = $model;
	}

	public function save() {
		// Helper::dump($model);
		$table = $this->model->tableName();
		$model = $this->model;
		$attributes = $this->model->attributes();

		$this->model->password = password_hash($this->model->password, PASSWORD_DEFAULT);
		$this->model->status = $model->defaultStatus();
		// Helper::dump($model->status);
		$values = array_map(function($a) use ($model){
			return $model->{$a};
		}, $attributes);

		// Helper::dump($this->table, $attributes, $values);
		return $this->insert($table, $attributes, $values);
	}

	public function login()
	{
		$table = $this->model->tableName();
		// $attributes = $model->attributes();
		$user = $this->findOne($table, ['email' => $this->model->email]);
		
		if (!$user) {
			$this->model->addError('email', 'User does not exist.');
			Helper::pre($this->model->errors);
			return false;
		}

		if (!password_verify($this->model->password, $user->password)) {
			$this->model->addError('password', 'Password for the Username is incorrect.');
			$this->model->addError('email', 'Username for the Password is incorrect.');
			Helper::pre("Password failed");
			return false;
		}
		Helper::pre("Login Success!");
		return true;
	}
}

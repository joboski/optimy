<?php 

namespace optimy\app\repositories;

use optimy\app\connections\MyConnection;
use optimy\app\models\User;
use optimy\app\repositories\Repository;
use optimy\app\core\Application;
use optimy\app\core\Helper;

class UserRepository extends Repository
{
	public function __construct($model)
	{
		$this->pdo = MyConnection::getConnection()->pdo;
		$this->model = $model;
	}

	public function save($attributes, $values) {
		$table = $this->model->tableName();

		return $this->insert($table, $attributes, $values);
	}

	public function findUser($email)
	{
		$table = $this->model->tableName();
		$user = $this->findOne($table, ['email' => $email]);

		return $user;
	}

	public function findUserById($id)
	{
		return $this->findById($this->model->tableName(), $id);
	}
}

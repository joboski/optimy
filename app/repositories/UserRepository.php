<?php 

namespace optimy\app\repositories;

use optimy\app\connections\MyConnection;
use optimy\app\models\User;
use optimy\app\repositories\Repository;
use optimy\app\core\Application;
use optimy\app\core\Helper;
use PDO;

class UserRepository extends Repository
{
	public function __construct($model)
	{
		$this->pdo = MyConnection::getConnection()->pdo;
		$this->model = $model;
		$this->table = $this->model->tableName();
		$this->attributes = $this->model->attributes();
	}


	public function save($values) {

		return $this->insert($values);
	}

	public function getUserObject($email)
	{
		$stmt = $this->findOne(['email' => $email]);
		$user = $stmt->fetchObject(get_class($this->model));

		return $user;
	}

	public function findUserById($id)
	{
		$stmt = $this->findOne(['id' => $id]);
		$user = $stmt->fetch(PDO::FETCH_OBJ);

		return $user;
	}

	public function setModel($model)
	{
		$this->model = $model;
	}

	public function setAttributes($attributes)
	{
		$this->attributes = $attributes;
	}
}

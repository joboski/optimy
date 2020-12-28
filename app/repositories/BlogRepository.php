<?php 

namespace optimy\app\repositories;

use optimy\app\connections\MyConnection;
use optimy\app\models\Blog;
use optimy\app\repositories\Repository;

class BlogRepository extends Repository 
{
	public function __construct()
	{
		$this->pdo = MyConnection::getConnection()->pdo;
	}

	public function save($table, $attributes, $values) {
		return $this->insert($table, $attributes, $values);
	}

	public function getAll($table) {
		return $this->fetchAll($table);
	}

	public function getBlogByType($table, $type)
	{
		return $this->get($table, $type);
	}

	public function getAllByUser($table, $userId)
	{
		// TODO:
	}
}

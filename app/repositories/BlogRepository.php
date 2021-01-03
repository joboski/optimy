<?php 

namespace optimy\app\repositories;

use optimy\app\connections\MyConnection;
use optimy\app\models\Blog;
use optimy\app\repositories\Repository;
use PDO;

class BlogRepository extends Repository 
{
	protected $model;

	public function __construct(Blog $model)
	{
		$this->pdo = MyConnection::getConnection()->pdo;
		$this->model = $model;
		$this->table = $this->model->tableName();
		$this->attributes = $this->model->attributes();
	}

	public function save($values) {
		return $this->insert($values);
	}

	public function updateBlog($blogId, $userId, $newValues)
	{
		return $this->update($blogId, $userId, $newValues);
	}

	public function deleteBlog($blogId)
	{
		return $this->delete($blogId);
	}

	public function getBlog($id)
	{
		$stmt = $this->findOne($id);
		$blog = $stmt->fetch(PDO::FETCH_ASSOC);

		return $blog;
	}

	public function getBlogs($category = null) {
		return $this->findAll($category);
	}

	public function getBlogsByUser($userId)
	{
		return $this->findAll($userId);
	}

	public function setAttributes($attributes)
	{
		$this->attributes = $attributes;
	}
}

<?php 

namespace optimy\app\repositories;

use Repository;

use optimy\app\connections\MyConnection;
use optimy\app\models\Blog;
use optimy\app\repositories\Repository;

class BlogRepository extends Repository 
{
	private const TABLE_BLOGS = "blogs";

	protected $table;
	protected $attributes = [];

	public function __construct()
	{
		$this->model = new Blog();
		$this->pdo = MyConnection::getConnection()->pdo;
		$this->table = self::TABLE_BLOGS;
		$this->attributes = $this->attributes();
	}

	public function save() {
		return $this->add($this->table, $this->attributes);
	}

	public function attributes()
	{
		return [
			"user_id",
			"title",
			"content",
			"category",
			"filename"
		];
	}
}

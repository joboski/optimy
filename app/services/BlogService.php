<?php 

namespace optimy\app\services;

use optimy\app\core\Application;
use optimy\app\repositories\BlogRepository;
use optimy\app\core\Helper;
use optimy\app\models\Blog;

class BlogService
{
	private $repo;
	private $model;
	private $table;
	private $attributes;

	public function __construct(Blog $model)
	{
		$this->model = $model;
		$this->attributes = $this->model->attributes();
		$this->repo = new BlogRepository($this->model);		
	}

	public function create()
	{
		$values = $this->getValues();
		$values[0] = (int) Application::$app->user->id;

		return $this->repo->save($values);
	}

	public function update()
	{
		$values = $this->getValues();
		$blogId = $values[0];
		$userId = $values[1];
		unset($values[0]);
		unset($values[1]);
		unset($values[7]);

		unset($this->attributes[0]);
		unset($this->attributes[1]);
		unset($this->attributes[7]);

		return $this->repo->updateBlog($blogId, $userId, $this->attributes, $values);
	}

	public function delete($id)
	{
		return $this->repo->deleteBlog($id);
	}

	public function getAllBlogs()
	{
		return $this->repo->getBlogs();
	}

	public function getBlogById($id)
	{
		return $this->repo->getBlog($id);
	}

	public function getBlogsByType(array $type)
	{
		return $this->repo->getBlogs($type);
	}

	private function getValues() : array
	{
		$model = $this->model;
		
		$values = array_map(function($a) use ($model){
			return $model->{$a} ?? null;
		}, $this->attributes);
		
		return $values;
	}
}

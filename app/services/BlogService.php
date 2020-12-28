<?php 

namespace optimy\app\services;

use optimy\app\repositories\BlogRepository;
use optimy\app\core\Helper;
use optimy\app\models\Blog;

class BlogService
{
	private $repo;
	private $model;


	public function __construct(Blog $model)
	{
		$this->model = $model;
		$this->repo = new BlogRepository();
		
	}

	public function create()
	{
		$table = $this->model->tableName();
		$attributes = $this->model->attributes();
		
		$model = $this->model;
		$values = array_map(function($a) use ($model){
			return $model->{$a};
		}, $attributes);

		return $this->repo->save($table, $attributes, $values);
	}
}

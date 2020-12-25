<?php 

namespace optimy\app\services;

use optimy\app\models\Blog;

class BlogService {

	private $model;

	public function __construct()
	{
		$this->model = new Blog();
	}

	public function load($data)
	{
		$this->model->load($data);

		if ($this->model->validate() && $this->execute()) {
			return true;
		}

		return false;
	}

// Let the service call the model and not the controller
	public function model()
	{
		return $this->model;
	}

	private function execute()
	{

	}
}
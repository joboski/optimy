<?php 

namespace optimy\app\controllers;


use optimy\app\controllers\Controller;
use optimy\app\services\BlogService;
use optimy\app\core\Request;


class BlogController extends Controller
{
	private $service;

	public function __construct()
	{
		$this->service = new BlogService();
	}

	public function create(Request $request)
	{
		if ($request->isPost()) {
			if ($this->service->load($request->body())) {
				return "success";
			}
			return $this->view("blog", ["model" => $this->service->model()]);
		}
		
		$this->setLayout('blog');

		return $this->view("blog", ["model" => $this->service->model()]);
	}

	public function get(Request $request, Response $response)
	{
		if ($request->isPost()) {
			if ($this->service->load($request->body())) {
				return "success";
			}
			return $this->view("blog", ["model" => $this->service->model()]);
		}
		
		$this->setLayout('blog');

		return $this->view("blog", ["model" => $this->service->model()]);
	}
}

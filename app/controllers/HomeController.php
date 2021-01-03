<?php 

namespace optimy\app\controllers;

use optimy\app\controllers\Controller;
use optimy\app\core\Application;
use optimy\app\core\Helper;
use optimy\app\core\Response;
use optimy\app\core\Request;
use optimy\app\models\Blog;
use optimy\app\services\BlogService;

class HomeController extends Controller
{
	private const FOOD_TYPE = "foods";
	private const SPORT_TYPE = "sports";
	private const PLACE_TYPE = "places";
	private const PEOPLE_TYPE = "people";
	private const CATEGORY_TYPE = "category";

	private $model;
	private $table;
	private $service;

	public function __construct()
	{
		$this->model = new Blog();
		$this->service = new BlogService($this->model);
	}
	
	public function getAll(Request $request)
	{
		$this->setLayout('main');	
		$blogs = $this->service->getAllBlogs();
		
		return $this->view("home", ["blogs" => $blogs]);
	}

	public function getFoods()
	{
		$this->setLayout('main');
		$blogs = $this->service->getBlogsByType([self::CATEGORY_TYPE => self::FOOD_TYPE]);
		
		return $this->view("home", ["blogs" => $blogs]);
	}

	public function getPlaces()
	{
		$this->setLayout('main');
		$blogs = $this->service->getBlogsByType([self::CATEGORY_TYPE => self::PLACE_TYPE]);
		
		return $this->view("home", ["blogs" => $blogs]);
	}

	public function getSports()
	{
		$this->setLayout('main');
		$blogs = $this->service->getBlogsByType([self::CATEGORY_TYPE => self::SPORT_TYPE]);
		
		return $this->view("home", ["blogs" => $blogs]);
	}

	public function getPeople()
	{
		$this->setLayout('main');
		$blogs = $this->service->getBlogsByType([self::CATEGORY_TYPE => self::PEOPLE_TYPE]);
		
		return $this->view("home", ["blogs" => $blogs]);
	}
}

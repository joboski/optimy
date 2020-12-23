<?php 

namespace optimy\app\controllers;


use optimy\app\controllers\Controller;
use optimy\app\core\Helper;
use optimy\app\core\Request;


class BlogController extends Controller
{
	public function get()
	{
		$params = [
			"name" => "Hi Jojo"
		];

		return $this->view('blog', $params);
	}


	public function post(Request $request)
	{
		$this->view('blog', $request->method());
	}
}

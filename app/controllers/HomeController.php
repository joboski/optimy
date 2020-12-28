<?php 

namespace optimy\app\controllers;

use optimy\app\controllers\Controller;
use optimy\app\core\Request;


class HomeController extends Controller
{
	public function get(Request $request)
	{
		$params = [
			"name" => "Hi Jojo"
		];

		return $this->view('home', $params);
	}
}

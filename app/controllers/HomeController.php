<?php 

namespace optimy\app\controllers;

use optimy\app\controllers\Controller;


class HomeController extends Controller
{
	public function get()
	{
		$params = [
			"name" => "Hi Jojo"
		];

		return $this->view('home', $params);
	}
}
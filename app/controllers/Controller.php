<?php 

namespace optimy\app\controllers;


use optimy\app\core\Renderer;

abstract class Controller
{

	protected $render;
	public $layout = "main"; // default layout

	public function view($view, $params){
		
		$this->render = new Renderer();
		return $this->render->view($view, $params);
	}

	public function setLayout($layout) {
		$this->layout = $layout;
	}
}

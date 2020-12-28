<?php 

namespace optimy\app\core;

use optimy\app\core\Config;
use optimy\app\core\Renderer;
use optimy\app\core\Request;
use optimy\app\core\Response;
use optimy\app\core\Application;


// define("HTTP_RESPONSE_404" ,"404");

class Router
{
	private const HTTP_RESPONSE_404 = 404;
	protected $routes = [];
	protected $request;
	protected $response;
	protected $app;

	public function __construct(Request $request, Response $response)
	{
		$this->request = $request;
		$this->response = $response;
	}

	public function get($path, $callback)
	{
		$this->routes['get'][$path] = $callback;
	}

	public function post($path, $callback)
	{
		$this->routes['post'][$path] = $callback;
	}
	
	public function resolve()
	{
		$path = $this->request->path();
		$method = $this->request->method(); // get, post, update, delete, patch
		
		$render = new Renderer();
		
		$callback = $this->routes[$method][$path] ?? false;

		if ($callback === false) {
			
			$this->response->setStatusCode(HTTP_RESPONSE_404);

			return $render->content(HTTP_RESPONSE_404, null);
		}

		if (is_string($callback)) {
			return $render->view($view, $params);
		}

		if (is_array($callback)) {
			// calling the instance of the class and pass it back as param[0]
			$controller = new $callback[0];

			Application::$app->setController($controller);
			$callback[0] = $controller;		
		}
		
		return call_user_func($callback, $this->request, $this->response);
	}
}

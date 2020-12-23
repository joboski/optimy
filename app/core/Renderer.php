<?php

namespace optimy\app\core;

use optimy\app\core\Application;

class Renderer
{
	public function view($view, $params = [])
	{
		$layout = $this->layout();
		$viewContent = $this->content($view, $params);

		return str_replace("{{content}}", $viewContent, $layout);
	}

	public function layout()
	{
		$layout = Application::$app->getController()->layout;
		ob_start(); // start caching the output buffering

		include_once Application::$ROOT_PATH . "/app/views/layout/$layout.php"; // the actual output

		return ob_get_clean(); // return the output and clean the cache
	}

	public function content($view, $params = [])
	{
		Helper::pre($params);
		if (!is_null($params)) {
			// Helper::pre($params);
			foreach ($params as $key => $value) {
				// turn the key which is name into a variable $name
				$$key = $value;
			}
		}
		
		ob_start(); // start caching the output

		include_once Application::$ROOT_PATH . "/app/views/$view.php"; // the actual view output

		return ob_get_clean(); // return the output and clean the cache
	}
}

<?php 

namespace optimy\app\controllers;


use optimy\app\controllers\Controller;
use optimy\app\services\BlogService;
use optimy\app\models\Blog;
use optimy\app\core\Application;
use optimy\app\core\Request;
use optimy\app\core\Response;

use optimy\app\core\Helper;
use \RuntimeException;


class BlogController extends Controller
{
	private $service;
	private $model;
	private $uploadDir;
	private $uploadFile;

	public function __construct()
	{
		// Helper::pre("inside BLOG Controller");
		$this->model = new Blog();
		$this->service = new BlogService($this->model);
		$this->uploadDir = Application::$ROOT_PATH . "/app/assets/uploads/";
	}

	public function create(Request $request, Response $response)
	{
		$this->setLayout('main');

		if (!$this->isAllowed()) {
			Application::$app->session->setFlash("fail" , "Access denied!");
			$response->redirect("/"); // home
			exit;
		}
		
		if ($request->isPost()) {
			$this->model = $this->loadData($request->body());
			$this->model->userid = Application::$app->user->id;

			if ($this->model->validate() && $this->service->create()) {

				move_uploaded_file($_FILES["filename"]["tmp_name"], $this->uploadFile);
				Application::$app->session->setFlash("success" , "Blog has been created");
				$response->redirect("/"); // home
				exit;
			}
			Application::$app->session->setFlash("fail" , "Failed to create blog.");
			return $this->view("blog", ["model" => $this->model]);
		}
		return $this->view("blog", ["model" => $this->model]);
	}

	public function update(Request $request, Response $response)
	{
		if (!$this->isAllowed()) {
			Application::$app->session->setFlash("fail" , "Access denied!");
			$response->redirect("/"); // home
			exit;
		}

		$id = $request->body()["id"] ?? null;
		$blog = $this->service->getBlogById(["id" => $id]);
		// pre-populating the form
		$this->model->load($blog);

		if ($request->isPost()) {
			$this->model = $this->loadData($request->body());
			if ($this->model->validate() && $this->service->update()) {

				move_uploaded_file($_FILES["filename"]["tmp_name"], $this->uploadFile);
				Application::$app->session->setFlash("success" , "Blog has been updated");
				$response->redirect("/"); // home
				exit;
			}
			Application::$app->session->setFlash("fail" , "Failed to create blog.");
			return $this->view("blog", ["model" => $this->model]);
		}
		
		return $this->view("blog", ["model" => $this->model]);
	}

	public function delete(Request $request, Response $response)
	{
		if (!$this->isAllowed()) {
			Application::$app->session->setFlash("fail" , "Access denied!");
			$response->redirect("/"); // home
			exit;
		}

		if ($this->service->delete($request->body()["id"])) {
			Application::$app->session->setFlash("success" , "Blog has been deleted");
			$response->redirect("/"); // home
			exit;
		}
		Application::$app->session->setFlash("fail" , "Failed to delete blog.");
		$response->redirect("/");
	}

	private function loadData($data)
	{
		if (!empty($_FILES["filename"])) {
			$this->uploadFile = $this->uploadDir . $_FILES["filename"]["name"];
			// Checking the MIME type
		    $extension = in_array($_FILES["filename"]["type"], [
		    	"image/jpeg",
		    	"image/png",
		    	"image/gif",
		    	"image/tiff"
		    ]);

			try {

				if ($_FILES["filename"]["error"] != 0 && isset($_FILES["filename"]["error"])) {
					throw new RuntimeException("Invalid parameters");
				}
				
			    if ($_FILES["filename"]["size"] > 1000000) {
			        throw new RuntimeException('Exceeded filesize limit.');
			    }

			    if (!$extension) {
			    	throw new RuntimeException('Invalid file format.');
			    }

			} catch (RuntimeException $e) {
				echo $e->getMessage();
			}
		}

		$this->model->load($data);
		$this->model->filename = $_FILES["filename"]["name"] ?? null;

		return $this->model;
	}

	private function isAllowed()
	{
		return !empty(Application::$app->user);
	}
}

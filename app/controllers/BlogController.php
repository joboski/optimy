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

	public function __construct()
	{
		Helper::pre("inside BLOG Controller");
		$this->model = new Blog();
		$this->service = new BlogService($this->model);

	}

	public function create(Request $request, Response $response)
	{
		$this->setLayout('blog');
		
		if ($request->isPost()) {
			// for uploaded file
			if ($_FILES) {
				$uploadDir = Application::$ROOT_PATH . "/app/assets/uploads/";
				$uploadFile = $uploadDir . $_FILES["filename"]["name"];
				// Helper::pre($uploadFile);
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
					// Checking filesize here.
				    if ($_FILES["filename"]["size"] > 100000) {
				        throw new RuntimeException('Exceeded filesize limit.');
				    }

				    if (!$extension) {
				    	throw new RuntimeException('Invalid file format.');
				    }

				} catch (RuntimeException $e) {
					echo $e->getMessage();
				}
			}

			// Helper::pre(Application::$app->user->id);
			$this->model->load($request->body());
			$this->model->userid = Application::$app->user->id;
			$this->model->filename = $_FILES["filename"]["name"];
			
			// Helper::pre($request->body());
			// Helper::pre($this->model->validate());

			if ($this->model->validate() && $this->service->create()) {

				move_uploaded_file($_FILES["filename"]["tmp_name"], $uploadFile);
				Application::$app->session->setFlash("success" , "Blog has been created");
				$response->redirect("/"); // home
				exit;
			}
			Application::$app->session->setFlash("fail" , "Failed to create blog.");
			// Display the blog form
			return $this->view("blog", ["model" => $this->model]);
		}

		// Display the blog form 
		return $this->view("blog", ["model" => $this->model]);
	}
}

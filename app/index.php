<?php 

namespace optimy\app;

require_once "../vendor/autoload.php";
require_once "init.php";

use optimy\app\core\Application;
use optimy\app\controllers\BlogController;
use optimy\app\controllers\HomeController;
use optimy\app\controllers\AuthController;

$app = Application::instance();
$app->setRootPath(dirname(__DIR__));


$app->router->get("/user", function(){
	return "User Directory";
});

$app->router->get("/user/create", function(){
	return "Creating User";
});

// Home routes
$app->router->get("/", [HomeController::class, "get"]);

// Blog routes
$app->router->get("/blog", [BlogController::class, "get"]);
$app->router->post("/blog", [BlogController::class, "post"]);

// User routes
$app->router->get("/login", [AuthController::class, "login"]);
$app->router->post("/login", [AuthController::class, "login"]);
$app->router->get("/register", [AuthController::class, "register"]);
$app->router->post("/register", [AuthController::class, "register"]);


$app->run();

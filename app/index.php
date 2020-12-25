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

// Home routes
$app->router->get("/", [HomeController::class, "get"]);

// Blog routes
$app->router->get("/blog", [BlogController::class, "create"]);
$app->router->post("/blog", [BlogController::class, "create"]);

// User routes
$app->router->get("/login", [AuthController::class, "login"]);
$app->router->post("/login", [AuthController::class, "login"]);
$app->router->get("/register", [AuthController::class, "register"]);
$app->router->post("/register", [AuthController::class, "register"]);


$app->run();

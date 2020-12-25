<?php 

namespace optimy\app;

require_once "../vendor/autoload.php";
require_once "init.php";

use optimy\app\core\Application;

$app = Application::instance();

$app->db->applyMigrations();

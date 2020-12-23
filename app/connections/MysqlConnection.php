<?php

namespace optimy\app\connections;

use \optimy\app\config\Config as Config;
use PDO;

class MysqlConnection
{
	private static $_instance = null;
	// Essential var for query result
	private $_pdo,
			$_query,
			$_error = false,
			$_results,
			$_count = 0;

	private function __construct()
	{
		try {

			$config = Config::get("mysql");

			$dns = "mysql:host=".$config['host'].";dbname=".$config['dbname'];
			$user = $config['user'];
			$pass = $config['pass'];
			$opt = [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
				PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        	];

			$this->_pdo = new PDO($dns, $user, $pass);

		} catch(PDOException $err) {
			die(json_encode([
				"message" => "Failed to connect to database",
				"error" => $err->getMessage()]
			));
		}
	}

	public static function instance()
	{
		if (!isset(self::$_instance)){
			self::$_instance = new MysqlConnection();
		}
	}

	public function close()
	{
		return $this->_pdo = null;
	}
}

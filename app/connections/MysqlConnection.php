<?php

namespace optimy\app\connections;

use optimy\app\core\Config;
use optimy\app\core\Helper;
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

			$this->_pdo = new PDO($dns, $user, $pass, $opt);

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
		return self::$_instance;
	}

	public function close()
	{
		return $this->_pdo = null;
	}

	public function applyMigrations()
	{
		$dir = __DIR__."\..\migrations";
		$this->createMigrationsTable();
		
		$migratedTables = $this->getMigratedTables();
		
		$files = scandir($dir);
		
		$pendingMigrations = array_diff($files, $migratedTables);
		
		foreach ($pendingMigrations as $migration) {
			if ($migration === "." || $migration === "..") {
				// do nothing
				continue;
			}

			require_once($dir . "\\" . $migration);

			$className = pathinfo($migration, PATHINFO_FILENAME);
			// Helper::pre($className);
			$object = new $className;

			$object->up();
		}
	}

	private function createMigrationsTable()
	{
		$this->_pdo->exec(
			"CREATE TABLE IF NOT EXISTS 
				migrations (
					id INT AUTO_INCREMENT PRIMARY KEY, 
					tablename VARCHAR(255), 
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
				)
				ENGINE=INNODB;"
			);
	}

	private function getMigratedTables()
	{
		$stmt = $this->_pdo->prepare("SELECT tablename FROM migrations");
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}

}

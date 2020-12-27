<?php

namespace optimy\app\connections;

use optimy\app\core\Config;
use optimy\app\core\Helper;
use PDO;

class MysqlConnection
{
	private static $instance = null;
	// Essential var for query result
	public $pdo;

	private	$query,
			$error = false,
			$results,
			$count = 0;

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

			$this->pdo = new PDO($dns, $user, $pass, $opt);

		} catch(PDOException $err) {
			die(json_encode([
				"message" => "Failed to connect to database",
				"error" => $err->getMessage()]
			));
		}
	}

	public static function instance()
	{
		if (!isset(self::$instance)){
			self::$instance = new MysqlConnection();
		}
		return self::$instance;
	}

	public function close()
	{
		return $this->pdo = null;
	}

	public function migrate()
	{
		$newMigrations = [];

		$dir = __DIR__."\..\migrations";
		$this->createMigrationsTable();
		
		$migrations = $this->getMigrations();

		$migrations = array_map(function($v) {
			return $v = $v.".php";
		}, $migrations);
		// Helper::pre($migrations);
		$files = scandir($dir);
		// Helper::pre($files);
		
		$pendingMigrations = array_diff($files, $migrations);
		
		foreach ($pendingMigrations as $migration) {
			if ($migration === "." || $migration === "..") {
				// do nothing
				continue;
			}

			require_once($dir . "\\" . $migration);

			$className = pathinfo($migration, PATHINFO_FILENAME);
			// Helper::pre($className);
			$object = new $className;
			
			$this->log("Applying migration $migration");
			$object->up();
			$this->log("Done migrating $migration");
			
			$newMigrations[] = $migration;
		}

		if (!empty($newMigrations)) {
			$this->save($newMigrations);
		} else {
			$this->log("All migrations are applied");
		}
	}

	private function createMigrationsTable()
	{
		$this->pdo->exec(
			"CREATE TABLE IF NOT EXISTS 
				migrations (
					id INT AUTO_INCREMENT PRIMARY KEY, 
					migration VARCHAR(255), 
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
				)
				ENGINE=INNODB;"
			);
	}

	private function getMigrations()
	{
		$stmt = $this->pdo->prepare("SELECT migration FROM migrations");
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}

	private function save($migrations)
	{
		$values = implode(",", array_map(
			function($m){
				$m = str_replace(".php", "", $m);
				return "('$m')";
			}, $migrations));
		
		$stmt = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $values");

		$stmt->execute();
	}

	private function log($message)
	{
		echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
	}

}

<?php 

use optimy\app\core\Application;

class M01_users_table
{
	public function up()
	{
		$app = Application::instance();
		$stmt = "CREATE TABLE IF NOT EXISTS users (
					id INT AUTO_INCREMENT PRIMARY KEY, 
					email VARCHAR(255) NOT NULL,
					firstname VARCHAR(255) NOT NULL,
					lastname VARCHAR(255) NOT NULL,
					password VARCHAR(255) NOT NULL,
					status TINYINT NOT NULL, 
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
				)
				ENGINE=INNODB;";

		$app->db->pdo->exec($stmt);

		echo "Users table has been created" . PHP_EOL;
	}

	public function down()
	{
		$app = Application::instance();
		$stmt = "DROP TABLE users;";
		$app->db->pdo->exec($stmt);
		echo "Table users is offline" . PHP_EOL;
	}
}

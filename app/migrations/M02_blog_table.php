<?php 

use optimy\app\core\Application;

class M02_blog_table
{

	public function up()
	{
		$app = Application::instance();
		$stmt = "CREATE TABLE IF NOT EXISTS blogs (
					id INT(6) AUTO_INCREMENT PRIMARY KEY,
					user_id INT,
					title TEXT NOT NULL,
					content TEXT NULL,
					filename TEXT NULL,
					category VARCHAR(50) NOT NULL,
					created_at TIMESTAMP NOT NULL
				)
				ENGINE=INNODB;";

		$app->db->pdo->exec($stmt);
		echo "Blog table has been created " . PHP_EOL;
	}

	public function down()
	{
		$app = Application::instance();
		$stmt = "DROP TABLE blogs;";
		$app->db->pdo->exec($stmt);
		echo "Table users is offline" . PHP_EOL;
	}
}

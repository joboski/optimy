<?php 

use optimy\app\core\Application;

class M03_add_blog_column_updated_at
{

	public function up()
	{
		$app = Application::instance();
		$stmt = "ALTER TABLE `blogs` ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`";

		$app->db->pdo->exec($stmt);
		echo "Blog table has been updated " . PHP_EOL;
	}

	public function down()
	{
		$app = Application::instance();
		$stmt = "DROP TABLE blogs;";
		$app->db->pdo->exec($stmt);
		echo "Table blog is offline" . PHP_EOL;
	}
}

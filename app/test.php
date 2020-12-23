<?php 

namespace optimy\app;

use optimy\app\connections\MysqlConnection as MysqlConnection;
use optimy\app\connections\MyConnection;


// require_once("connections/MysqlConnection.php");
// // require_once("repositories/BlogRepository.php");

// // $blog = new BlogRepository();
// // $sql = "SELECT * FROM blog";
// // $params = ["food"];
// // $result = $database->query($sql, []);

// // print_r($result);
$conn = new MyConnection();
$conn->getConnection();

print_r($conn);
// print_r($connection->getConnection());
echo "Success!";


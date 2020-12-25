<?php 

namespace optimy\app\connections;

use optimy\app\connections\MysqlConnection;
use optimy\app\core\Helper;
use optimy\app\core\Config;

class MyConnection 
{
	public static function getConnection()
	{
		$provider = Config::get("provider");
		
		if ($provider === "mysql") {
			$conn = MysqlConnection::instance();
		} elseif ($provider === "mssql") {
			// TODO: if need to change to mssql provider
		} else {
			// TODO: throw an error if provider is not existing
		}
		
		return $conn;
	}
}

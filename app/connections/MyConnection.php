<?php 

namespace optimy\app\connections;

use optimy\app\core\Config;

class MyConnection 
{
	private $_conn;
	private $_provider;

	public function __constructor()
	{	
		$this->_provider = Config::get("provider");
	}

	public function getConnection()
	{
		if ($this->_provider === "mysql") {
				$this->_conn = MysqlConnection::instance();
		} elseif ($this->_provider === "mssql") {
			// TODO: if need to change to mssql provider
		} else {
			// TODO: throw an error if provider is not existing
		}
		
		return $this->_conn;
	}

}

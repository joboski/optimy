<?php 

namespace optimy\app\repositories;

use Repository;
use optimy\app\models\Blog;
use optimy\app\contracts\BaseContract;
use optimy\app\connections\MyConnection as Connection;

class BlogRepository extends Repository implements BaseContract
{
	private $_model;
	private $_conn;

	public function __construct()
	{
		$this->_model = new Blog();
		$this->_conn = new Connection();
		$this->_pdo = $this->_conn->getConnection();
	}

	public function get(string $table, string $whereClause)
	{
		$this->action->("SELECT * ", $table, $whereClause);
	}

	public function add(string $table, array $fields)
	{

	}

	public function update(string $table, array $whereClause, array $fields)
	{

	}

	public function delete(string $table, string $whereClause)
	{

	}


}

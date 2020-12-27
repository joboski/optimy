<?php 

namespace optimy\app\repositories;

use optimy\app\contracts\BaseContract;
use optimy\app\core\Helper;
use PDO;


abstract class Repository
{
	protected $error;
	protected $query;
	protected $pdo;
	protected $results;
	protected $count;
	protected $model;

	private function prepare($sql)
	{
		return $this->pdo->prepare($sql);
	}

	protected function get(string $table, string $whereClause)
	{
		// $this->action("SELECT * ", $table, $whereClause);
	}

	protected function find(string $table, array $whereClause)
	{
		$attributes = array_keys($whereClause);

		// SELECT * FROM $table WHERE email = :email AND firstname = :firstname....
		$where = implode("AND ",array_map(function($a){
			return "$a = :$a";
		}, $attributes));

		$stmt = self::prepare("SELECT * FROM $table WHERE $where");

		foreach ($whereClause as $key => $value) {
			// Helper::pre($value));
			$stmt->bindValue(":$key", $value);
		}

		$record = $stmt->execute();
		// Helper::dump($stmt->execute());
		if ($record) {
			$this->results = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $this->results;	
		}
		// Helper::dump($this->results);
		return false;
	}

	protected function insert($table, $attributes, $values)
	{
		$params = array_map(function($a){
			return $a = ":$a";
		}, $attributes);

		$stmt = self::prepare("INSERT INTO  $table (" . implode(',', $attributes) . ") 
			VALUES(". implode(',', $params) .")");

		array_map(function($a, $v) use ($stmt) {
			$stmt->bindValue(":$a", $v);
		}, $attributes, $values);
		
		$stmt->execute();

		return true;	
	}

	protected function update(string $table, array $whereClause)
	{
		$this->action("UPDATE ", $table, $whereClause);
	}

	protected function delete(string $table, string $whereClause)
	{
		$this->action("DELETE ", $table, $whereClause);
	}

	protected function findOne(string $table, array $whereClause)
	{
		$results = $this->find($table, $whereClause);

		$this->results = $results[0] ?? false;

		return $this->results;
	}
}

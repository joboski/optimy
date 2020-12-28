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

	private function prepareStatement(string $table, array $whereClause) {
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

		return $stmt;
	}

	protected function get(string $table, string $category)
	{
		try
		{
			$stmt = self::prepare("SELECT * FROM $table WHERE category = :category");
			$stmt->bindValue(":category", $category);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed: ' . $e->getMessage();
    		exit;
		}
	}

	protected function fetchAll(string $table)
	{
		try
		{
			$stmt = self::prepare("SELECT * FROM $table");
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed: ' . $e->getMessage();
    		exit;
		}
	}

	protected function find(string $table, array $whereClause)
	{
		try
		{
			$stmt = self::prepareStatement($table, $whereClause);
			$stmt->execute();
			$this->results = $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed: ' . $e->getMessage();
    		exit;
		}

		if ($this->results) {
			return $this->results;	
		}

		return false;
	}

	protected function findOne(string $table, array $whereClause)
	{
		// Helper::pre("Inside find one");
		try
		{
			$stmt = $this->prepareStatement($table, $whereClause);
			$stmt->execute();
			$this->results = $stmt->fetchObject(get_class($this->model));

			return $this->results;
		}
		catch (PDOException $e)
		{
			echo 'Connection failed: ' . $e->getMessage();
    		exit;
		}
		
		return false;
	}

	protected function insert($table, $attributes, $values)
	{
		$params = array_map(function($a){
			return $a = ":$a";
		}, $attributes);

		try 
		{
			$stmt = self::prepare("INSERT INTO  $table (" . implode(',', $attributes) . ") 
			VALUES(". implode(',', $params) .")");

			array_map(function($a, $v) use ($stmt) {
				$stmt->bindValue(":$a", $v);
			}, $attributes, $values);
		
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo 'Connection failed: ' . $e->getMessage();
    		exit;
		}
		return true;	
	}

	protected function update(string $table, array $whereClause)
	{
		// TODO
	}

	protected function delete(string $table, string $whereClause)
	{
		// TODO
	}

	public function findById($table, $id)
	{
		try
		{
			$stmt = self::prepare("SELECT * FROM $table WHERE id = $id");
			$stmt->execute();
			$this->results = $stmt->fetchObject(get_class($this->model));
			
			return $this->results;
		}
		catch (PDOException $e)
		{
			echo 'Connection failed: ' . $e->getMessage();
    		exit;
		}
		
		return false;
	}

}

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
	protected $table;
	protected $attributes;

	private const ACTION_FIND = "find",
				  ACTION_INSERT = "insert",
				  ACTION_UPDATE = "update",
				  ACTION_DELETE = "delete";	 

	/** 
		@actions 
		findAll: SELECT * FROM $table WHERE attr1 =:attr1 AND attr2 =:attr2 AND attr3 =:attr3

		find: SELECT * FROM $table WHERE attr =:attr

		insert: INSERT INTO $table (attr1, attr2, attr3) VALUES (:attr1, :attr2, :attr3);

		update: UPDATE $table SET attr1=:attr1, attr2=:attr2, attr3=:attr3 WHERE id=:id;

		delete: DELETE FROM $table WHERE id =:id


	*/
	private function prepareQuery($sql, $whereClause)
	{
		if (empty($whereClause)) {
			$this->query = $sql;
			$this->query = $this->pdo->prepare($this->query);
			return;
		}
		
		$where = implode(" AND ",array_map(function($a){
			return "$a = :$a";
		}, array_keys($whereClause)));
		
		$this->query = "{$sql} WHERE {$where}";
		$this->query = $this->pdo->prepare($this->query);
		foreach ($whereClause as $key => $value) {
			$this->query->bindValue(":$key", $value);
		}	
	}

	protected function findAll($attributes)
	{
		$sql = "SELECT * FROM $this->table";

		try 
		{
			$this->prepareQuery($sql, $attributes);
			$this->query->execute();
			$this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed: ' . $e->getMessage();
    		exit;
		}

		return $this->results;
	}

	protected function findOne($whereClause)
	{
		$sql = "SELECT * FROM $this->table";
		try 
		{
			$this->prepareQuery($sql, $whereClause);
			$this->query->execute();
		}
		catch (PDOException $e)
		{
			echo 'Connection failed: ' . $e->getMessage();
    		exit;
		}

		return $this->query;
	}

	protected function insert(array $values)
	{
		// SQL: INSERT INTO <tablename> (attr1, attr2, attr3) VALUES (:attr1, :attr2, :attr3);
		$holders = array_map(function($a){
			return $a = ":$a";
		}, $this->attributes);

		try 
		{
			$stmt = $this->pdo->prepare("INSERT INTO $this->table (" . implode(',', $this->attributes) . ") 
			VALUES(". implode(',', $holders) .")");

			array_map(function($a, $v) use ($stmt) {
				$stmt->bindValue(":$a", $v);
			}, $this->attributes, $values);
		
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo 'Connection failed: ' . $e->getMessage();
    		exit;
		}
		return true;	
	}

	protected function update($blogId, $userId, $attributes, $values)
	{
		// SQL: UPDATE <tablename> SET attr1=:attr1, attr2=:attr2, attr3=:attr3 WHERE id=:id;
		$params = array_map(function($a){
			return "$a = :$a";
		}, $attributes);

		try 
		{
			$stmt = $this->pdo->prepare("UPDATE $this->table SET " . implode(', ', $params) . " WHERE id =:id AND userid =:userid");

			array_map(function($a, $v) use ($stmt) {
				$stmt->bindValue(":$a", $v);
			}, $attributes, $values);

			$stmt->bindValue(":id", $blogId);
			$stmt->bindValue(":userid", $userId);
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo 'Connection failed: ' . $e->getMessage();
    		exit;
		}
		return true;
	}

	protected function delete(int $id)
	{
		//SQL "DELETE FROM <tablename> WHERE id = :id LIMIT 1"
		try
		{
			$stmt = $this->pdo->prepare("DELETE FROM $this->table WHERE id = :id LIMIT 1");
			$stmt->bindValue(":id", $id);
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo 'Connection failed: ' . $e->getMessage();
    		exit;
		}
		return true;
	}
}

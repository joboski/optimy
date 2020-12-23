<?php 

namespace optimy\app\repositories;


class Repository
{
	protected $_error;
	protected $_query;
	protected $_pdo;
	protected $_results;
	protected $_count;


	protected function action($action,$table,$where = array()){
		if(count($where) === 3){
			$operators = array('=','>','<','>=','<=');
			$field = $where[0];
			$operator = $where[1];
			$value = $where[2];

			if(in_array($operator,$operators)){
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				if(!$this->query($sql,array($value))->error()){
					return $this;
				}
			}
		}
		return false;
	}

	protected function query($sql,$params = array()){

		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)){
			if(count($params)){
				$x=1;
				foreach ($params as $param) {
					$this->_query->bindValue($x,$param);
					$x++;
				}
			}
		}

		if($this->_query->execute()){
			$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ); //PDO::FETCH_OBJ
			$this->_count = $this->_query->rowCount();
		}else{
			$this->_error = true;
		}

		return $this;
	}
}

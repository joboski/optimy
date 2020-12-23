<?php 

namespace optimy\app\contracts;

interface BaseContract {

	// public function query(string $statement, array $params);

	// public function action(string $action, string $table, array $params);

	public function get(string $table, string $whereClause);

	public function add(string $table, array $fields);

	public function update(string $table, array $whereClause, array $fields);

	public function delete(string $table, string $whereClause);

	// public function results();

	// public function first();

	// public function error();

	// public function count();
}

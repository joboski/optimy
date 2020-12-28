<?php 

namespace optimy\app\models;

use optimy\app\models\BaseModel;
use optimy\app\connections\MyConnection;
use optimy\app\core\Helper;


abstract class Model_BKUP extends Model {

	// @returns type sting tablename 
	abstract public static function tableName();
	// @returns array of attributes 1 is 1 from the actual table 
	abstract public function attributes();

	abstract public function labels();

	public function save()
	{
		$table = $this->tableName();
		$attributes = $this->attributes();

		// should be in the format of :firstname, :lastname ...
		$params = array_map(function($a){
			return $a = ":$a";
		}, $attributes);

		$stmt = self::prepare("INSERT INTO $table (" . implode(',', $attributes) . ") 
			VALUES(". implode(',', $params) .")");

		foreach ($attributes as $attribute) {
			$stmt->bindValue(":$attribute", $this->{$attribute});
			Helper::pre($this->{$attribute});
		}

		// Helper::dump($stmt, $params, $this->{$attribute});
	}

	public function prepare($sql)
	{
		return MyConnection::getConnection()->pdo->prepare($sql);
	}
}
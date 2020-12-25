<?php 

namespace optimy\app\models;

use optimy\app\models\Model;
use optimy\app\core\Helper;

class User extends Model
{	
	public $email = "";
	public $password = "";
	public $confirmPassword = "";
	public $firstname = "";
	public $lastname = "";
	
	public function rules() {
		
		return [
			"firstname" => [
				"type" => "string",
				"required" => true,
				"min" => 2,
				"max" => 80
			],
			"lastname" => [
				"type" => "string",
				"required" => true,
				"min" => 2,
				"max" => 80
			],
			"email" => [
				"type" => "email",
				"required" => true,
			],
			"password" => [
				"type" => "password",
				"required" => true,
				"min" => 8,
				"max" => 32
			],
			"confirmPassword" => [
				"type" => "match",
				"required" => true,
			]
		];
	}
}

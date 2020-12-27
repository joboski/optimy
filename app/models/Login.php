<?php 

namespace optimy\app\models;

use optimy\app\models\Model;

class Login extends Model
{
	public $email = "";
	public $password = "";
	protected $table = "";

	public function rules() {
		return [
			"email" => [
				"required" => true,
				"type" => "email"
			],
			"password" => [
				"required" => true,
				"type" => "unknown"
			],
		];
	}

	public function labels()
	{
		return [
			"email" => "Username or Email",
			"password" => "Password",
		];
	}

	public function attributes()
	{
		return ["email", "password"];
	}

	public function tableName()
	{
		$this->table = "users";
		return $this->table;
	}
}

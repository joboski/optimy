<?php 

namespace optimy\app\models;

use optimy\app\models\Model;

class Login extends Model
{
	private const TABLE_NAME = "users";
	private const PRIMARY_KEY = "id";

	public $email = "";
	public $password = "";

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
		return self::TABLE_NAME;
	}

	public function primaryKey()
	{
		return self::PRIMARY_KEY;
	}
}

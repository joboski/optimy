<?php 

namespace optimy\app\models;

use optimy\app\models\Model;

class User extends Model
{	
	private const TABLE_USERS = "users";
	private const STATUS_INACTIVE = 0;
	private const STATUS_ACTIVE = 1;
	private const STATUS_DELETED = 2;

	public $email = "";
	public $password = "";
	public $confirmPassword = "";
	public $firstname = "";
	public $lastname = "";
	public $status = self::STATUS_INACTIVE;

	public function activeStatus()
	{
		return self::STATUS_ACTIVE;
	}

	public function defaultStatus()
	{
		return self::STATUS_INACTIVE;
	}

	public function deletedStatus()
	{
		return self::STATUS_DELETED;
	}

	public function tableName()
	{
		return self::TABLE_USERS;
	}

	public function attributes()
	{
		return ["firstname", "lastname", "email", "password", "status"];
	}

	public function labels()
	{
		return [
			"firstname" => "First Name",
			"lastname" => "Last Name",
			"email" => "Email",
			"password" => "Password",
			"confirmPassword" => "Repeat Password",
		];
	}
	
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
				"unique" => true,
				"class" => self::class
			],
			"password" => [
				"type" => "password",
				"required" => true,
				"min" => 8,
				"max" => 32
			],
			"confirmPassword" => [
				"type" => "match",
				"match" => "password",
				"required" => true,
			]
		];
	}
}

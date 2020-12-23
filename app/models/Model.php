<?php 

namespace optimy\app\models;

use optimy\app\core\Helper;

abstract class Model
{
	public const RULE_REQUIRED = 'required';
	public const RULE_EMAIL = 'email';
	public const RULE_MATCH = 'match';
	public const RULE_MIN = 'min';
	public const RULE_MAX = 'max';
	
	public $errors = [];

	protected $messages = [
		self::RULE_REQUIRED => 'This field is required',
		self::RULE_EMAIL => 'This field must be a valid email address',
		self::RULE_MIN => 'Minimum length of this character must be {min}',
		self::RULE_MAX => 'Maximum length of this character must be {max}',
		self::RULE_MATCH => 'Field must be thes same as {match}'
	];

	/* Load the data for persistence */

	public function error($key, $rule, $params = [])
	{
		$message = $this->messages[$rule] ?? "";

		foreach ($params as $key => $value) {
			$message = str_replace("{{$key}}", $value, $message);
		}

		$this->errors[$key][] = $message;
	}

	public function addMessages($msg = [])
	{
		$this->messages = array_merge($this->messages, $msg);
	}

	public function hasError($key)
	{
		return $this->errors[$key] ?? false;
	}

	public function firstError($key)
	{
		Helper::pre($this->errors);
		return $this->errors[$key][0];
	}

	abstract public function load($data);

	abstract public function rules();

	abstract public function validate();
}

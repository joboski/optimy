<?php 

namespace optimy\app\models;

use optimy\app\core\Helper;

abstract class Model
{
	public const RULE_TYPE = 'type';
	public const RULE_REQUIRED = 'required';
	public const RULE_EMAIL = 'email';
	public const RULE_STRING = "string";
	public const RULE_NUMERIC = "numeric";
	public const RULE_FLOAT = "float";
	public const RULE_BOOL = "bool";
	public const RULE_MATCH = 'match';
	public const RULE_MIN = 'min';
	public const RULE_MAX = 'max';
	public const RULE_PASSWORD = 'password';
	public const RULE_INVALID = 'invalid';
	
	public $errors = [];

	protected $messages = [
		self::RULE_REQUIRED => 'This field is required',
		self::RULE_EMAIL => 'This field must be a valid email address',
		self::RULE_STRING => 'This field must not contain numbers',
		self::RULE_NUMERIC => 'This field must contain numbers only',
		self::RULE_FLOAT => 'This is a float value field',
		self::RULE_BOOL => 'This field should be true or false only',
		self::RULE_MIN => 'Minimum length must be {min} characters',
		self::RULE_MAX => 'Maximum length is {max} characters',
		self::RULE_MATCH => 'Field must be the same as {match}',
		self::RULE_PASSWORD => 'Password must be alphanumeric with atleast 1 uppercase and atleast 1 lowercase',
		self::RULE_INVALID => 'Invalid input'
	];

	abstract public function rules();
	/* Load the data for persistence */

	public function error($attribute, $rule, $params = [])
	{
		$message = $this->messages[$rule] ?? "";
		
		foreach ($params as $key => $value) {
			$message = str_replace("{{$key}}", $value, $message);
		}

		$this->errors[$attribute][] = $message;
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
		// Helper::pre($this->errors);
		return $this->errors[$key][0] ?? false;
	}

	public function load($data)
	{	
		foreach ($data as $key => $value) {
			// check if the property exists on this targeted model
			if (property_exists($this, $key)) {
				$this->{$key} = $value;
			}
		}
	}

	public function validate()
	{
		$rulesArray = $this->rules();

		foreach ($rulesArray as $attribute => $rule) {
			
			$value = $this->{$attribute};
			// Helper::pre($attribute);
			// Helper::dump($rules);
			if ($rule[self::RULE_REQUIRED] && empty($value)) {
				$this->error($attribute, self::RULE_REQUIRED);
			}

			if ($rule[self::RULE_TYPE] === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
				$this->error($attribute, self::RULE_EMAIL);
			}

			if ($rule[self::RULE_TYPE] === self::RULE_FLOAT && !filter_var($value, FILTER_VALIDATE_FLOAT)) {
				$this->error($attribute, self::RULE_FLOAT);
			}

			if ($rule[self::RULE_TYPE] === self::RULE_BOOL && !filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
				$this->error($attribute, self::RULE_BOOL);
			}

			if ($rule[self::RULE_TYPE] === self::RULE_STRING && !empty($value)) {
				$this->validateString($attribute, $value);
			}

			if ($rule[self::RULE_TYPE] === self::RULE_NUMERIC && !empty($value)) {
				$this->validateNumeric($attribute, $value);
			}

			if ($rule[self::RULE_TYPE] === self::RULE_PASSWORD && !empty($value)) {
				$this->validatePassword($attribute, $value);
			}

			if ($rule[self::RULE_TYPE] === self::RULE_MATCH && !empty($value)) {
				$this->validatePassword($attribute, $value);
			}

			if (array_key_exists(self::RULE_MIN, $rule) && strlen($value) < $rule[self::RULE_MIN]) {
				$this->error($attribute, self::RULE_MIN, $rule);
			}

			if (array_key_exists(self::RULE_MAX, $rule) && strlen($value) > $rule[self::RULE_MAX]) {
				$this->error($attribute, self::RULE_MAX, $rule);
			}
		}
		Helper::pre($this->errors);
	}

	private function validateString($attribute, $value)
    { 
        if (preg_match( '/\d+/m', $value)) {

        	$this->error($attribute, self::RULE_STRING);

        }
        if(!is_string($value)) {
            
            $this->error($attribute, self::RULE_INVALID);   
        }
    }

    private function validateNumeric($attribute, $value)
    {
        if (filter_var($value, FILTER_VALIDATE_INT)) {
            $this->error($attribute, self::RULE_NUMERIC);
        }
    }

    private function validatePassword($attribute, $value)
    {
    	if (!preg_match('/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(^[a-zA-Z0-9@\$=!:.#%]+$)/m', $value)) {
    		$this->error($attribute, self::RULE_PASSWORD);
    	}
    }
}

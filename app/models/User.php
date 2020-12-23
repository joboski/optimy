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

	private const RULE_FIRSTNAME_MIN = 'firstname_min';
	private const RULE_FIRSTNAME_MAX = 'firstname_max';
	private const RULE_LASTNAME_MIN = 'lastname_min';
	private const RULE_LASTNAME_MAX = 'lastname_max';
	private const RULE_PASSWORD_MIN = 'password_min';

	protected $msg = [
		self::RULE_FIRSTNAME_MIN => 'Minimum length of this character must be {firstname_min}',
		self::RULE_LASTNAME_MIN => 'Minimum length of this character must be {lastname_min}',
		self::RULE_PASSWORD_MIN => 'Minimum length of this character must be {password_min}',
		self::RULE_FIRSTNAME_MAX => 'Maximum length of this character must be {firstname_max}',
		self::RULE_LASTNAME_MAX => 'Maximum length of this character must be {lastname_max}',
	];

	public function load($data)
	{
		foreach ($data as $key => $value) {
			// check if the property exists on this targeted model
			if (property_exists($this, $key)) {
				$this->{$key} = $value;
			}
		}
	}

	public function rules() {
		
		return [
			"firstname" => [self::RULE_REQUIRED, [self::RULE_FIRSTNAME_MIN, "firstname_min" => "2"], [self::RULE_FIRSTNAME_MAX, "firstname_max" => "50"]],
			"lastname" => [self::RULE_REQUIRED,  [self::RULE_LASTNAME_MIN, "lastname_min" => "2"], [self::RULE_LASTNAME_MAX, "lastname_max" => "50"]],
			"email" => [self::RULE_REQUIRED, self::RULE_EMAIL],
			"password" => [self::RULE_REQUIRED, [self::RULE_PASSWORD_MIN, "password_min" => "8"]],
			"confirmPassword" => [self::RULE_REQUIRED, [self::RULE_MATCH, "match" => "password"]],
		];
	}

	public function validate()
	{
		$rulesArr = $this->rules();
		$this->addMessages($this->msg);
		// Helper::pre($rulesArr);

		foreach ($rulesArr as $key => $rules) {
			
			print($key);

			$attribute = $this->{$key};

			print($attribute);
			
			foreach ($rules as $rule) {
				
				$ruleName = $rule;
				
				if (is_array($rule)) {
					$ruleName = $rule[0];
				}

				// checking for defined rules
				if ($ruleName === self::RULE_REQUIRED && !$attribute) {
					$this->error($key, self::RULE_REQUIRED);
				}

				if ($ruleName === self::RULE_EMAIL && !filter_var($attribute, FILTER_VALIDATE_EMAIL)) {
					$this->error($key, self::RULE_EMAIL);
				}

				if ($ruleName === self::RULE_FIRSTNAME_MIN && strlen($attribute) < $rule["firstname_min"]) {
					$this->error($key, self::RULE_FIRSTNAME_MIN, $rule);
				}

				if ($ruleName === self::RULE_LASTNAME_MIN && strlen($attribute) < $rule["lastname_min"]) {
					$this->error($key, self::RULE_LASTNAME_MIN, $rule);
				}

				if ($ruleName === self::RULE_PASSWORD_MIN && strlen($attribute) < $rule["password_min"]) {
					$this->error($key, self::RULE_PASSWORD_MIN, $rule);
				}

				if ($ruleName === self::RULE_FIRSTNAME_MAX && strlen($attribute) > $rule["firstname_max"]) {
					$this->error($key, self::RULE_FIRSTNAME_MAX, $rule);
				}

				if ($ruleName === self::RULE_LASTNAME_MAX && strlen($attribute) > $rule["lastname_max"]) {
					$this->error($key, self::RULE_LASTNAME_MAX, $rule);
				}

				if ($ruleName === self::RULE_MATCH && $attribute !== $this->{$rule["match"]}) {
					$this->error($key, self::RULE_MATCH, $rule);
				}

			}
		}

		return empty($this->errors);
	}
}

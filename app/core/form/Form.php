<?php 

namespace optimy\app\core\form;

use optimy\app\models\Model;

class Form
{
	public static function begin($action, $method){
		echo sprintf('<form action="%s" method="%s">', $action, $method);
		return new Form();
	}

	public static function end()
	{
		echo "</form>";
	}

	public function field(Model $model, $attribute) {
		return new Field($model, $attribute);
	}
}

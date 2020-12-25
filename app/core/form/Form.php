<?php 

namespace optimy\app\core\form;

use optimy\app\models\Model;

class Form
{
	public function begin($action, $method){
		echo sprintf('<form action="%s" method="%s">', $action, $method);
	}

	public function end()
	{
		echo "</form>";
	}

	public function field(Model $model, $attribute) {
		return new Field($model, $attribute);
	}

	public static function instance()
	{
		return new Form();
	}
}
 
<?php 

namespace optimy\app\core\form;

use optimy\app\models\Model;

class Form
{
	public function begin($action, $method){
		echo sprintf('<form enctype="multipart/form-data" action="%s" method="%s">', $action, $method);
	}

	public function end()
	{
		echo "</form>";
	}

	public function field(Model $model, $attribute)
	{
		return new Field($model, $attribute);
	}

	public function select(Model $model, $attribute, $options = [])
	{
		return new Select($model, $attribute, $options);
	}

	public function textarea(Model $model, $attribute)
	{
		return new Textarea($model, $attribute);
	}

	public function file(Model $model, $attribute)
	{
		return new Filefield($model, $attribute);
	}

	public static function instance()
	{
		return new Form();
	}
}
 
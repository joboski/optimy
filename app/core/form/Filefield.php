<?php 

namespace optimy\app\core\form;

use optimy\app\models\Model;


class Filefield
{
	protected $model;
	protected $attribute;

	public function __construct(Model $model, $attribute){
		$this->model = $model;
		$this->attribute = $attribute;
	}

	public function __toString()
	{
		return sprintf(
			'<input type="file" id="%s" name="%s" value="%s" class="custom-file-input %s">',   
	        $this->attribute,
	        $this->attribute,
	        $this->model->{$this->attribute},
	        $this->model->hasError($this->attribute) ? 'is-invalid' : ''
		);
	}
}

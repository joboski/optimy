<?php 

namespace optimy\app\core\form;

use optimy\app\models\Model;
use optimy\app\core\Helper;


class Field
{
	public $model;
	public $attribute;
	public $errors = [];

	public function __construct(Model $model, $attribute){
		$this->model = $model;
		$this->attribute = $attribute;
		$this->errors = $this->model->errors;
		Helper::pre($this->errors);
	}

	public function __toString()
	{
		return sprintf(
			'<div class="form-group">
	            <label>%s</label>
	            <input type="text" name="%s" class="form-control %s">
	            <div class="invalid-feedback">%s</div>
	        </div>', 
	        $this->attribute, 
	        $this->attribute,
	        $this->model->{$this->attribute},
	        $this->model->hasError($this->attribute) ? "in-valid" : "",
	        $this->model->firstError($this->attribute)
		);
	}
}


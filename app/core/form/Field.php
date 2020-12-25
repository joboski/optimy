<?php 

namespace optimy\app\core\form;

use optimy\app\models\Model;
use optimy\app\core\Helper;


class Field
{
	public $model;
	public $attribute;

	public function __construct(Model $model, $attribute){
		$this->model = $model;
		$this->attribute = $attribute;
	}

	public function __toString()
	{
		return sprintf(
			'<div class="form-group">
	            <label>%s</label>
	            <input type="%s" name="%s" value="%s" class="form-control %s">
	            <div class="invalid-feedback">
	            	%s
	            </div>
	        </div>', 
	        ucfirst($this->attribute), 
	        $this->model->type($this->attribute),
	        $this->attribute,
	        $this->model->{$this->attribute},
	        $this->model->hasError($this->attribute)? 'is-invalid' : '',
	        $this->model->firstError($this->attribute)
		);
	}
}

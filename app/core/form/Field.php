<?php 

namespace optimy\app\core\form;

use optimy\app\models\Model;


class Field
{
	protected $model;
	protected $attribute;
	protected $type;
	protected $css;

	protected const TYPE_TEXT = "text";

	public function __construct(Model $model, $attribute){
		$this->model = $model;
		$this->attribute = $attribute;
		$this->type = self::TYPE_TEXT;
	}

	public function __toString()
	{
		return sprintf(
			'<div class="form-group %s">
	            <label class="center">%s</label>
	            <input type="%s" name="%s" value="%s" class="form-control %s">
	            <div class="invalid-feedback">
	            	%s
	            </div>
	        </div>',
	        $this->css ?? "",
	        $this->model->labels()[$this->attribute] ?? $this->attribute, 
	        $this->type,
	        $this->attribute,
	        $this->model->{$this->attribute},
	        $this->model->hasError($this->attribute)? 'is-invalid' : '',
	        $this->model->firstError($this->attribute)
		);
	}

	public function fieldType($type)
	{
		$this->type = $type;
		return $this;
	}

	public function cssStyle($style)
	{
		$this->css = $style;
		return $this;
	}
}

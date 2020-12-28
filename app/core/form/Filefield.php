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
			'<div class="input-group bottom_space">
			  <div class="custom-file">
			    <input type="file" id="%s" name="%s" class="custom-file-input %s">
			    <label class="custom-file-label" for="%s">Choose file</label>
			  </div>
			  <div class="invalid-feedback">
	            	%s
	           </div>
			</div>', 
	        $this->attribute,
	        $this->attribute,
	        $this->attribute,
	        $this->model->hasError($this->attribute) ? 'invalid-feedback' : '',
	        $this->model->firstError($this->attribute)
		);
	}
}

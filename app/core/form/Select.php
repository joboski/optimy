<?php 

namespace optimy\app\core\form;

use optimy\app\models\Model;

class Select
{
	private $model;
	private $attribute;
	private $options;


	public function __construct($model, $attribute, $options = [])
	{
		$this->model = $model;
		$this->attribute = $attribute;
		$this->options = $options;
	}

	public function __toString(){
		return sprintf(
			'<div class="form-group">
                <label for="%s">%s</label>
                <select class="form-control" id="%s" name="%s">
                    <option>%s</option>
                    <option>%s</option>
                    <option>%s</option>
                    <option>%s</option>
                </select>
            </div>',
            $this->attribute
            ucfirst($this->attribute), 
            $this->attribute,
            $this->attribute, 
            implode(",", array_map(function($opt) {
            	return $opt;
            }, $options));
		);
	}
}
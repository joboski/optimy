<?php 

namespace optimy\app\core\form;

use optimy\app\models\Model;
use optimy\app\core\Helper;

class Select
{
	private $model;
	private $attribute;
	private $options = [];

    // public const TYPE_SELECT = "select";

	public function __construct($model, $attribute, $options = [])
	{
		$this->model = $model;
		$this->attribute = $attribute;
		$this->options = array_map(function($opt){ return $opt;}, $options);
        // Helper::dump(implode(",",));
	}

	public function __toString(){
		return sprintf(
			'<div class="bottom_space">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="%s">%s</label>
                </div> 
                <select class="custom-select" id="%s" name="%s">
                    <option value="%s">%s</option>
                    <option value="%s">%s</option>
                    <option value="%s">%s</option>
                    <option value="%s">%s</option>
                </select>
            </div>
            </div>',
            $this->attribute,
            $this->model->labels()[$this->attribute] ?? $this->attribute, 
            $this->attribute,
            $this->attribute,
            $this->options[0],
            $this->model->labels()[$this->options[0]],
            $this->options[1],
            $this->model->labels()[$this->options[1]],
            $this->options[2],
            $this->model->labels()[$this->options[2]],
		    $this->options[3],
            $this->model->labels()[$this->options[3]]
        );
	}

    // public function selectField()
    // {
    //     return self::TYPE_SELECT;
    // }
}
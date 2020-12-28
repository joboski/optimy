<?php 

namespace optimy\app\core\form;

use optimy\app\models\Model;
use optimy\app\core\Helper;

class Textarea
{
    private $model;
    private $attribute;

    // public const TYPE_SELECT = "select";

    public function __construct($model, $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    public function __toString(){
        return sprintf(
            '<div class="bottom_space">
              <label class="label">
                %s
              </label>
              <textarea class="form-control" name=%s id=%s aria-label="%s" rows="5">%s</textarea>
            </div>',
            $this->model->labels()[$this->attribute] ?? $this->attribute,
            $this->attribute, 
            $this->attribute,
            $this->model->labels()[$this->attribute] ?? $this->attribute,
            $this->model->{$this->attribute}
        );
    }
}

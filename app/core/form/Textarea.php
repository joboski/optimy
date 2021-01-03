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
            '<div class="margin-tb">
              <label class="label">
                %s
              </label>
              <textarea class="form-control %s" name=%s id=%s aria-label="%s" rows="5">%s</textarea>
                <div class="invalid-feedback">
                    %s
                </div>
            </div>',
            $this->model->labels()[$this->attribute] ?? $this->attribute,
            $this->model->hasError($this->attribute)? 'is-invalid' : '',
            $this->attribute, 
            $this->attribute,
            $this->model->labels()[$this->attribute] ?? $this->attribute,
            $this->model->{$this->attribute},
            $this->model->firstError($this->attribute)
        );
    }
}

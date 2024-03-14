<?php

namespace App\Settings\Resolved\Workflows;

use Illuminate\Support\Arr;
use App\Settings\Resolved\Traits\HasScriptableFields;

class Operation
{
    use HasScriptableFields;

	protected $operation = null;

    public $validator = null;
    public $handler = null;

	public function __construct($operation)
    {
    	$this->operation = $operation;
        $this->fieldsWithScript = Arr::get($this->operation, 'fields', null);
        $this->validator = Arr::get($this->operation, 'validator', null);
        $this->handler = Arr::get($this->operation, 'handler', null);
    }

    public function __get($key)
    {
        switch ($key) {
            case "fields":
                return $this->getFields();
                break;      
        }
    }

}

<?php

namespace App\Settings\Resolved\Workflows;

use Illuminate\Support\Collection;
use App\Settings\NovaFactory;
use Illuminate\Support\Arr;

class OperationCollection extends Collection
{

	public function __construct($operations)
    {
        $operations = collect($operations)->map(function ($operation) {
            return new Operation($operation);
        });
    	parent::__construct($operations);
    }

    public function validator() 
    {
        return collect($this)->map(function ($operation) {
            return $operation->validator;
        })->all();
    }

    public function handler() 
    {
        return collect($this)->map(function ($operation) {
            $handlerName = 'handler_' . str_random(16);
            $handler = "function " . $handlerName . "() {\n" . 
                $operation->handler . "\n}\n" . $handlerName . "();\n\n";
            return $handler;
        })->implode('');
    }

    public function fields() 
    {
        return collect($this)->flatMap(function ($operation) {
            return $operation->fields->all();
        });
    }

}

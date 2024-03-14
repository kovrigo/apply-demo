<?php

namespace App\Settings\Query;

class Interpreter extends \EloquentJs\Query\Interpreter
{

    public function parseArray($calls = null)
    {
    	$calls = $calls ?: [];	
        return new Query($this->getMethodCalls($calls));
    }

}

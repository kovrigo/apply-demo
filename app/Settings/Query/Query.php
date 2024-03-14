<?php

namespace App\Settings\Query;

class Query extends \EloquentJs\Query\Query
{

	public static function make($calls)
	{
		$interpreter = new Interpreter;
		return $interpreter->parseArray($calls);
	}

	public function applyTo($builder)
	{
        foreach ($this->calls as $call) {
            call_user_func_array([$builder, $call->method], $call->arguments);
        }
        return $builder;
	}

}

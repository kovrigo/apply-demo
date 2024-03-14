<?php

namespace App\Settings\Resolved;

use Illuminate\Support\Collection;
use App\Settings\NovaFactory;
use Illuminate\Support\Arr;

class NovaCollection extends Collection
{

	public function __construct($values = [])
    {
        $values = collect($values)->map(function ($value) {
            return NovaFactory::make($value);
        });
    	parent::__construct($values);
    }

}

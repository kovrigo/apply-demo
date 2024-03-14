<?php

namespace App\Settings\Resolved;

use Illuminate\Support\Arr;

class ResourceCollection
{

	protected $resources = null;

	public function __construct($resources = [])
    {
    	$this->resources = $resources;
    }

    public function __get($key)
    {
    	$value = Arr::get($this->resources, $key, null);
    	if (is_object($value)) {
    		return $value;
    	}
    	if (is_array($value)) {
    		$value = new Resource($value);
    		Arr::set($this->resources, $key, $value);
    		return $value;
    	}
    	return new Resource([]);
    }

}

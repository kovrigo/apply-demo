<?php

namespace App\Settings\Resolved;

use Illuminate\Support\Collection;
use App\Settings\NovaFactory;
use Illuminate\Support\Arr;

class FieldCollection extends Collection
{

	public function __construct($fields)
    {
        $fieldCollection = Arr::get($fields, 'FieldCollection', []);
        $fields = collect($fieldCollection)->map(function ($field) {
            // Check constructor arguments for nested FieldCollections
            $constructor = Arr::get($field, 'constructor', []);
            $constructor = self::resolveNestedFieldCollections($constructor);
            Arr::set($field, 'constructor', $constructor);
            // Check calls for nested FieldCollections
            $calls = Arr::get($field, 'calls', []);
            $calls = self::resolveNestedFieldCollections($calls);
            Arr::set($field, 'calls', $calls);
            // Resolve field
            return NovaFactory::make($field);
        })->values();
    	parent::__construct($fields);
    }

    public static function resolveNestedFieldCollections($collection) 
    {
        return collect($collection)->map(function ($arg) {
            if (is_array($arg)) {
                if (Arr::has($arg, 'FieldCollection')) {
                    return new FieldCollection($arg);
                } else {
                    foreach ($arg as $key => $value) {
                        if (is_array($value) && Arr::has($value, 'FieldCollection')) {
                            Arr::set($arg, $key, new FieldCollection($value));
                        }
                    }
                }
            }
            return $arg;
        })->all();
    }

}

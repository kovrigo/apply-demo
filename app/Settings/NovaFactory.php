<?php

namespace App\Settings;

use Illuminate\Support\Arr;
use ReflectionClass;

class NovaFactory
{

	public static function make($object)
	{
		$alias = Arr::get($object, 'class', null);
		$class = self::resolveClassAlias($alias);
		$constructor = Arr::get($object, 'constructor', []);
		$calls = Arr::get($object, 'calls', []);
		if ($class) {
			$reflection = new ReflectionClass($class);
			$instance = $reflection->newInstanceArgs($constructor);
			foreach ($calls as $method => $args) {
				call_user_func_array([$instance, $method], $args);
			}
			return $instance;
		}
		return null;
	}

	public static function resolveClassAlias($alias) {
		return collect(self::CLASS_MAP)->get($alias);
	}

}

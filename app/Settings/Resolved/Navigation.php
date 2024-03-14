<?php

namespace App\Settings\Resolved;

use Illuminate\Support\Arr;
use App\Settings\Query\Interpreter;
use App\Settings\Query\Query;

class Navigation
{

    public static function countResources($navigation)
    {
        return collect($navigation)->map(function ($group, $groupName) {
        	return [
        		'label' => $groupName,
        		'links' => collect($group)->map(function ($link, $linkName) {
        			$count = Arr::get($link, 'count', null);
        			if (is_array($count)) {
        				$class = Arr::get($count, 'class', null);
        				$query = Arr::get($count, 'query', null);		
        				if ($class && $query) {
        					$class = '\\App\\' . $class;
		                    $count = Query::make($query)
		                        ->applyTo($class::query())
		                        ->count();
                            $count = $count ?: null;
        				} else {
        					$count = null;
        				}
        			}
        			return [
        				'label' => $linkName,
        				'url' => url(Arr::get($link, 'url', '/')),
        				'count' => $count,
        			];
        		}),
        	];
        });
    }

}

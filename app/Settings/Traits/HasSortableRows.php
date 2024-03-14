<?php

namespace App\Settings\Traits;

use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\EloquentSortable\SortableTrait;

trait HasSortableRows
{
    use \OptimistDigital\NovaSortable\Traits\HasSortableRows {
        getSortabilityConfiguration as private parentGetSortabilityConfiguration;
        indexQuery as private parentIndexQuery;
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
    	$query = parent::indexQuery($request, $query);
    	return static::parentIndexQuery($request, $query);
    }

    public static function getSortabilityConfiguration($model): ?array
    {
        if (is_null($model)) return null;

        // Check if spatie trait is in the model.
        if (!in_array(SortableTrait::class, trait_uses_recursive($model))) {
            return null;
        }

        // Get spatie defaut configuration.
        $defaultConfiguration = config('eloquent-sortable', []);
        // If model does not have sortable configuration return the default.
        if (!isset($model->sortable)) return $defaultConfiguration;

        return array_merge($defaultConfiguration, $model->sortable);
    }    

}
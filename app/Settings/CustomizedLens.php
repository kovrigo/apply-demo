<?php

namespace App\Settings;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use App\Settings\Resolved\Traits\HasScriptableFields;
use App\Settings\Query\Query;
use App\Settings\Resolved\NovaCollection;

class CustomizedLens extends Lens
{
    use HasScriptableFields;

    public $key =  null;
    public $query = [];
    public $filters = [];


    public function setName($name)
    {
        return $this->name = $name;
    }

    public function setKey($key)
    {
        return $this->key = $key;
    }

    public function setQuery($query)
    {
        return $this->query = $query;
    }

    public function setFields($fields)
    {
        return $this->fieldsWithScript = $fields;
    }

    public function setFilters($filters)
    {
        return $this->filters = $filters;
    }

    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        Query::make($request->lens()->query)->applyTo($query);
        return $request->withOrdering($request->withFilters(
            $query
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $request = LensRequest::createFrom($request);
        return $request->lens()->getFields()->all();
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        $request = LensRequest::createFrom($request);
        $filters = new NovaCollection($request->lens()->filters);
        return $filters->all();
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return $this->key;
    }

}

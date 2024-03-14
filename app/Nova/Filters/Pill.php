<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use DigitalCreative\PillFilter\PillFilter;
use Laravel\Nova\Nova;
use App\Settings\RequestContext;

class Pill extends PillFilter
{

    protected $label;
    protected $relation;

    public function __construct($label, $relation)
    {
        parent::__construct();
        $this->label = $label;
        $this->relation = $relation;
    }

    public function name()
    {
        return $this->label;
    }

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $values)
    {
        $model = RequestContext::make()->model();
        $relation = $this->relation;
        $foreignKey = $model->$relation()->getForeignKeyName();
        return $query->whereIn($foreignKey, $values);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $model = RequestContext::make()->model();
        $relation = $this->relation;
        $relatedModelClass = get_class($model->$relation()->getRelated());
        return $relatedModelClass::get()->mapWithKeys(function ($model) {
            return [
                $model->model_title => $model->id,
            ];
        })->all();
    }
}
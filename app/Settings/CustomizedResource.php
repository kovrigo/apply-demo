<?php

namespace App\Settings;

use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;
use Inspheric\NovaDefaultable\HasDefaultableFields;
use Eminiarts\Tabs\TabsOnEdit;
use Laravel\Nova\Resource;
use App\Settings\Translator;
use Epartment\NovaDependencyContainer\HasDependencies;
use Laravel\Nova\Fields\ID;

class CustomizedResource extends Resource
{
    use HasDefaultableFields, TabsOnEdit, HasDependencies;

    protected $customized = null;

    /**
     * The name of the field that should be used to group resources in select options
     *
     * @var string
     */
    public static $optionsGroup = null;

    public function optionsGroup()
    {
        if (static::$optionsGroup) {
            return $this->model()->{static::$optionsGroup};
        }
        return null;
    }

    /**
     * Indicates whether Nova should check for modifications between viewing and updating a resource.
     *
     * @var bool
     */
    public static $trafficCop = false;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'model_title';

    /**
     * Create a new resource instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $resource
     * @return void
     */
    public function __construct($resource)
    {
        $this->customized = self::getCustomizedResource();
        parent::__construct($resource);
    }

    public static function getCustomizedResource()
    {
        $resourceClass = class_basename(get_called_class());
        return apply()->settings->resources->$resourceClass;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $fields = optional($this->customized->fields)->all();
        $fields = $fields ?: [];
        $fields = array_merge($fields, [$this->idField()]);
        return $fields;
    }    

    public function idField()
    {
        return ID::make()
            ->hideFromIndex()
            ->hideFromDetail();
    }

    /**
     * Get the filters available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return optional($this->customized->filters)->all();
    }

    /**
     * Get the lenses available on the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return optional($this->customized->lenses)->all();
    }    

    /**
     * Get the actions available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return optional($this->customized->actions)->all();
    }

    /**
     * Get the cards available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return optional($this->customized->cards)->all();
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return optional(self::getCustomizedResource())->getIndexQuery($query) ?: $query;
    }

    /**
     * Return the location to redirect the user after creation.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        $fallback = '/resources/' . static::uriKey() . '/' . $resource->getKey();
        return static::redirectAfter('Create', $fallback, $resource);
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        $fallback =  '/resources/'.static::uriKey().'/'.$resource->getKey();
        return static::redirectAfter('Update', $fallback, $resource);
    }

    /**
     * Return the location to redirect the user after deletion.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return string
     */
    public static function redirectAfterDelete(NovaRequest $request)
    {
        $fallback = '/resources/'.static::uriKey();
        return static::redirectAfter('Delete', $fallback);
    }

    public static function redirectAfter($action, $fallback, $resource = null)
    {
        $redirect = optional(self::getCustomizedResource())->{'redirectAfter' . $action};
        if (is_null($redirect)) {
            return $fallback;
        }
        return Translator::replace($redirect, [
            'resource' => static::uriKey(),
            'id' => optional($resource)->getKey(),
        ]);
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        $label = optional(self::getCustomizedResource())->label;
        if ($label) {
            return $label;
        }
        return parent::label();
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        $label = optional(self::getCustomizedResource())->singularLabel;
        if ($label) {
            return $label;
        }
        return parent::singularLabel();
    }

}

<?php

namespace Kovrigo\CustomMorphTo;

use Laravel\Nova\Fields\MorphTo;
use Illuminate\Http\Request;
use Auth;

class CustomMorphTo extends MorphTo
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'custom-morph-to';

    public function authorize(Request $request)
    {
        //if ($request->isUpdateOrUpdateAttachedRequest() || $request->isCreateOrAttachRequest()) {
        if ($request->isUpdateOrUpdateAttachedRequest()) { 
            if (!in_array($this->morphToType, collect($this->morphToTypes)->pluck('value')->values()->all())) {
            	return false;
            }
        }
        $this->withMeta([
            'authorized' => parent::authorize($request),
        ]);
    	return true;
    }

    /**
     * Set the types of resources that may be related to the resource.
     *
     * @param  array  $types
     * @return $this
     */
    public function types(array $types)
    {
        $this->morphToTypes = collect($types)->filter(function ($resourceClass) {
        	$modelClass = $resourceClass::$model;
        	return Auth::user()->can("viewAny", $modelClass);
        })
        ->map(function ($display, $key) {
            return [
                'type' => is_numeric($key) ? $display : $key,
                'singularLabel' => is_numeric($key) ? $display::singularLabel() : $key::singularLabel(),
                'display' => (is_string($display) && is_numeric($key)) ? $display::singularLabel() : $display,
                'value' => is_numeric($key) ? $display::uriKey() : $key::uriKey(),
            ];
        })->values()->all();
        return $this;
    }

}
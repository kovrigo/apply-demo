<?php

namespace Kovrigo\CustomJson;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class CustomJson extends Field
{

    public function __construct($attribute = null, $resolveCallback = null)
    {
        parent::__construct($attribute,$resolveCallback);
        $this->withMeta([
            'mode' => 'code',
            'expandedOnStart' => false,
            'defaultJson' => (object) [],
        ]);
    }
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'custom-json';

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param  mixed  $resource
     * @param  string  $attribute
     * @return mixed
     */
    protected function resolveAttribute($resource, $attribute)
    {
        return data_get($resource, str_replace('->', '.', $attribute));
    }

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {
        // decoding the input so we can use the laravel casts
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = json_decode($request->get($requestAttribute), true);
        }
    }

    public function mode(string $mode)
    {
        return $this->withMeta(['mode' => $mode]);
    }
    public function expandedOnStart(bool $expandedOnStart)
    {
        return $this->withMeta(['expandedOnStart' => $expandedOnStart]);
    }
    public function defaultJsonPath(string $defaultJson)
    {
        return $this->withMeta(['defaultJson' => (object) json_decode(file_get_contents($defaultJson))]);
    }
}
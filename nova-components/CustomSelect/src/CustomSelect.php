<?php

namespace Kovrigo\CustomSelect;

use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class CustomSelect extends Select
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'custom-select';

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $value = json_decode($request->input($requestAttribute));
        $model->{$attribute} = $value;
    }

}

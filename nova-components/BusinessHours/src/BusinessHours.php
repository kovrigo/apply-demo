<?php

namespace Kovrigo\BusinessHours;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class BusinessHours extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'business-hours';
 
}

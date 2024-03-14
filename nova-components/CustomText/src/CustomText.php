<?php

namespace Kovrigo\CustomText;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class CustomText extends Text
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'custom-text';

}

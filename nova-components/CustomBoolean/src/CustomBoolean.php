<?php

namespace Kovrigo\CustomBoolean;

use Laravel\Nova\Fields\Boolean;

class CustomBoolean extends Boolean
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'custom-boolean';

}

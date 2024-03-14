<?php

namespace Kovrigo\CustomTrix;

use Laravel\Nova\Fields\Trix;

class CustomTrix extends Trix
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'custom-trix';
}

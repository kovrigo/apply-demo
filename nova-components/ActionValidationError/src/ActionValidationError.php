<?php

namespace Kovrigo\ActionValidationError;

use Laravel\Nova\Fields\Field;

class ActionValidationError extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'action-validation-error';

    public function __construct()
    {
    	parent::__construct('Action validation error', 'action_validation_error');
    }

}

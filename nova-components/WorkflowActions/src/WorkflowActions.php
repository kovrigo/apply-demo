<?php

namespace Kovrigo\WorkflowActions;

use Laravel\Nova\Card;

class WorkflowActions extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '2/3';

    /**
     * Indicates if the element is only shown on the detail screen.
     *
     * @var bool
     */
    public $onlyOnDetail = true;

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'workflow-actions';
    }

}

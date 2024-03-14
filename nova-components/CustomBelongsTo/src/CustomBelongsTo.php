<?php

namespace Kovrigo\CustomBelongsTo;

use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Http\Request;

class CustomBelongsTo extends BelongsTo
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'custom-belongs-to';

    public function __construct($name, $attribute = null, $resource = null)
    {
        parent::__construct($name, $attribute, $resource);
        // TODO: need to change some styles to enable trashed
        $this->displaysWithTrashed = false;
    }

    /**
     * Determine if the field is the reverse relation of a showed index view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isReverseRelation(Request $request)
    {
        if (! $request->viaResource || ($this->resourceName && $this->resourceName !== $request->viaResource)) {
            return false;
        }
        return true;
    }

}

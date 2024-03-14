<?php

namespace Kovrigo\CustomTextarea;

use Laravel\Nova\Fields\Textarea;

class CustomTextarea extends Textarea
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'custom-textarea';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = true;

    /**
     * Display the field as raw HTML using Vue.
     *
     * @return $this
     */
    public function asHtml()
    {
        return $this->withMeta(['asHtml' => true]);
    }

    /**
     * Display the field as markdown using Vue.
     *
     * @return $this
     */
    public function asMarkdown()
    {
        return $this->withMeta(['asMarkdown' => true]);
    }
}

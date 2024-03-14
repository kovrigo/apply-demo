<?php

namespace Kovrigo\Link;

use Laravel\Nova\Fields\Field;

class Link extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'link';

    public function __construct($name, $attribute = null, $resource = null)
    {
        parent::__construct($name, $attribute, $resource);
        $this->onlyOnDetail();
    }

    public function template($template)
    {
    	return $this->withMeta([
    		'template' => $template,
    	]);
    }

    public function text($text)
    {
    	return $this->withMeta([
    		'text' => $text,
    	]);
    }

    public function target($target)
    {
    	return $this->withMeta([
    		'target' => $target,
    	]);
    }

}

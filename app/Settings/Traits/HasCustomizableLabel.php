<?php

namespace App\Settings\Traits;

trait HasCustomizableLabel
{

    public function label($label)
    {
        $this->name = $label;
    }

}
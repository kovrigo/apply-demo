<?php

namespace App\Settings;

class ActionRequest extends \Laravel\Nova\Http\Requests\ActionRequest
{

    public function models()
    {
        return $this->toSelectedResourceQuery()->when(! $this->forAllMatchingResources(), function ($query) {
            $query->whereKey(explode(',', $this->resources));
        })->get();
    }

    public function dialog()
    {
        return collect($this->action()->fields())
            ->mapWithKeys(function ($field) {
                return [$field->attribute => $this->{$field->attribute}];
            });
    }

}

<?php

namespace App\Relations;

trait Page
{

    public function functionalAreas()
    {
        return $this->belongsToMany('App\FunctionalArea');
    }

    public function customFunctionalAreas()
    {
        return $this->belongsToMany('App\CustomFunctionalArea');
    }

    public function employments()
    {
        return $this->morphToMany('App\Employment', 'employment_pivot');
    }

    public function faqs()
    {
        return $this->morphMany('App\Faq', 'relatable');
    }

    public function infoBlocks()
    {
        return $this->morphMany('App\InfoBlock', 'relatable');
    }

}

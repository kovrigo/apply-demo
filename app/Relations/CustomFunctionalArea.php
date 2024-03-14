<?php

namespace App\Relations;

trait CustomFunctionalArea
{

    public function customFunctionalAreaGroup()
    {
        return $this->belongsTo('App\CustomFunctionalAreaGroup');
    }

    public function functionalAreas()
    {
        return $this->belongsToMany('App\FunctionalArea');
    }

    public function jobs()
    {
        return $this->belongsToMany('App\Job');
    }

    public function pages()
    {
        return $this->belongsToMany('App\Job');
    }

}

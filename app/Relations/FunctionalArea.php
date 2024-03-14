<?php

namespace App\Relations;

trait FunctionalArea
{

    public function functionalAreaGroup()
    {
        return $this->belongsTo('App\FunctionalAreaGroup');
    }

    public function jobs()
    {
        return $this->belongsToMany('App\Job');
    }

    public function pages()
    {
        return $this->belongsToMany('App\Page');
    }

    public function interests()
    {
        return $this->belongsToMany('App\Interest');
    }

}

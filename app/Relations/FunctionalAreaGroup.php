<?php

namespace App\Relations;

trait FunctionalAreaGroup
{

    public function functionalAreas()
    {
        return $this->hasMany('App\FunctionalArea');
    }

}

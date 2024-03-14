<?php

namespace App\Relations;

trait CustomFunctionalAreaGroup
{

    public function customFunctionalAreas()
    {
        return $this->hasMany('App\CustomFunctionalArea');
    }

}

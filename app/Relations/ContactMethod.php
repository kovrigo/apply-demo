<?php

namespace App\Relations;

trait ContactMethod
{

    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }

}

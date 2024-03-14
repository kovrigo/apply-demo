<?php

namespace App\Relations;

trait PlaceGroupAddress
{

    public function placeGroup()
    {
        return $this->belongsTo('App\PlaceGroup');
    }

}

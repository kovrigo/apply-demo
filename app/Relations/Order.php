<?php

namespace App\Relations;

trait Order
{

    public function record()
    {
        return $this->belongsTo('App\Record');
    }

}


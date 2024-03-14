<?php

namespace App\Relations;

trait Contact
{

    public function contactable()
    {
        return $this->morphTo();
    }

    public function contactMethod()
    {
        return $this->belongsTo('App\ContactMethod');
    }

}

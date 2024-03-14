<?php

namespace App\Relations;

trait Record
{

    public function orders()
    {
        return $this->hasMany('App\Order');
    }    

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }          

    public function artists()
    {
        return $this->belongsToMany('App\Artist');
    }    

}


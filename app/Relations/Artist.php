<?php

namespace App\Relations;

trait Artist
{

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }    

    public function records()
    {
        return $this->belongsToMany('App\Record');
    }    

}


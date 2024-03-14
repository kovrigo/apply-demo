<?php

namespace App\Relations;

trait ArticleState
{

    public function articles()
    {
        return $this->hasMany('App\Article');
    }

}


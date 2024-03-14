<?php

namespace App\Relations;

trait ArticleLog
{

    public function articleState()
    {
        return $this->belongsTo('App\ArticleState');
    }

    public function article()
    {
        return $this->belongsTo('App\Article');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }    

    public function state()
    {
        return $this->articleState();
    }

}


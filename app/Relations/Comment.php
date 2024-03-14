<?php

namespace App\Relations;

trait Comment
{

    public function commentable()
    {
        return $this->morphTo()->withTrashed();
    }    

}


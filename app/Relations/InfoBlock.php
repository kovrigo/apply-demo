<?php

namespace App\Relations;

trait InfoBlock
{

    public function relatable()
    {
        return $this->morphTo()->withTrashed();
    }

    public function infoBlockItems()
    {
        return $this->hasMany('App\InfoBlockItem');
    }

}

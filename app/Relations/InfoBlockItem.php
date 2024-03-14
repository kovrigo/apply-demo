<?php

namespace App\Relations;

trait InfoBlockItem
{

    public function infoBlock()
    {
        return $this->belongsTo('App\InfoBlock');
    }

}

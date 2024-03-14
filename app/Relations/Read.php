<?php

namespace App\Relations;

trait Read
{

    public function readable()
    {
        return $this->morphTo()->withTrashed();
    }

}

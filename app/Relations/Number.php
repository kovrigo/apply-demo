<?php

namespace App\Relations;

trait Number
{

    public function relatable()
    {
        return $this->morphTo()->withTrashed();
    }

}

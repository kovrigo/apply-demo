<?php

namespace App\Relations;

trait Faq
{

    public function relatable()
    {
        return $this->morphTo()->withTrashed();
    }

}

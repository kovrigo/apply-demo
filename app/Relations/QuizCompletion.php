<?php

namespace App\Relations;

trait QuizCompletion
{

    public function quiz()
    {
        return $this->belongsTo('App\Quiz');
    }

    public function participant()
    {
        return $this->morphTo()->withTrashed();
    }

}

<?php

namespace App\Relations;

trait QuizAnswer
{

    public function quizQuestion()
    {
        return $this->belongsTo('App\QuizQuestion');
    }

}

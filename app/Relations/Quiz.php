<?php

namespace App\Relations;

trait Quiz
{

    public function quizQuestions()
    {
        return $this->hasMany('App\QuizQuestion');
    }

    public function quizCompletions()
    {
        return $this->hasMany('App\QuizCompletion');
    }

}

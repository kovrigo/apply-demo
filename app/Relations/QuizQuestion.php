<?php

namespace App\Relations;

trait QuizQuestion
{

    public function quiz()
    {
        return $this->belongsTo('App\Quiz');
    }

    public function quizAnswers()
    {
        return $this->hasMany('App\QuizAnswer');
    }

    public function assignee()
    {
        return $this->belongsTo('App\User', 'assignee_id');
    }

    public function chats()
    {
        return $this->morphMany('App\Chat', 'subject');
    }

}

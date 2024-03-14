<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\QuizQuestion;
use App\QuizAnswer;
use App\Chat;

class QuizQuestionPolicy extends CustomizedPolicy
{

    public function addQuizAnswer(User $user, QuizQuestion $model)
    {
        return $this->policy->can('addQuizAnswer', $model);
    }

    public function addChat(User $user, QuizQuestion $model)
    {
        return $this->policy->can('addChat', $model);
    }

}


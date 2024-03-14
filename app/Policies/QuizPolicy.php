<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\Quiz;
use App\QuizQuestion;

class QuizPolicy extends CustomizedPolicy
{

    public function addQuizQuestion(User $user, Quiz $model)
    {
        return $this->policy->can('addQuizQuestion', $model);
    }

}


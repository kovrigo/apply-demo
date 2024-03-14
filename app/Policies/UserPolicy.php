<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\UserProfile;

class UserPolicy extends CustomizedPolicy
{

    public function attachUserProfile(User $user, User $model, UserProfile $relatedModel)
    {
        return $this->policy->can('attachUserProfile', $model, $relatedModel);
    }

    public function addUser(User $user, Department $model)
    {
        return $this->policy->can('addUser', $model);
    }

    public function addProjectTask(User $user, $model)
    {
        return $this->policy->can('addProjectTask', $model);
    }

}


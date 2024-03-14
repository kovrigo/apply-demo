<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;

class ProjectRolePolicy extends CustomizedPolicy
{

    public function addUser(User $user, $model)
    {
        return $this->policy->can('addUser', $model);
    }

    public function addProjectRate(User $user, $model)
    {
        return $this->policy->can('addProjectRate', $model);
    }

    public function addProjectTask(User $user, $model)
    {
        return $this->policy->can('addProjectTask', $model);
    }

}


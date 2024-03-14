<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;

class ProjectTaskPolicy extends CustomizedPolicy
{

    public function addProjectTaskLink(User $user, $model)
    {
        return $this->policy->can('addProjectTaskLink', $model);
    }

    public function addProjectTask(User $user, $model)
    {
        return $this->policy->can('addProjectTask', $model);
    }

    public function addProjectTransaction(User $user, $model)
    {
        return $this->policy->can('addProjectTransaction', $model);
    }

}


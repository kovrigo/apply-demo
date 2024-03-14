<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\Project;

class ProjectPolicy extends CustomizedPolicy
{

    public function addProject(User $user, $model)
    {
        return $this->policy->can('addProject', $model);
    }

    public function addProjectRate(User $user, $model)
    {
        return $this->policy->can('addProjectRate', $model);
    }

    public function addProjectRole(User $user, $model)
    {
        return $this->policy->can('addProjectRole', $model);
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


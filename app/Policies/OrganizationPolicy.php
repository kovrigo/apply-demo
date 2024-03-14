<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\Organization;

class OrganizationPolicy extends CustomizedPolicy
{

    public function addProject(User $user, Organization $model)
    {
        return $this->policy->can('addProject', $model);
    }

    public function addProjectTask(User $user, $model)
    {
        return $this->policy->can('addProjectTask', $model);
    }

}


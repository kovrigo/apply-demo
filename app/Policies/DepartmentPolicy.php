<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\Department;

class DepartmentPolicy extends CustomizedPolicy
{

    public function addDepartment(User $user, User $model)
    {
        return $this->policy->can('addDepartment', $model);
    }

    public function addProject(User $user, Department $model)
    {
        return $this->policy->can('addProject', $model);
    }

}


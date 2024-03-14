<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\Workplace;
use App\Job;

class WorkplacePolicy extends CustomizedPolicy
{

    public function addJob(User $user, Workplace $model)
    {
        return $this->policy->can('addJob', $model);
    }

}


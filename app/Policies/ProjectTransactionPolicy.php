<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;

class ProjectTransactionPolicy extends CustomizedPolicy
{

    public function addProjectTransaction(User $user, $model)
    {
        return $this->policy->can('addProjectTransaction', $model);
    }

}


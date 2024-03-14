<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;

class NewOrderPolicy extends CustomizedPolicy
{

    public function addNewOrderLog(User $user, $model)
    {
        return $this->policy->can('addNewOrderLog', $model);
    }

}


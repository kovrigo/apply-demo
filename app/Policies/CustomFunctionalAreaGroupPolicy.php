<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\CustomFunctionalAreaGroup;
use App\CustomFunctionalArea;

class CustomFunctionalAreaGroupPolicy extends CustomizedPolicy
{

    public function addCustomFunctionalArea(User $user, CustomFunctionalAreaGroup $model)
    {
        return $this->policy->can('addCustomFunctionalArea', $model);
    }

}


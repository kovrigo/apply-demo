<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\CustomFunctionalArea;
use App\FunctionalArea;

class CustomFunctionalAreaPolicy extends CustomizedPolicy
{

    public function attachFunctionalArea(User $user, CustomFunctionalArea $model, FunctionalArea $relatedModel)
    {
        return $this->policy->can('attachFunctionalArea', $model, $relatedModel);
    }

}


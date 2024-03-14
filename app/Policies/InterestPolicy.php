<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\Interest;
use App\AdvertisingPlatformInterest;
use App\FunctionalArea;

class InterestPolicy extends CustomizedPolicy
{

    public function addAdvertisingPlatformInterest(User $user, Interest $model)
    {
        return $this->policy->can('addAdvertisingPlatformInterest', $model);
    }

    public function attachFunctionalArea(User $user, Interest $model, FunctionalArea $relatedModel)
    {
        return $this->policy->can('attachFunctionalArea', $model, $relatedModel);
    }

}


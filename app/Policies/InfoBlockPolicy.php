<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\InfoBlock;
use App\InfoBlockItem;

class InfoBlockPolicy extends CustomizedPolicy
{

    public function addInfoBlockItem(User $user, InfoBlock $model)
    {
        return $this->policy->can('addInfoBlockItem', $model);
    }

}


<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;

class JobPageTemplatePolicy extends CustomizedPolicy
{

    public function addJobPageTemplate(User $user, $model)
    {
        return $this->policy->can('addJobPageTemplate', $model);
    }

}


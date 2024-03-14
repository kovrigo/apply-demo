<?php

namespace App\Policies;

use App\Settings\CustomizedPolicy;
use App\User;
use App\Page;
use App\Faq;
use App\InfoBlock;
use App\FunctionalArea;
use App\CustomFunctionalArea;
use App\Employment;

class PagePolicy extends CustomizedPolicy
{

    public function addFaq(User $user, Page $model)
    {
        return $this->policy->can('addFaq', $model);
    }

    public function addInfoBlock(User $user, Page $model)
    {
        return $this->policy->can('addInfoBlock', $model);
    }

    public function attachFunctionalArea(User $user, Page $model, FunctionalArea $relatedModel)
    {
        return $this->policy->can('attachFunctionalArea', $model, $relatedModel);
    }

    public function attachCustomFunctionalArea(User $user, Page $model, CustomFunctionalArea $relatedModel)
    {
        return $this->policy->can('attachCustomFunctionalArea', $model, $relatedModel);
    }

    public function attachEmployment(User $user, Page $model, Employment $relatedModel)
    {
        return $this->policy->can('attachEmployment', $model, $relatedModel);
    }

}


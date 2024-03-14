<?php

namespace App;

use App\Settings\Model;

class Application extends Model
{
	use \App\Relations\Application;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Workflows\Traits\Workflowable;
	use \App\Restrictions\Application;
    use \App\Traits\RespectsOwnership;

    public function state()
    {
        return $this->applicationStage();
    }

    public function stateClass()
    {
        return 'App\\ApplicationStage';
    }

    public static $resourceTitleAttribute = "default_title";

    public function getDefaultTitleAttribute()
    {
        return $this->profile->title . " (" . $this->job->name . ")";
    }

    public function stateForeignKey()
    {
        return 'application_stage_id';
    }

}


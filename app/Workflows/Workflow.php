<?php

namespace App\Workflows;

use App\Settings\Model;
use App\Settings\JsonDereferencer;
use App\Settings\Resolved\Workflows\Workflow as ResolvedWorkflow;
use Illuminate\Support\Str;

class Workflow extends Model
{
    use \App\Tenancy\Traits\RespectsTenancy;

    protected $resolvedSettings = null;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'array',
    ];

    public function userProfiles()
    {
        return $this->belongsToMany('App\UserProfile');
    }

    public function getSettings()
    {
        if (is_null($this->resolvedSettings)) {
            $this->resolvedSettings = new ResolvedWorkflow(
                JsonDereferencer::dereference($this->settings, apply()->definitions)
            );
        }
        return $this->resolvedSettings;
    }

    public function initialState()
    {
        return $this->getSettings()->initialState();
    }

}

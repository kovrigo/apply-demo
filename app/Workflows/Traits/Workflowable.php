<?php

namespace App\Workflows\Traits;

use App\Scopes\TenantScope;
use Laravel\Nova\Nova;
use App\Workflow;
use Illuminate\Support\Str;

trait Workflowable
{

    /**
     * Get all of the appendable values that are arrayable.
     *
     * @return array
     */
    protected function getArrayableAppends()
    {
        $appends = parent::getArrayableAppends();
        $appends['state_code'] = 'state_code';
        $appends['state_timestamp'] = 'state_timestamp';
        return $appends;
    }

    public static function bootWorkflowable()
    {
        static::creating(function ($model) {
            if (is_null($model->workflow)) {
                $workflowRelation = static::workflowRelationName();
                $workflow = apply()->user->userProfile->$workflowRelation()->first();
                $model->workflow()->associate($workflow);
            }
            if (!is_null($model->workflow) && is_null($model->state)) {
                $stateClass = $model->stateClass();
                $state = $stateClass::where('code', $model->workflow->initialState())->first();
                $model->state()->associate($state);
            }
        });
    }

    public function workflow()
    {
        return $this->belongsTo($this->workflowClass(), $this->workflowForeignKey());
    }

    public function state()
    {
        return $this->belongsTo($this->stateClass(), $this->stateForeignKey());
    }

    public function logs()
    {
        return $this->hasMany($this->logsClass());
    }

    public function scopeOfState($query, $codes)
    {
        $codes = explode('|', $codes);
        // Get state ids
        $stateClass = $this->stateClass();
        $sateIds = $stateClass::whereIn('code', $codes)->pluck('id');
        return $query->whereIn($this->stateForeignKey(), $sateIds);
    }

    public function workflowClass()
    {
        $modelClass = class_basename(get_called_class());
        return 'App\\' . $modelClass . 'Workflow';
    }

    public function stateClass()
    {
        $modelClass = class_basename(get_called_class());
        return 'App\\' . $modelClass . 'State';
    }

    public function logsClass()
    {
        $modelClass = class_basename(get_called_class());
        return 'App\\' . $modelClass . 'Log';
    }

    public function workflowForeignKey()
    {
        $modelClass = class_basename(get_called_class());
        return Str::snake($modelClass) . '_workflow_id';
    }

    public function stateForeignKey()
    {
        $modelClass = class_basename(get_called_class());
        return Str::snake($modelClass) . '_state_id';
    }

    public static function workflowRelationName()
    {
        $modelClass = class_basename(get_called_class());
        return Str::camel($modelClass) . 'Workflows';
    }

    public function getStateCodeAttribute()
    {
        return optional($this->state)->code;
    }

    public function getStateTimestampAttribute()
    {
        $log = $this->logs()->latest()->first();
        $date = $log ? $log->created_at : $this->created_at;
        return $date->timestamp;
    }

}
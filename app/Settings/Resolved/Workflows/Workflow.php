<?php

namespace App\Settings\Resolved\Workflows;

use Illuminate\Support\Arr;

class Workflow
{

	protected $workflow = null;

	public function __construct($workflow = [])
    {
    	$this->workflow = $workflow;
    }

    public function transitionsFrom($from)
    {
    	return collect(Arr::get($this->workflow, $from, []))->map(function ($transition, $to) use ($from) {
            $permissions = Arr::get($transition, 'permissions', []);
            $granted = in_array(apply()->userProfileCode, $permissions);
            return $granted ? new Transition($transition, $from, $to) : null;
        })->filter(function ($transition) {
            return !is_null($transition);
        })->values();
    }

    public function initialState()
    {
        return collect($this->workflow)->keys()->first();
    }

}

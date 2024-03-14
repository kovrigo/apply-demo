<?php

namespace App\Settings\Resolved\Workflows;

use Illuminate\Support\Arr;
use App\Settings\Resolved\Traits\HasScriptableFields;

class Transition
{

	protected $transition = null;

    protected $operations = null;
    
    public $label = null;
    public $redirect = null;
    public $from = null;
    public $to = null;
    public $key = null;

	public function __construct($transition, $from, $to)
    {
    	$this->transition = $transition;
        $this->label = Arr::get($this->transition, 'label', null);
        $this->redirect = Arr::get($this->transition, 'redirect', '/resources/:resource/:id');
        $this->from = $from;
        $this->to = $to;
        $this->key = $from . '-' . $to;
    }

    public function __get($key)
    {
        switch ($key) {
            case "operations":
                return $this->getOperations();
                break;        
        }
    }

    public function getOperations()
    {
        if (is_null($this->operations)) {
            $this->operations = new OperationCollection(Arr::get($this->transition, 'operations', []));
        }
        return $this->operations;
    }

}

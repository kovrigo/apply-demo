<?php

namespace App\Settings\Resolved;

use Illuminate\Support\Arr;

class Settings
{

	public $resources = null;
	public $home = null;

	protected $navigationWithCount = null;
	protected $navigation = null;

	public function __construct($settings = [])
    {
    	$this->resources = new ResourceCollection(Arr::get($settings, 'resources', null));
    	$this->navigationWithCount = Arr::get($settings, 'navigation', null);
    	$this->home = url(Arr::get($settings, 'home', '/'));
    }

    public function __get($key)
    {
        switch ($key) {
            case "navigation":
                return $this->getNavigation();
                break;          
        }
    }

    public function getNavigation()
    {
        if (is_null($this->navigation)) {
        	$this->navigation = Navigation::countResources($this->navigationWithCount);
        }
        return $this->navigation;
    }

}

<?php

namespace App\Settings;

use Laravel\Nova\Http\Requests\NovaRequest;

class RequestContext extends NovaRequest
{

	protected $context = [];

	public static function make() 
	{
		$instance = static::createFrom(request());
		return $instance
			->set("user", apply()->systemUser)
			->set("auth", apply()->user)
			->set("model", $instance->serializedModel())
			->set("relatedModel", null)
			->set("request", $instance->serializedRequest());
	}

	public function set($key, $value)
	{
		$this->context[$key] = $value;
		return $this;
	}

	public function forScripting()
	{
		return $this->context;
	}

	public function resourceId()
	{
        $resourceId = $this->input('resourceId');
        $resourceId = is_null($resourceId) ? $this->input('resources') : $resourceId;
        $resourceId = is_null($resourceId) ? $this->resourceId : $resourceId;
        $resourceId = is_null($resourceId) ? $this->route('resourceId') : $resourceId;
        return $resourceId;
	}

	public function resourceKey()
	{
		return $this->route('resource');
	}

	public function viaResourceKey()
	{
		return $this->input('viaResource');
	}

	public function viaResourceId()
	{
		return $this->input('viaResourceId');
	}

	public function serializedModel()
	{
		$resourceKey = $this->resourceKey();		
		$resourceId = $this->resourceId();
		if ($resourceKey && $resourceId) {
			return $this->findModelQuery($resourceId)->first();	
		}
		return null;
	}

	public function serializedRequest() {
		return [
			'isCreateOrAttachRequest' => $this->isCreateOrAttachRequest(),
			'isUpdateOrUpdateAttachedRequest' => $this->isUpdateOrUpdateAttachedRequest(),
		];
	}

}

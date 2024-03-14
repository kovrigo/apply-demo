<?php

namespace App\Tenancy\Traits;

use App\Tenancy\Scopes\TenantScope;

trait RespectsTenancy
{

    public static function bootRespectsTenancy()
    {
        // Rewrite tenant before saving the model
        static::saving(function ($model) {
            $model->tenant_id = apply()->tenantId;
        });
        // Scope model by tenant
        static::addGlobalScope(new TenantScope);
    }

    public function tenant()
    {
        return $this->belongsTo('App\Tenant');
    }

}
<?php

namespace App\Tenancy\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class TenantScope implements Scope
{
    /**
     * Scope model by tenant.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (apply()->tenantId) {
            $builder->where($model->getTable() . '.tenant_id', apply()->tenantId);
        }
    }
    
}
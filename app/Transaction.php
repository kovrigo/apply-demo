<?php

namespace App;

use App\Settings\Model;
use App\Tenancy\Scopes\TenantScope;

class Transaction extends Model
{
    use \App\Relations\Transaction;
	use \App\Restrictions\Transaction;
	use \App\Traits\RespectsOwnership;

    protected static function boot()
    {   
        parent::boot(); 
        // Rewrite tenant before saving the model
        static::saving(function ($model) {
        	if (!apply()->isHost) {
        		$model->tenant_id = apply()->tenantId;
        	}
        });
        // Scope model by tenant
        if (!apply()->isHost) {
			static::addGlobalScope(new TenantScope);
        }
    }

}


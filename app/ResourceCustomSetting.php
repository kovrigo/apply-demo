<?php

namespace App;

use App\Settings\Model;
use Illuminate\Database\Eloquent\Builder;

class ResourceCustomSetting extends Model
{
    use \App\Relations\ResourceCustomSetting;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Restrictions\ResourceCustomSetting;

    protected $casts = [
        'json_value' => 'array',
        'settings' => 'array',
    ];

    protected static function boot()
    {   
        parent::boot(); 
        static::addGlobalScope('named', function (Builder $builder) {
            $builder->whereNotNull('name');
        });
    }

}


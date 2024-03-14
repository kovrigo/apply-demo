<?php

namespace App;

use App\Settings\Model;
use Illuminate\Database\Eloquent\Builder;

class ResourceSetting extends Model
{
    use \App\Relations\ResourceSetting;
	use \App\Restrictions\ResourceSetting;

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


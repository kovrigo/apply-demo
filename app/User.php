<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use EloquentJs\Model\EloquentJsQueries;
use Illuminate\Database\Eloquent\Builder;
use App\Tenancy\Scopes\TenantScope;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements HasMedia
{
    use Notifiable, SoftDeletes, EloquentJsQueries;
    use \Illuminate\Notifications\Notifiable;
    use \App\Relations\User;
    use \App\Media\User;
    use \App\Restrictions\User;

    protected static function boot()
    {   
        parent::boot(); 
        // Rewrite tenant before saving the model
        static::saving(function ($model) {
            if (is_null($model->tenant_id)) {
                $model->tenant_id = apply()->tenantId;
            }
        });
        // Scope model by tenant
        static::addGlobalScope(new TenantScope);
    }

    public static function generateApiToken() {
        return str_random(60);
    }

    public static function generatePassword() {
        return str_random(16);
    }

    public static $resourceTitleAttribute = "default_title";

    public function getDefaultTitleAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getModelTitleAttribute()
    {
        return $this->resource_title ?: $this->{static::$resourceTitleAttribute};
    }

    public function saveWithoutEvents(array $options = [])
    {
        return static::withoutEvents(function() use ($options) {
            return $this->save($options);
        });
    }

    public function scopeMe($query)
    {
        return $query->where('id', apply()->user->id);
    }    

}

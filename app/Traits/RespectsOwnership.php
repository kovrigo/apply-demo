<?php

namespace App\Traits;

trait RespectsOwnership
{

    public static function bootRespectsOwnership()
    {
        // Rewrite owner before saving the model
        static::creating(function ($model) {
            if (is_null($model->user_id)) {
                $model->user_id = apply()->user->id;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use App\Settings\Model;

class Tenant extends Model
{
    use \App\Relations\Tenant;
	use \App\Restrictions\Tenant;

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            $default = file_get_contents(resource_path() . '/views/vendor/mail/html/themes/default.css');
            $name = 'tenant' . $model->id . '.css';
            $content = $default . $model->email_css;
            file_put_contents(resource_path() . '/views/vendor/mail/html/themes/' . $name, $content);
        });
        if (apply()->authenticated) {
            if (!apply()->isHost) {
                static::addGlobalScope('tenant', function (Builder $builder) {
                    $builder->where('id', apply()->tenantId);
                });
            }
        }
    }

    public function getBalanceAttribute()
    {
        $credit = $this->transactions()
            ->whereIn('type', ['credit', 'refund'])
            ->where('currency_id', $this->currency_id)
            ->sum('amount');
        $debit = $this->transactions()
            ->whereIn('type', ['debit'])
            ->where('currency_id', $this->currency_id)
            ->sum('amount');
        return $credit - $debit;
    }

}


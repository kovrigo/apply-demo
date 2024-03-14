<?php

namespace App;

use Denismitr\Translit\Translit;
use App\Settings\Model;
use App\Ad;

class Job extends Model
{
	use \App\Relations\Job;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Workflows\Traits\Workflowable;
	use \App\Restrictions\Job;
    use \App\Traits\RespectsOwnership;

    protected static function boot()
    {
        parent::boot();
        static::updating(function ($model) {
            if (is_null($model->page)) {
                $company = $model->workplace->addressable->company->name;
                $job = $model->name;
                // Slugify
                $company = (new Translit($company))->getSlug();
                $job = (new Translit($job))->getSlug();
                // Set page
                $model->page = '/' . $company . '/' . $job . '/' . $model->id;
            }
        });
        static::saving(function ($model) {
            $model->reserved = is_null($model->reserved) ? 0 : $model->reserved;
            $model->spent = is_null($model->spent) ? 0 : $model->spent;
            $model->balance = $model->reserved - $model->spent;
        });
    }

    public function getApplicationPriceAttribute()
    {
        return Ad::getApplicationPrice($this);
    }

    public function getApplicationsCountAttribute()
    {
        return $this->applications()->count();
    }

    public function getAverageCpmAttribute()
    {
        $count = 0;
        $total = 0;
        foreach ($this->functionalAreas as $functionalArea) {
            // For each interest
            foreach ($functionalArea->interests as $interest) {
                // For each advertising platform (advertising platform interest)
                foreach ($interest->advertisingPlatformInterests as $advertisingPlatformInterest) {
                    $total += $advertisingPlatformInterest->cpm ?: Ad::DEFAULT_CPM;
                    $count += 1;
                }
            }
        }
        if ($count == 0) {
            return 0;
        }
        return $total / $count;
    }

    public function getDailyBudgetAttribute()
    {
        if (is_null($this->applications_per_day_max)) {
            return $this->balance;
        } else if ($this->applications_per_day_max <= 0) {
            return 0;
        }
        if ($this->application_price * $this->applications_per_day_max > $this->balance) {
            return $this->balance >= 0 ? $this->balance : 0;
        } else {
            return $this->application_price * $this->applications_per_day_max;
        }
    }

    public function getApplicationPrices()
    {
        return $this->ads->map(function ($ad) {
            return $ad->avg_application_price;
        })->filter(function ($price) {
            return !is_null($price);
        });
    }

    public function getAvgApplicationPriceAttribute()
    {
        return $this->getApplicationPrices()->avg();
    }

    public function getMedianApplicationPriceAttribute()
    {
        return $this->getApplicationPrices()->median();
    }

}


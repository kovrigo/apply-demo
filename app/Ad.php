<?php

namespace App;

use App\Settings\Model;
use App\Job;
use App\Cpm;
use App\Conversion;
use App\CpmGeoMultiplier;
use App\AdvertisingPlatform;
use App\Marketing\MarketingManager;

class Ad extends Model
{
	use \App\Relations\Ad;
	use \App\Tenancy\Traits\RespectsTenancy;
	use \App\Restrictions\Ad;

    protected $casts = [
        'advertising_platform_data' => 'array',
    ];

    const DEFAULT_CPM = 100;

    public static function promote(Job $job)
    {
        // If job is promoted
        if ($job->promoted) {
            // For each functional area
                foreach ($job->functionalAreas as $functionalArea) {
                    // For each interest
                    foreach ($functionalArea->interests as $interest) {
                        // For each advertising platform (advertising platform interest)
                        foreach ($interest->advertisingPlatformInterests as $advertisingPlatformInterest) {
                            // For each creative
                            foreach ($job->creatives as $creative) {
                                // If creative is promoted
                                if ($creative->promoted) {
                                    // If ad exists and not stopped
                                    $ad = Ad::where('job_id', $job->id)
                                        ->where('advertising_platform_interest_id', $advertisingPlatformInterest->id)
                                        ->where('creative_id', $creative->id)
                                        ->where('stopped', false)
                                        ->first();
                                    // If ad doesn't exist, create new ad
                                    if (is_null($ad)) {
                                        $ad = new Ad;
                                        $ad->job()->associate($job);
                                        $ad->advertisingPlatformInterest()->associate($advertisingPlatformInterest);
                                        $ad->creative()->associate($creative);
                                        $ad->spent = 0;
                                        $ad->clicks = 0;
                                        $ad->daily_budget = 0;
                                        $ad->synced = false;
                                        $ad->ineffective = false;
                                        $ad->save();
                                    }
                                } else {
                                    // If creative is not promoted
                                    // Stop all ads with this creative
                                    foreach ($creative->ads as $ad) {
                                        $ad->stopped = true;
                                        $ad->synced = false;
                                        $ad->save();
                                    }
                                }
                            }
                        }
                    }
                }

        } else {
            // If job is not promoted
            // Stop all ads
            foreach ($job->ads as $ad) {
                if (!$ad->stopped) {
                    $ad->stopped = true;
                    $ad->synced = false;
                    $ad->save();
                }
            }            
        }
        static::allocateBudget($job);
        MarketingManager::syncJob($job);
    }

    public static function syncSpentFromAdsToJob(Job $job)
    {
        $job->spent = $job->ads()->sum('spent');
        $job->balance = $job->reserved - $job->spent;
        $job->saveWithoutEvents();
    }

    public static function optimize(Job $job)
    {
        // Pause ads with low conversion
        foreach ($job->ads as $ad) {
            if ($ad->should_optimize) {
                if ($ad->applications_count / $ad->clicks < 0.005) {
                    $ad->ineffective = true;
                    $ad->ineffective_reason = 'conversion';
                    $ad->synced = false;
                    $ad->saveWithoutEvents();
                }
            }
        }
        // Pause ads with highest application costs
        if ($job->applications_count > 50) {
            $adsPercentToPause = $job->median_application_price <= $job->avg_application_price ? 0.25 : 0.05;
            $ads = $job->ads()->where('stopped', false)->get()->filter(function ($ad) {
                return $ad->should_optimize && 
                    (!$ad->ineffective || ($ad->ineffective && $ad->ineffective_reason == 'cost'));
            });
            $adsCountToPause = (int) round($ads->count() * $adsPercentToPause);
            $ads->sortByDesc(function ($ad) {
                return $ad->avg_application_price;
            })->each(function ($ad, $index) use ($adsCountToPause) {
                if ($index < $adsCountToPause) {
                    if ($ad->ineffective == false) {
                        $ad->ineffective = true;
                        $ad->ineffective_reason = 'cost';
                        $ad->synced = false;
                        $ad->saveWithoutEvents();
                    }
                } else {
                    if ($ad->ineffective == true) {
                        $ad->ineffective = false;
                        $ad->ineffective_reason = null;
                        $ad->synced = false;
                        $ad->saveWithoutEvents();
                    }
                }
            });
        }
        static::allocateBudget($job);
        MarketingManager::syncJob($job);
    }

    public static function allocateBudget(Job $job)
    {
        // Set daily budget of stopped and paused ads to 0
        foreach ($job->ads as $ad) {
            if ($ad->stopped || $ad->ineffective) {
                if ($ad->daily_budget != 0) {
                    $ad->synced = false;
                    $ad->daily_budget = 0;
                    $ad->saveWithoutEvents();
                }
            }
        }
        // Get all promoted ads
        $promotedAds = $job->ads()
            ->where('stopped', false)
            ->where('ineffective', false)
            ->get();
        // Get cpm of all promoted ads
        $promotedAdsCpmSum = $promotedAds->map(function ($ad) {
            return $ad->cpm;
        })->sum();
        // Get job daily budget
        $jobDailyBudget = $job->daily_budget;
        // Allocate budget for the new ads (that are not ready for optimization yet)
        $allocatedBudget = 0;
        $promotedAds->filter(function ($ad) {
            return !$ad->should_optimize;
        })->each(function ($ad) use ($promotedAdsCpmSum, $jobDailyBudget, &$allocatedBudget) {
            $newBudget = $ad->cpm / $promotedAdsCpmSum * $jobDailyBudget;
            if ($ad->daily_budget != $newBudget) {
                $ad->daily_budget = $newBudget;
                $ad->synced = false;
                $ad->saveWithoutEvents();
            }
            $allocatedBudget += $newBudget;
        });
        // Allocate budget for the rest of the ads
        $shouldOptimizeAds = $promotedAds->filter(function ($ad) {
            return $ad->should_optimize;
        });
        $spentSum = $shouldOptimizeAds->map(function ($ad) {
            return $ad->spent;
        })->sum();
        $weights = $shouldOptimizeAds->mapWithKeys(function ($ad) use ($spentSum) {
            return [
                $ad->id => $spentSum * $ad->applications_count / pow($ad->avg_application_price, 2)
            ];
        });
        $weightsSum = $weights->sum();
        $shouldOptimizeAds->each(function ($ad) use ($weights, $weightsSum, $jobDailyBudget, $allocatedBudget) {
            $newBudget = ($jobDailyBudget - $allocatedBudget) * $weights[$ad->id] / $weightsSum;
            if ($ad->daily_budget != $newBudget) {
                $ad->daily_budget = $newBudget;
                $ad->synced = false;
                $ad->saveWithoutEvents();
            }
        });
    }

    public static function syncAdvertisingPlatformResponsesFromAdsToCreatives(Job $job)
    {
        $creatives = $job->creatives;
        foreach ($creatives as $creative) {
            $ads = $creative->ads()
                ->where('stopped', false)
                ->whereNotNull('moderation')
                ->get();
            $responses = [];
            foreach (AdvertisingPlatform::pluck('google_talent_solution_id') as $code) {
                $advertisingPlatformResponses = $ads->filter(function ($ad) use ($code) {
                    return $ad->advertisingPlatformInterest->advertisingPlatform->google_talent_solution_id == $code;
                })->map(function ($ad) {
                    return $ad->moderation;
                })->unique();
                if ($advertisingPlatformResponses->count() > 0) {
                    $responses[] = $code . "\n\n" . $advertisingPlatformResponses->implode("\n\n");
                }
            }
            $responses = $responses ? implode("\n\n", $responses) : null;
            $creative->advertising_platform_responses = $responses;
            $creative->saveWithoutEvents();
        }
    }

    public static function getApplicationPrice(Job $job)
    {
        // Average CPM for all functional areas and advertising platforms
        $baseCpm = $job->average_cpm;
        $baseCpm = $baseCpm == 0 ? self::DEFAULT_CPM : $baseCpm;
        // CPM geo multiplyer by workplace address
        $state = $job->workplace->addressable->state;
        $country = $job->workplace->addressable->country;
        $geoMultiplier = CpmGeoMultiplier::where('state', $state)->value('cpm_multiplier');
        if (is_null($geoMultiplier)) {
            $geoMultiplier = CpmGeoMultiplier::where('country', $country)->whereNull('state')->value('cpm_multiplier');
        }
        $geoMultiplier = is_null($geoMultiplier) ? 1 : $geoMultiplier;
        // CPM multiplier by company popularity rating
        $populatiryMultiplier = optional($job->workplace->addressable->company->popularityRating)->cpm_multiplier;
        $populatiryMultiplier = is_null($populatiryMultiplier) ? 1 : $populatiryMultiplier;
        // CPM
        $cpm = $baseCpm * $geoMultiplier * $populatiryMultiplier;
        // Price of one application
        return $cpm / 1000 / Conversion::impressionToAction();
    }

    public function getCpmAttribute()
    {
        $baseCpm = $this->advertisingPlatformInterest->cpm;
        $baseCpm = is_null($baseCpm) ? self::DEFAULT_CPM : $baseCpm;
        // CPM geo multiplyer by workplace address
        $state = $this->job->workplace->addressable->state;
        $country = $this->job->workplace->addressable->country;
        $geoMultiplier = CpmGeoMultiplier::where('state', $state)->value('cpm_multiplier');
        if (is_null($geoMultiplier)) {
            $geoMultiplier = CpmGeoMultiplier::where('country', $country)->whereNull('state')->value('cpm_multiplier');
        }
        $geoMultiplier = is_null($geoMultiplier) ? 1 : $geoMultiplier;
        // CPM multiplier by company popularity rating
        $populatiryMultiplier = optional($this->job->workplace->addressable->company->popularityRating)->cpm_multiplier;
        $populatiryMultiplier = is_null($populatiryMultiplier) ? 1 : $populatiryMultiplier;
        // CPM
        return $baseCpm * $geoMultiplier * $populatiryMultiplier;
    }

    public function getApplicationsCountAttribute()
    {
        return $this->applications()->count();
    }

    public function getAvgApplicationPriceAttribute()
    {
        return $this->applications_count ? $this->spent / $this->applications_count : null;
    }

    public function getShouldOptimizeAttribute()
    {
        return $this->clicks > 200;
    }

    public function scopeOfAdvertisingPlatform($query, $advertisingPlatformCode)
    {
        $advertisingPlatform = AdvertisingPlatform::where('google_talent_solution_id', $advertisingPlatformCode)->first();
        $advertisingPlatformInterestIds = $advertisingPlatform->advertisingPlatformInterests()->pluck('id')->all();
        return $query->whereIn('advertising_platform_interest_id', $advertisingPlatformInterestIds);
    }

}


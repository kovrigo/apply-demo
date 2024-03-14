<?php

namespace App\Marketing;

use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Ad;
use FacebookAds\Object\AdCreative;
use FacebookAds\Object\AdImage;
use FacebookAds\Object\AdCreativeLinkData;
use FacebookAds\Object\Fields\AdCreativeLinkDataFields;
use FacebookAds\Object\AdCreativeObjectStorySpec;
use FacebookAds\Object\Fields\AdCreativeObjectStorySpecFields;
use FacebookAds\Object\Fields\AdCreativeFields;
use FacebookAds\Object\Values\AdCreativeCallToActionTypeValues;
use FacebookAds\Object\Fields\AdFields;
use FacebookAds\Object\Fields\TargetingFields;
use FacebookAds\Object\Targeting;
use Carbon\Carbon;
use App\Marketing\Jobs\Facebook\SyncAd;
use App\Marketing\Jobs\Facebook\SyncCampaign;
use App\Marketing\Jobs\Facebook\ImportStats;

class Facebook
{

    public static function syncJob($job)
    {
        // Get all facebook ads of the job to sync
        $ads = $job->ads()->ofAdvertisingPlatform('FACEBOOK')->where('synced', false)->get();
        foreach ($ads as $ad) {
            // Dispatch a job to sync ad
            SyncAd::dispatch($ad);
        }
        // Dispatch a job to sync campaign (budget)
        SyncCampaign::dispatch($job);
    }

    public static function importStats($job)
    {
        ImportStats::dispatch($job);
    }

}


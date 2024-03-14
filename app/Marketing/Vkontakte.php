<?php

namespace App\Marketing;

use VK\Client\VKApiClient;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Marketing\Jobs\Vkontakte\SyncAd;
use App\Marketing\Jobs\Vkontakte\ImportStats;

class Vkontakte
{

    public static function syncJob($job)
    {
        // Get all vk ads of the job to sync
        $ads = $job->ads()->ofAdvertisingPlatform('VKONTAKTE')->where('synced', false)->get();
        foreach ($ads as $ad) {
            // Dispatch a job to sync ad
            SyncAd::dispatch($ad);
        }
    }

    public static function importStats($job)
    {
    	ImportStats::dispatch($job);
    }    

}


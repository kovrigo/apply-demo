<?php

namespace App\Marketing;

use App\Ad;
use App\Marketing\Facebook;
use App\Marketing\Vkontakte;

class MarketingManager
{

    public static function syncJob($job)
    {
        Facebook::syncJob($job);
        Vkontakte::syncJob($job);
    }

    public static function importStats($job)
    {
        Ad::syncSpentFromAdsToJob($job);
        Ad::syncAdvertisingPlatformResponsesFromAdsToCreatives($job);        
        Ad::optimize($job);

        Facebook::importStats($job);
        Vkontakte::importStats($job);
    }

}


<?php

namespace App\Marketing\Jobs\Facebook;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
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
use Illuminate\Support\Facades\Redis;
use App\Job;

class SyncCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jobModel;

    public function __construct(Job $jobModel)
    {
        $this->jobModel = $jobModel;
    }

    public function handle()
    {
        Redis::funnel('facebook')->limit(1)->then(function () {
            $job = $this->jobModel;
            $advertisingPlatformBudget = $job->ads()->ofAdvertisingPlatform('FACEBOOK')->get()->sum('daily_budget');

            Api::init(config('app.facebook_app_id'), config('app.facebook_app_secret'), config('app.facebook_access_token'));
            $api = Api::instance();
            //$api->setLogger(new CurlLogger());

            $syncedAdOfTheSameJob = $job->ads()
                ->whereNotNull('advertising_platform_uuid')
                ->first();
            $syncedAd = new Ad($syncedAdOfTheSameJob->advertising_platform_uuid);
            $syncedAd = $syncedAd->getSelf(['campaign_id']);
            $campaign = new Campaign($syncedAd->campaign_id);

            if ($advertisingPlatformBudget == 0) {
                $campaign->updateSelf([], [
                    'status' => 'PAUSED',
                ]);
            } else {
                $campaign->updateSelf([], [
                    'status' => 'ACTIVE',
                    'daily_budget' => $advertisingPlatformBudget . '00',
                ]);
            }
            
        }, function () {
            return $this->release();
        });   
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addYears(1);
    }

}
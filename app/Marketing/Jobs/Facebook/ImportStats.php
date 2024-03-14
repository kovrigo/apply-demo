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

class ImportStats implements ShouldQueue
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

            Api::init(config('app.facebook_app_id'), config('app.facebook_app_secret'), config('app.facebook_access_token'));   
            $api = Api::instance();
            //$api->setLogger(new CurlLogger());

            // Clicks, spent
            $syncedAdOfTheSameJob = $job->ads()
                ->whereNotNull('advertising_platform_uuid')
                ->first();
            $syncedAd = new Ad($syncedAdOfTheSameJob->advertising_platform_uuid);
            $syncedAd = $syncedAd->getSelf(['campaign_id']);
            $campaignId = $syncedAd->campaign_id;

            $campaign = new Campaign($campaignId);
            $fields = ['ad_id', 'clicks', 'spend'];
            $insights = $campaign->getInsights($fields, ['date_preset' => 'maximum', 'level' => 'ad']);
            foreach ($insights as $insight) {
                $ad = \App\Ad::where('advertising_platform_uuid', $insight->ad_id)->first();
                if ($ad) {
                    $ad->clicks = $insight->clicks;
                    $ad->spent = $insight->spend;
                    $ad->saveWithoutEvents();
                }
            }

            // Recomendations
            $syncedAds = $campaign->getAds(['id', 'recommendations']);
            foreach ($syncedAds as $syncedAd) {
                $ad = \App\Ad::where('advertising_platform_uuid', $syncedAd->id)->first();
                if ($ad) {
                    $moderation = null;
                    if ($syncedAd->recommendations) {
                        $moderation = collect($syncedAd->recommendations)->map(function ($recommendation) {
                            return $recommendation['title'] . "\n" . $recommendation['message'];
                        })->implode("\n\n");
                    }
                    if ($ad->moderation != $moderation) {
                        $ad->moderation = $moderation;
                        $ad->saveWithoutEvents();
                    }
                }
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
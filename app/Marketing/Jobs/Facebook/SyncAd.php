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

class SyncAd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ad;

    public function __construct($ad)
    {
        $this->ad = $ad;
    }

    public function handle()
    {
        Redis::funnel('facebook')->limit(1)->then(function () {
            $ad = $this->ad;
            $job = $ad->job;

            $active = config('app.env') == 'production' ? 'ACTIVE' : 'PAUSED';
            $paused = 'PAUSED';
            $archived = 'ARCHIVED';

            Api::init(config('app.facebook_app_id'), config('app.facebook_app_secret'), config('app.facebook_access_token'));
            $api = Api::instance();
            //$api->setLogger(new CurlLogger());

            $accountId = config('app.facebook_account_id');
            $account = new AdAccount($accountId);

            // Get ad set to copy
            $adSetId = $ad->advertisingPlatformInterest->advertising_platform_uuid;
            $adSet = new AdSet($adSetId);
            // Update existing ad
            if ($ad->advertising_platform_uuid) {
                // Status
                $syncedAd = new Ad($ad->advertising_platform_uuid);
                $syncedAd->updateSelf([], [
                    'status' => $ad->stopped ? $archived : ($ad->ineffective ? $paused : $active),
                ]);
                $ad->synced = true;
                $ad->saveWithoutEvents();
            } else {
                // Create new ad
                // Check if campaign and ad set have already been created for the job/interest
                $copiedCampaignId = null;
                $copiedAdSetId = null;
                $syncedAdWithTheSameInterest = $job->ads()
                    ->ofAdvertisingPlatform('FACEBOOK')
                    ->where('advertising_platform_interest_id', $ad->advertisingPlatformInterest->id)
                    ->whereNotNull('advertising_platform_uuid')
                    ->first();
                if (!is_null($syncedAdWithTheSameInterest)) {
                    // Get ad
                    $syncedAd = new Ad($syncedAdWithTheSameInterest->advertising_platform_uuid);
                    $syncedAd = $syncedAd->getSelf(['adset_id', 'campaign_id']);
                    $copiedCampaignId = $syncedAd->campaign_id;
                    $copiedAdSetId = $syncedAd->adset_id;
                } else {
                    $syncedAdOfTheSameJob = $job->ads()
                        ->ofAdvertisingPlatform('FACEBOOK')
                        ->whereNotNull('advertising_platform_uuid')
                        ->first();
                    if (!is_null($syncedAdOfTheSameJob)) {
                        $syncedAd = new Ad($syncedAdOfTheSameJob->advertising_platform_uuid);
                        $syncedAd = $syncedAd->getSelf(['campaign_id']);
                        $copiedCampaignId = $syncedAd->campaign_id;
                    }
                }

                // Create new campaign for the job
                if (is_null($copiedCampaignId)) {
                    // Get campaign id
                    $adSet = $adSet->getSelf(['id', 'campaign_id']);
                    $campaignId = $adSet->campaign_id;
                    // Make a copy of the campaign
                    $campaign = new Campaign($campaignId);
                    $copiedCampaign = $campaign->createCopy(['id']);
                    $copiedCampaignId = $copiedCampaign->id;
                    // Change campaign name
                    $companyName = $job->workplace->addressable->company->name;
                    $jobName = $job->name;
                    $copiedCampaign->updateSelf([], [
                        'name' => $companyName . ' | ' . $jobName,
                    ]);
                }

                // Create new ad set for the interest
                if (is_null($copiedAdSetId)) {
                    // Make a copy of the ad set
                    $copiedAdSet = $adSet->createCopy(['id'], [
                        'campaign_id' => $copiedCampaignId,
                        'end_time' => 0,
                        'start_time' => Carbon::now('UTC')->toDateTimeString(),
                    ]);
                    $copiedAdSetId = $copiedAdSet->id;
                    // Change ad set name and targeting
                    $copiedAdSet = new AdSet($copiedAdSetId);
                    $targeting = $copiedAdSet->getSelf(['targeting'])->targeting;
                    // Age
                    unset_or_update($targeting, 'age_min', $job->age_min);
                    unset_or_update($targeting, 'age_max', $job->age_max);
                    // Sex
                    $targeting['genders'] = is_null($job->sex) ? [1, 2] : ($sex == 'male' ? [1] : [2]);
                    // Geo
                    $locations = [
                        [
                            'latitude' => $job->workplace->addressable->latitude,
                            'longitude' => $job->workplace->addressable->longitude,
                        ],
                    ];
                    foreach ($job->cities as $city) {
                        $locations[] = [
                            'latitude' => $city->latitude,
                            'longitude' => $city->longitude,                            
                        ];
                    }
                    $locations = collect($locations)->map(function ($location) {
                        return [
                            'latitude' => $location['latitude'],
                            'longitude' => $location['longitude'],
                            'distance_unit' => 'kilometer',
                            'radius' => 80,
                        ];
                    })->all();
                    $targeting['geo_locations']['custom_locations'] = $locations;
                    unset($targeting['geo_locations']['countries']);
                    // Save new targeting info
                    $copiedAdSet->updateSelf([], [
                        'name' => $ad->advertisingPlatformInterest->interest->name,
                        'targeting' => $targeting,
                    ]);
                }

                // Upload image
                $images = $account->createAdImage([], [
                    'filename' => $ad->creative->getMedia('creative')->first()->getPath()
                ]);
                $imageHash = collect($images->images)->first()['hash'];

                // Get url
                $url = trim($job->tenant->career_website_url, '/') . $job->page;
                // Get call-to-action
                $callToActionTypeId = $ad->creative->callToActionType->id;
                $callToAction = $ad->advertisingPlatformInterest
                    ->advertisingPlatform
                    ->advertisingPlatformCallToActionTypes()
                    ->where('call_to_action_type_id', $callToActionTypeId)
                    ->first()
                    ->advertising_platform_uuid;

                $link_data = new AdCreativeLinkData;
                $link_data->setData([
                    'message' => $ad->creative->title,
                    'description' => $ad->creative->description,
                    'link' => $url,
                    'image_hash' => $imageHash,
                    'call_to_action' => [
                        'type' => $callToAction,
                        'value' => ['link' => $url],
                    ],
                ]);

                $object_story_spec = new AdCreativeObjectStorySpec;
                $object_story_spec->setData([
                    'page_id' => $job->workplace->addressable->company->facebook_page_uuid,
                    'link_data' => $link_data,
                ]);

                // Add creative
                $creative = $account->createAdCreative([], [
                    'title' => $ad->creative->title,
                    'body' => $ad->creative->description,
                    'object_story_spec' => $object_story_spec,
                ]);
                $creativeId = $creative->id;

                // Create ad
                $facebookAd = $account->createAd([], [
                    'name' => $ad->creative->title,
                    'adset_id' => $copiedAdSetId,
                    'creative' => ['creative_id' => $creativeId],
                    'status' => $active,
                ]);

                $ad->advertising_platform_uuid = $facebookAd->id;
                $ad->synced = true;
                $ad->saveWithoutEvents();
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
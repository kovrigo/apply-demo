<?php

namespace App\Marketing\Jobs\Vkontakte;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use VK\Client\VKApiClient;
use Illuminate\Support\Str;
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
        Redis::funnel('vkontakte')->limit(1)->then(function () {
            $ad = $this->ad;
            $job = $ad->job;

            $access_token = config('app.vk_access_token');
            $account_id = config('app.vk_account_id');
            $client_id = config('app.vk_client_id');
            $active = config('app.env') == 'production' ? 1 : 0;
            $paused = 0;
            $job = $ad->job;
            $companyName = $job->workplace->addressable->company->name;
            $vk = new VKApiClient();

            // Update existing ad
            if ($ad->advertising_platform_uuid) {
                if ($ad->stopped) {
                    $vk->ads()->deleteAds($access_token, [
                        'account_id' => $account_id,
                        'ids' => json_encode([$ad->advertising_platform_uuid]),
                    ]);
                    usleep(500000);
                } else {
                    $adFields = [
                        'ad_id' => $ad->advertising_platform_uuid,
                        'status' => $ad->ineffective ? $paused : $active,
                    ];
                    if ($ad->daily_budget == 0) {
                        $adFields['status'] = $paused;
                    } else {
                        $adFields['day_limit'] = $ad->daily_budget;
                    }
                    $x = $vk->ads()->updateAds($access_token, [
                        'account_id' => $account_id,
                        'data' => json_encode([$adFields]),
                    ]);
                    usleep(2000000);
                }
                $ad->synced = true;
                $ad->saveWithoutEvents();
            } else {
                // Check if client and campaign have already been created for the company/job
                $copiedClientId = null;
                $copiedCampaignId = null;
                $syncedAdOfTheSameJob = $job->ads()
                    ->ofAdvertisingPlatform('VKONTAKTE')
                    ->whereNotNull('advertising_platform_uuid')
                    ->whereNotNull('advertising_platform_data')
                    ->first();
                if (!is_null($syncedAdOfTheSameJob)) {
                    $copiedClientId = $syncedAdOfTheSameJob->advertising_platform_data['client_id'];
                    $copiedCampaignId = $syncedAdOfTheSameJob->advertising_platform_data['campaign_id'];
                } else {
                    $syncedAdOfTheSameCompany = $job->workplace->addressable->company->ads()
                        ->ofAdvertisingPlatform('VKONTAKTE')
                        ->whereNotNull('advertising_platform_uuid')
                        ->whereNotNull('advertising_platform_data')
                        ->first();
                    if (!is_null($syncedAdOfTheSameCompany)) {
                        $copiedClientId = $syncedAdOfTheSameCompany->advertising_platform_data['client_id'];
                    }
                }

                // Get ad to copy
                $adToCopyId = $ad->advertisingPlatformInterest->advertising_platform_uuid;
                $adToCopyTargeting = $vk->ads()->getAdsTargeting($access_token, [
                    'account_id' => $account_id,
                    'client_id' => $client_id,
                    'ad_ids' => json_encode([$adToCopyId]),
                ])[0];
                usleep(500000);
                // Remove fields that should not be copied
                $dontCopyFields = ['id', 'campaign_id', 'count', 'country', 'cities', 'cities_not'];
                $dontCopyFields = collect($dontCopyFields)->flip();
                $adFields = collect($adToCopyTargeting)->diffKeys($dontCopyFields)->all();

                // Create new client for the company
                if (is_null($copiedClientId)) {
                    $copiedClientId = $vk->ads()->createClients($access_token, [
                        'account_id' => $account_id,
                        'data' => json_encode([[
                            'name' => $companyName,
                        ]]),
                    ])[0]['id'];
                    usleep(500000);
                }

                // Create new campaign for the job
                if (is_null($copiedCampaignId)) {
                    $copiedCampaignId = $vk->ads()->createCampaigns($access_token, [
                        'account_id' => $account_id,
                        'data' => json_encode([[
                            'client_id' => $copiedClientId,
                            'type' => 'adaptive_ads',
                            'name' => $job->name,
                            'status' => $active,
                        ]]),
                    ])[0]['id'];
                    usleep(500000);
                }

                // Upload creative
                $uploadUrl = $vk->ads()->getUploadURL($access_token, [
                    'ad_format' => 11,
                ]);
                $path = $ad->creative->getMedia('creative')->first()->getPath();
                $photo = $vk->getRequest()->upload($uploadUrl, 'file', $path);
                usleep(500000);

                // Upload logo
                $uploadUrl = $vk->ads()->getUploadURL($access_token, [
                    'ad_format' => 11,
                    'icon' => 1,
                ]);
                $path = $job->workplace->addressable->company->getMedia('logo')->first()->getPath();
                $photo_icon = $vk->getRequest()->upload($uploadUrl, 'file', $path);
                usleep(500000);

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

                // Targeting
                $adFields['sex'] = is_null($job->sex) ? 0 : ($sex == 'male' ? 2 : 1);
                $adFields['age_from'] = is_null($job->age_min) ? 0 : $job->age_min;
                $adFields['age_to'] = is_null($job->age_max) ? 0 : $job->age_max;

                // Geo
                // TODO: add other locations
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
                    return $location['latitude'] . ',' . $location['longitude'] . ',80000';
                })->implode(';');
                $adFields['geo_near'] = $locations;

                // Create ad
                $adFields['ad_format'] = 11;
                $adFields['cost_type'] = 3;
                $adFields['goal_type'] = 2;
                $adFields['ad_platform'] = "all";
                $adFields['publisher_platforms'] = "all";
                $adFields['publisher_platforms_auto'] = 1;
                $adFields['autobidding'] = 1;
                $adFields['day_limit'] = $ad->daily_budget;
                $adFields['all_limit'] = 0;
                $adFields['campaign_id'] = $copiedCampaignId;
                $adFields['status'] = $active;
                $adFields['link_url'] = $url;
                $adFields['link_button'] = strtolower($callToAction);
                $adFields['link_title'] = $ad->creative->title;
                $adFields['title'] = Str::limit($companyName, 25, '');
                $adFields['description'] = $ad->creative->description;
                $adFields['name'] = $ad->advertisingPlatformInterest->interest->name;
                $adFields['photo'] = $photo['photo'];
                $adFields['photo_icon'] = $photo_icon['photo'];

                $vkontakteAd = $vk->ads()->createAds($access_token, [
                    'account_id' => $account_id,
                    'data' => json_encode([$adFields]),
                ]);
                usleep(2000000);

                $ad->advertising_platform_uuid = $vkontakteAd[0]['id'];
                $ad->advertising_platform_data = [
                    'client_id' => $copiedClientId,
                    'campaign_id' => $copiedCampaignId,
                ];
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